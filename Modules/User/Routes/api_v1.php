<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\V1\CoachAchievementController;
use Modules\User\Http\Controllers\V1\CoachController;
use Modules\User\Http\Controllers\V1\PlayerController;
use Modules\User\Http\Controllers\V1\UserController;

/*
|--------------------------------------------------------------------------
| User API Routes — v1
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // ── Текущий пользователь ─────────────────────────────────────────
    Route::get('me', [UserController::class, 'me']);

    // ── Пользователи ─────────────────────────────────────────────────
    Route::get('users',      [UserController::class, 'index'])->middleware('permission:users.view');
    Route::get('users/{id}', [UserController::class, 'show'])->middleware('permission:users.view');
    Route::put('users/{id}', [UserController::class, 'update'])->middleware('permission:users.update');

    Route::post('users/{id}/player-profile', [UserController::class, 'createPlayerProfile'])
        ->middleware('permission:users.manage');
    Route::post('users/{id}/coach-profile', [UserController::class, 'createCoachProfile'])
        ->middleware('permission:users.manage');

    // ── Игроки (/players) ────────────────────────────────────────────
    Route::get('players',      [PlayerController::class, 'index'])->middleware('permission:players.view');
    Route::get('players/{id}', [PlayerController::class, 'show'])->middleware('permission:players.view');
    Route::put('players/{id}', [PlayerController::class, 'update'])->middleware('permission:players.update');
    Route::delete('players/{id}', [PlayerController::class, 'destroy'])->middleware('permission:players.delete');

    // ── Тренеры (/coaches) ───────────────────────────────────────────
    Route::get('coaches',      [CoachController::class, 'index'])->middleware('permission:coaches.view');
    Route::get('coaches/{id}', [CoachController::class, 'show'])->middleware('permission:coaches.view');
    Route::put('coaches/{id}', [CoachController::class, 'update'])->middleware('permission:coaches.update');
    Route::delete('coaches/{id}', [CoachController::class, 'destroy'])->middleware('permission:coaches.delete');

    // ── Достижения тренера ───────────────────────────────────────────
    Route::get('coaches/{id}/achievements', [CoachAchievementController::class, 'index'])
        ->middleware('permission:coaches.view');
    Route::post('coaches/{id}/achievements', [CoachAchievementController::class, 'store'])
        ->middleware('permission:coaches.update');
    Route::put('coaches/{id}/achievements/{achievementId}', [CoachAchievementController::class, 'update'])
        ->middleware('permission:coaches.update');
    Route::delete('coaches/{id}/achievements/{achievementId}', [CoachAchievementController::class, 'destroy'])
        ->middleware('permission:coaches.delete');
});
