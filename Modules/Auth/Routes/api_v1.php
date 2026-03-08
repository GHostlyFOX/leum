<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\V1\AuthController;

/*
|--------------------------------------------------------------------------
| Auth API Routes — v1
|--------------------------------------------------------------------------
*/

// Публичные
Route::post('auth/register',       [AuthController::class, 'register']);
Route::post('auth/login',          [AuthController::class, 'login']);
Route::post('auth/refresh',        [AuthController::class, 'refresh']);
Route::post('auth/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('auth/reset-password',  [AuthController::class, 'resetPassword']);

// Защищённые
Route::middleware('auth:sanctum')->group(function () {
    Route::post('auth/logout', [AuthController::class, 'logout']);
});
