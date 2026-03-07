<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\V1\UserController;

/*
|--------------------------------------------------------------------------
| User API Routes — v1
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('me',                         [UserController::class, 'me']);
    Route::get('users',                      [UserController::class, 'index']);
    Route::get('users/{id}',                 [UserController::class, 'show']);
    Route::put('users/{id}',                 [UserController::class, 'update']);
    Route::post('users/{id}/player-profile', [UserController::class, 'createPlayerProfile']);
    Route::post('users/{id}/coach-profile',  [UserController::class, 'createCoachProfile']);
});
