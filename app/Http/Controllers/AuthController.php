<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Services\CartService;
use App\Http\Responses\ApiResponse;

class AuthController extends Controller
{
    protected $authService;
    protected $cartService;

    public function __construct(AuthService $authService, CartService $cartService)
    {
        $this->authService = $authService;
        $this->cartService = $cartService;
    }

    public function register(Request $request)
    {
        try{
            $userData = $request->all();
            $token = $this->authService->register($userData);
            return ApiResponse::success(['token' => $token], 'Usuario registrado exitosamente', 201);
        }catch(\Exception $e){
            return ApiResponse::error('Error al registrar el usuario', 500, [$e->getMessage()]);
        }
    }

    public function login(Request $request)
    {
        try{
            $credentials = $request->all();
            $token = $this->authService->login($credentials);
            $cartCount = $this->cartService->getCartCount($token['user_id']);
            return ApiResponse::success(['token' => $token, 'cartCount' => $cartCount], 'Inicio de sesión exitoso', 200);
        }catch(\Exception $e){
            return ApiResponse::error('Error al iniciar sesión', 500, [$e->getMessage()]);
        }
    }

    public function redirect()
    {
        return $this->authService->redirect();
    }

    public function callback()
    {
        try{
            return $this->authService->callback();
        }catch(\Exception $e){
            return redirect(
                'http://localhost:5173/auth-error?message=' . urlencode($e->getMessage())
            );
        }
    }

    public function validateToken(Request $request)
    {
        try{
            $token = $request->bearerToken();
            $this->authService->validateToken($token);
            return ApiResponse::success(['valid' => true], 'Token válido', 200);
        }catch(\Exception $e){
            return ApiResponse::error('Token inválido', 401, [$e->getMessage()]);
        }
    }

    public function refreshToken(Request $request)
    {
        try{
            $token = $request->bearerToken();
            $newToken = $this->authService->refreshToken($token);
            return ApiResponse::success(['token' => $newToken], 'Token refrescado exitosamente', 200);
        }catch(\Exception $e){
            return ApiResponse::error('Error al refrescar el token', 500, [$e->getMessage()]);
        }
    }
}
