<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Http\Responses\ApiResponse;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function getFeaturedProducts()
    {
        try{
            $user = request()->user ?? null;
            $products = $this->productService->getFeaturedProducts($user);
            return ApiResponse::success($products, 'Productos destacados obtenidos con éxito');
        }catch(\Exception $e){
            return ApiResponse::error("Error al obtener productos destacados", 500, $e->getMessage());
        }
    }

    public function likeProduct($productId)
    {
        try{
            $user = request()->user ?? null;
            $message = $this->productService->likeProduct($user, $productId);
            return ApiResponse::success(null, $message);
        }catch(\Exception $e){
            return ApiResponse::error("Error al procesar la solicitud de favorito", 500, $e->getMessage());
        }
    }
}
