<?php

namespace App\Http\Controllers;
use App\Services\LikeService;
use App\Http\Responses\ApiResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LikeController extends Controller
{
    protected $likeService;

    public function __construct(LikeService $likeService)
    {
        $this->likeService = $likeService;
    }

    public function getLikedProducts()
    {
        try{
            $user = request()->user ?? null;
            $page = request()->query('page', 1);
            $products = $this->likeService->getLikedProducts($user, $page);
            return ApiResponse::success($products, 'Productos favoritos obtenidos con exitosamente');
        }catch(HttpException $e){
            return ApiResponse::error("Ocurrió un error al obtener productos favoritos", $e->getStatusCode(), $e->getMessage());
        }
    }
}
