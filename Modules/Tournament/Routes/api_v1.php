<?php

use Illuminate\Support\Facades\Route;
use Modules\Tournament\Http\Controllers\V1\TournamentController;

/*
|--------------------------------------------------------------------------
| Tournament API Routes — v1
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // Просмотр
    Route::get('tournaments',      [TournamentController::class, 'index'])->middleware('permission:tournaments.view');
    Route::get('tournaments/{id}', [TournamentController::class, 'show'])->middleware('permission:tournaments.view');

    // Создание / редактирование — admin
    Route::post('tournaments',     [TournamentController::class, 'store'])->middleware('permission:tournaments.create');
    Route::put('tournaments/{id}', [TournamentController::class, 'update'])->middleware('permission:tournaments.update');

    // Регистрация команды на турнир — coach+
    Route::post('tournaments/{id}/teams', [TournamentController::class, 'registerTeam'])
        ->middleware('permission:tournaments.register');
});
