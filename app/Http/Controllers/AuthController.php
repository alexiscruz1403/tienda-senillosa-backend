<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Http\Responses\ApiResponse;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
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
            return ApiResponse::success(['token' => $token], 'Inicio de sesión exitoso', 200);
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
}
