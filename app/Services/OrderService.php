<?php

namespace App\Services;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderStatus;
use App\Models\User;
use App\Models\Address;
use App\Models\Stock;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\OrderResource;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Illuminate\Support\Facades\Cache;

class OrderService{
    public function createOrder($user, $products){
        $userModel = User::find($user->user_id);

        if(!$userModel) throw new UnauthorizedHttpException("Usuario no encontrado");

        $validPurchase = true;
        $index = 0;
        while($validPurchase && $index < count($products)){
            $hasStock = $this->verifyStock($products[0]['product_id'], $products[0]['size'], $products[0]['quantity']);
            if(!$hasStock) $validPurchase = false;

            $index++;
        }

        if(!$validPurchase) throw new ConflictHttpException("No hay stock suficiente para realizar la compra");

        $address = Address::where('user_id', $user->user_id)->where('active', true)->first();

        if(!$address) throw new ConflictHttpException("El usuario no tiene una dirección asignada");

        $order = DB::transaction(function() use ($user, $products, $address){
            $order = Order::create([
                'user_id' => $user->user_id,
                'address_id' => $address->address_id
            ]);

            foreach($products as $product){
                OrderProduct::create([
                    'order_id' => $order->order_id,
                    'product_id' => $product['product_id'],
                    'product_size' => $product['size'],
                    'product_quantity' => $product['quantity'],
                    'product_price' => $product['price']
                ]);
                $this->updateStock($product['product_id'], $product['size'], $product['quantity']);
            }

            OrderStatus::create([
                'order_id' => $order->order_id,
                'status_id' => 1
            ]);

            $this->clearCart($user->user_id);

            Cache::forget("orders.user.{$user->user_id}");

            return $order;
        });

        return new OrderResource($order);
    }

    public function getOrders($user){
        $userModel = User::find($user->user_id);

        if(!$userModel) throw new UnauthorizedHttpException("Usuario no encontrado");

        $orders = Cache::remember("orders.user.{$user->user_id}", now()->addMinutes(5), function () use($user) {
            return Order::where('user_id', $user->user_id)->with(['orderProducts.product.images', 'orderStatuses.status'])->get();

        });
        return OrderResource::collection($orders);
    }

    public function getOrder($user, $orderId){
        $userModel = User::find($user->user_id);

        if(!$userModel) throw new UnauthorizedHttpException("Usuario no encontrado");

        $order = Cache::remember("order.{$orderId}", 600, function () use($orderId) {
            return Order::with(['orderProducts.product.images', 'orderStatuses.status'])->find($orderId);

        });

        if(!$order) throw new NotFoundHttpException("Compra no encontrada");

        if($order && $order->user_id != $userModel->user_id) throw new AccessDeniedHttpException("Esta compra pertenece a otro usuario");

        return new OrderResource($order);
    }

    private function verifyStock($productId, $productSize, $productQuantity){
        $stock = Stock::where('product_id', $productId)->where('size', $productSize)->first();

        if(!$stock) return false;

        if($stock && $stock->stock_quantity < $productQuantity) return false;

        return true;
    }

    private function updateStock($productId, $productSize, $productQuantity){
        $stock = Stock::where('product_id', $productId)->where('size', $productSize)->first();

        $newStock = $stock->stock_quantity - $productQuantity;
        $stock->stock_quantity = $newStock;
        $stock->save();
    }

    private function clearCart($userId){
        $userModel = User::find($userId);

        if(!$userModel) throw new UnauthorizedHttpException("Usuario no encontrado");

        Cart::where('user_id', $userModel->user_id)->delete();
    }
}
