<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Utils\JWTUtil;

class OptionalMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authToken = $request->header('Authorization');
        if ($authToken && preg_match('/^Bearer\s+(.+)$/', $authToken, $matches)) {
            $token = $matches[1];
            try {
                $decoded = JWTUtil::validateToken($token);

                $user = User::find($decoded['user_id']);
                if ($user) {
                    $request->merge(['user' => $user]);
                    return $next($request);
                }
            } catch (\Exception $e) {
                return $next($request);
            }
        }
        return $next($request);
    }
}
