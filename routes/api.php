<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\OptionalMiddleware;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::get('google/redirect', [AuthController::class, 'redirect']);
    Route::get('google/callback', [AuthController::class, 'callback']);
});

Route::prefix('products')->group(function () {
    Route::get('/featured', [ProductController::class, 'getFeaturedProducts'])->middleware(OptionalMiddleware::class);
    Route::post('/{productId}/like', [ProductController::class, 'likeProduct'])->middleware(AuthMiddleware::class);
});
