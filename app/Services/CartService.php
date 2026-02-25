<?php

namespace App\Services;
use App\Models\User;
use App\Models\Cart;
use App\Models\Stock;
use App\Http\Resources\CartResource;

class CartService
{
    public function getCart($user){
        $userModel = User::find($user->user_id);

        if(!$userModel) throw new \Exception("Usuario no encontrado");

        $cart = Cart::where('user_id', $userModel->user_id)->with(['stock', 'product'])->get();

        return CartResource::collection($cart);
    }

    public function getCartCount($userId){
        $userModel = User::find($userId);

        if(!$userModel) throw new \Exception("Usuario no encontrado");

        $cart = Cart::where('user_id', $userId)->get();

        return count($cart);
    }

    public function addToCart($user, $productId, $size, $quantity){
        $userModel = User::find($user->user_id);

        if(!$userModel) throw new \Exception("Usuario no encontrado");

        $stock = Stock::where('product_id', $productId)
                        ->where('size', $size)
                        ->first();

        if(!$stock) throw new \Exception("Stock no encontrado para el producto y talla especificados");

        $cartItem = Cart::where('user_id', $userModel->user_id)
                        ->where('stock_id', $stock->stock_id)
                        ->first();

        if($cartItem){
            Cart::where('user_id', $userModel->user_id)
                        ->where('stock_id', $stock->stock_id)
                        ->update([
                            'product_quantity' => $quantity + $cartItem->product_quantity,
                        ]);
        } else {
            $cartItem = Cart::create([
                'user_id' => $userModel->user_id,
                'stock_id' => $stock->stock_id,
                'product_quantity' => $quantity,
            ]);
        }

        return new CartResource($cartItem);
    }

    public function updateCartItem($user, $productId, $size, $quantity){
        $userModel = User::find($user->user_id);

        if(!$userModel) throw new \Exception("Usuario no encontrado");

        $stock = Stock::where('product_id', $productId)
                        ->where('size', $size)
                        ->first();

        if(!$stock) throw new \Exception("Stock no encontrado para el producto y talla especificados");

        $cartItem = Cart::where('user_id', $userModel->user_id)
                        ->where('stock_id', $stock->stock_id)
                        ->first();

        if(!$cartItem) throw new \Exception("El item no existe en el carrito");

        Cart::where('user_id', $userModel->user_id)
                ->where('stock_id', $stock->stock_id)
                ->update([
                    'product_quantity' => $quantity,
                ]);

        return true;
    }

    public function removeFromCart($user, $productId, $size){
        $userModel = User::find($user->user_id);

        if(!$userModel) throw new \Exception("Usuario no encontrado");

        $stock = Stock::where('product_id', $productId)
                        ->where('size', $size)
                        ->first();

        if(!$stock) throw new \Exception("Stock no encontrado para el producto y talla especificados");

        Cart::where('user_id', $userModel->user_id)
                ->where('stock_id', $stock->stock_id)
                ->delete();

        return true;
    }

    public function clearCart($user){
        $userModel = User::find($user->user_id);

        if(!$userModel) throw new \Exception("Usuario no encontrado");

        Cart::where('user_id', $userModel->user_id)->delete();

        return true;
    }
}
