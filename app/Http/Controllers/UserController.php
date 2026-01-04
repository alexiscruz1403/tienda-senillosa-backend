<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Responses\ApiResponse;

class UserController extends Controller
{
    protected $userService;

    public function __construct(\App\Services\UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getLikedProducts()
    {
        try{
            $user = request()->user ?? null;
            $page = request()->query('page', 1);
            $products = $this->userService->getLikedProducts($user, $page);
            return ApiResponse::success($products, 'Productos favoritos obtenidos con éxito');
        }catch(\Exception $e){
            return ApiResponse::error("Error al obtener productos favoritos", 500, $e->getMessage());
        }
    }
}
