<?php

namespace App\Http\Controllers;
use App\Services\OrderService;
use App\Http\Responses\ApiResponse;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function createOrder()
    {
        try{
            $user = request()->user;
            $products = request()->get('products', []);
            $order = $this->orderService->createOrder($user, $products);
            return ApiResponse::success($order, 'Compra completada exitosamente');
        }catch(\Exception $e){
            return ApiResponse::error('Ocurrió un error al completar la compra', $e->getCode(), [$e->getMessage()]);
        }
    }

    public function getOrders()
    {
        try{
            $user = request()->user;
            $orders = $this->orderService->getOrders($user);
            return ApiResponse::success($orders, 'Compras obtenidas exitosamente');
        }catch(\Exception $e){
            return ApiResponse::error('Ocurrió un error al obtener las compras', $e->getCode(), [$e->getMessage()]);
        }
    }

    public function getOrder($orderId)
    {
        try{
            $user = request()->user;
            $orderId =
            $order = $this->orderService->getOrder($user, $orderId);
            return ApiResponse::success($order, 'Compra obtenida exitosamente');
        }catch(\Exception $e){
            return ApiResponse::error('Ocurrió un error al obtener la compra', $e->getCode(), [$e->getMessage()]);
        }
    }
}
