<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\V1\UserController;

/*
|--------------------------------------------------------------------------
| User API Routes — v1
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // Текущий пользователь — доступен всем аутентифицированным
    Route::get('me', [UserController::class, 'me']);

    // Просмотр списка / профиля пользователей
    Route::get('users',      [UserController::class, 'index'])->middleware('permission:users.view');
    Route::get('users/{id}', [UserController::class, 'show'])->middleware('permission:users.view');

    // Редактирование
    Route::put('users/{id}', [UserController::class, 'update'])->middleware('permission:users.update');

    // Создание профилей — управление пользователями
    Route::post('users/{id}/player-profile', [UserController::class, 'createPlayerProfile'])
        ->middleware('permission:users.manage');
    Route::post('users/{id}/coach-profile', [UserController::class, 'createCoachProfile'])
        ->middleware('permission:users.manage');
});
