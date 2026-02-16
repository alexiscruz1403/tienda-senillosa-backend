<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Responses\ApiResponse;
use App\Services\UserService;
use App\Utils\JWTUtil;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
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

    public function getUserInfo(){
        try{
            $user = request()->user ?? null;

            $userModel = $this->userService->getUserInfo($user);

            return ApiResponse::success($userModel,"Información del usuario obtenida exitosamente");
        }catch(\Exception $e){
            return ApiResponse::error("Error al obtener la información del usuario",500, $e->getMessage());
        }
    }

    public function updateUserInfo()
    {
        try{
            $user = request()->user ?? null;
            $newInfo = [
                "username" => request()->input("username"),
                "email"=> request()->input("email"),
                "phone_number"=> request()->input("phone_number"),
            ];

            $updatedUser = $this->userService->updateUserInfo($user, $newInfo);
            $token = JWTUtil::generateToken(['user_id' => $updatedUser->user_id, 'email' => $updatedUser->email, 'role' => $updatedUser->role, 'username' => $updatedUser->username]);

            return ApiResponse::success(["token" => $token, "data" => $updatedUser],"Información del usuario actualizada correctamente");
        }catch(\Exception $e){
            return ApiResponse::error("Error al actualizar información del usuario",500, $e->getMessage());
        }
    }

    public function getUserAddress(){
        try{
            $user = request()->user ?? null;

            $address = $this->userService->getUserAddress($user);
            return ApiResponse::success($address,"Dirección obtenida exitosamente");
        }catch(\Exception $e){
            return ApiResponse::error("Error al obtener la dirección del usuario",500, $e->getMessage());
        }
    }

    public function updateUserAddress(){
        try{
            $user = request()->user ?? null;
            $newAddress = [
                "city" => request()->input("city"),
                "street"=> request()->input("street"),
                "postal_code"=> request()->input("postal_code"),
                "province"=> request()->input("province"),
                "department"=> request()->input("department"),
                "additional_info"=> request()->input("additional_info"),
            ];

            $updatedAddress = $this->userService->updateUserAddress($user, $newAddress);
            return ApiResponse::success($updatedAddress,"Dirección del usuario actualizada correctamente");
        }catch(\Exception $e){
            return ApiResponse::error("Error al actualizar la dirección del usuario",500, $e->getMessage());
        }
    }

    public function updateUserPassword(){
        try{
            $user = request()->user ?? null;
            $newPassword = [
                "current_password"=> request()->input("current_password"),
                "new_password"=> request()->input("new_password"),
                "confirm_password"=> request()->input("confirm_password"),
            ];

            $this->userService->updateUserPassword($user, $newPassword);
        }catch(\Exception $e){
            return ApiResponse::error("Error al cambiar la contraseña",500, $e->getMessage());
        }
    }
}
