<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::get('google/redirect', [AuthController::class, 'redirect']);
    Route::get('google/callback', [AuthController::class, 'callback']);
});
