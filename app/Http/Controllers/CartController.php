<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CartService;
use App\Http\Responses\ApiResponse;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function getCart(Request $request)
    {
        try {
            $user = $request->user;
            $cart = $this->cartService->getCart($user);
            return ApiResponse::success($cart, 'Carrito obtenido exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error('Ocurrió un error al obtener el carrito', $e->getCode(), $e->getMessage());
        }
    }

    public function addToCart(Request $request)
    {
        try {
            $user = $request->user;
            $productId = $request->input('product_id');
            $size = $request->input('size');
            $quantity = $request->input('quantity');

            $cartItem = $this->cartService->addToCart($user, $productId, $size, $quantity);
            return ApiResponse::success($cartItem, 'Producto agregado al carrito exitosamente', 201);
        } catch (\Exception $e) {
            return ApiResponse::error('Ocurrió un error al añadir el producto al carrito', $e->getCode(), [$e->getMessage()]);
        }
    }

    public function updateCartItem(Request $request)
    {
        try {
            $user = $request->user;
            $productId = $request->input('product_id');
            $size = $request->input('size');
            $quantity = $request->input('quantity');

            $cartItem = $this->cartService->updateCartItem($user, $productId, $size, $quantity);
            return ApiResponse::success($cartItem, 'Cantidad del producto en el carrito actualizada exitosamente', 200);
        } catch (\Exception $e) {
            return ApiResponse::error('Ocurrió un error al actualizar la cantidad en el carrrito', $e->getCode(), [$e->getMessage()]);
        }
    }

    public function removeFromCart(Request $request)
    {
        try {
            $user = $request->user;
            $productId = $request->input('product_id');
            $size = $request->input('size');

            $this->cartService->removeFromCart($user, $productId, $size);
            return ApiResponse::success(null, 'Producto eliminado del carrito exitosamente', 200);
        } catch (\Exception $e) {
            return ApiResponse::error('Ocurrió un error al eliminar el producto del carrito', $e->getCode(), [$e->getMessage()]);
        }
    }

    public function clearCart(Request $request)
    {
        try {
            $user = $request->user;

            $this->cartService->clearCart($user);
            return ApiResponse::success(null, 'Carrito vaciado exitosamente', 200);
        } catch (\Exception $e) {
            return ApiResponse::error('Ocurrió un error al vaciar el carrito', $e->getCode(), [$e->getMessage()]);
        }
    }
}
