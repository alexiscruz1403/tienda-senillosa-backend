<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Utils\JWTUtil;
use App\Http\Responses\ApiResponse;
use App\Models\User;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authToken = $request->header('Authorization');
        if (!$authToken || !preg_match('/^Bearer\s+(.+)$/', $authToken, $matches)) {
            return ApiResponse::error('Usuario no autenticado', 401, ['Token no proporcionado o inválido']);
        }

        $token = $matches[1];
        try {
            $decoded = JWTUtil::validateToken($token);

            $user = User::find($decoded['user_id']);
            if (!$user) {
                return ApiResponse::error('Usuario no autenticado', 401, ['Usuario no encontrado']);
            }

            $request->merge(['user' => $user]);
            return $next($request);
        } catch (\Exception $e) {
            return ApiResponse::error('Usuario no autenticado', 401, [$e->getMessage()]);
        }
    }
}
