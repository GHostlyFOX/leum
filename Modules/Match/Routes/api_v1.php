<?php

use Illuminate\Support\Facades\Route;
use Modules\Match\Http\Controllers\V1\MatchController;

/*
|--------------------------------------------------------------------------
| Match API Routes — v1
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // Просмотр
    Route::get('matches',      [MatchController::class, 'index'])->middleware('permission:matches.view');
    Route::get('matches/{id}', [MatchController::class, 'show'])->middleware('permission:matches.view');

    // Создание / редактирование — coach+
    Route::post('matches',     [MatchController::class, 'store'])->middleware('permission:matches.create');
    Route::put('matches/{id}', [MatchController::class, 'update'])->middleware('permission:matches.update');

    // Управление матчем (старт / завершение / события / состав)
    Route::post('matches/{id}/start',  [MatchController::class, 'start'])->middleware('permission:matches.manage');
    Route::post('matches/{id}/end',    [MatchController::class, 'end'])->middleware('permission:matches.manage');
    Route::post('matches/{id}/events', [MatchController::class, 'addEvent'])->middleware('permission:matches.manage');
    Route::put('matches/{id}/lineup',  [MatchController::class, 'setLineup'])->middleware('permission:matches.manage');
});
