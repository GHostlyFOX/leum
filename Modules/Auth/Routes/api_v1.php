<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\TelegramController;
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
    
    // Telegram
    Route::get('telegram/status', [TelegramController::class, 'status']);
    Route::post('telegram/generate-code', [TelegramController::class, 'generateCode']);
    Route::post('telegram/connect', [TelegramController::class, 'connectByCode']);
    Route::post('telegram/disconnect', [TelegramController::class, 'disconnect']);
});

// Публичный webhook
Route::post('telegram/webhook', [TelegramController::class, 'webhook'])
    ->name('telegram.webhook');
