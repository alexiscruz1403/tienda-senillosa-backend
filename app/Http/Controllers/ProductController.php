<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Http\Responses\ApiResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
            $products = $this->productService->getFeaturedProducts();
            return ApiResponse::success($products, 'Productos destacados obtenidos exitosamente');
        }catch(HttpException $e){
            return ApiResponse::error("Ocurrió un error al obtener productos destacados", $e->getStatusCode(), [$e->getMessage()]);
        }
    }

    public function likeProduct($productId)
    {
        try{
            $user = request()->user ?? null;
            $message = $this->productService->likeProduct($user, $productId);
            return ApiResponse::success(null, $message);
        }catch(HttpException $e){
            return ApiResponse::error("Ocurrió un error al procesar el Me Gusta", $e->getStatusCode(), [$e->getMessage()]);
        }
    }

    public function getSingleProduct($productId)
    {
        try{
            $product = $this->productService->getSingleProduct($productId);
            return ApiResponse::success($product, 'Producto obtenido exitosamente');
        }catch(HttpException $e){
            return ApiResponse::error("Ocurrió un error al obtener el producto", $e->getStatusCode(), [$e->getMessage()]);
        }
    }

    public function getRelatedProducts($productId)
    {
        try{
            $products = $this->productService->getRelatedProducts($productId);
            return ApiResponse::success($products, 'Productos relacionados obtenidos exitosamente');
        }catch(HttpException $e){
            return ApiResponse::error("Ocurrió un error al obtener productos relacionados", $e->getStatusCode(), [$e->getMessage()]);
        }
    }

    public function getManyProducts()
    {
        try{
            $search = request()->query('search', null);
            $category = request()->query('category', null);
            $gender = request()->query('gender', null);
            $ordering = request()->query('ordering', null);
            $page = request()->query('page', 1);

            $products = $this->productService->getManyProducts($search, $category, $gender, $page, $ordering);
            return ApiResponse::success($products, 'Productos obtenidos con exitosamente');
        }catch(HttpException $e){
            return ApiResponse::error("Ocurrió un error al obtener productos", $e->getStatusCode(), [$e->getMessage()]);
        }
    }
}
