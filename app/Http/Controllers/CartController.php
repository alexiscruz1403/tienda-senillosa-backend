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
            return ApiResponse::success($cart, 200);
        } catch (\Exception $e) {
            return ApiResponse::error(['error' => $e->getMessage()], 404);
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
            return ApiResponse::success($cartItem, 'Producto agregado al carrito', 201);
        } catch (\Exception $e) {
            return ApiResponse::error(['error' => $e->getMessage()], 400);
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
            return ApiResponse::success($cartItem, 'Cantidad del producto en el carrito actualizada', 200);
        } catch (\Exception $e) {
            return ApiResponse::error(['error' => $e->getMessage()], 400);
        }
    }

    public function removeFromCart(Request $request)
    {
        try {
            $user = $request->user;
            $productId = $request->input('product_id');
            $size = $request->input('size');

            $this->cartService->removeFromCart($user, $productId, $size);
            return ApiResponse::success(null, 'Producto eliminado del carrito', 200);
        } catch (\Exception $e) {
            return ApiResponse::error(['error' => $e->getMessage()], 400);
        }
    }

    public function clearCart(Request $request)
    {
        try {
            $user = $request->user;

            $this->cartService->clearCart($user);
            return ApiResponse::success(null, 'Carrito vaciado correctamente', 200);
        } catch (\Exception $e) {
            return ApiResponse::error(['error' => $e->getMessage()], 400);
        }
    }
}
