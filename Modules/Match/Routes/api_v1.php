<?php

use Illuminate\Support\Facades\Route;
use Modules\Match\Http\Controllers\V1\MatchController;

/*
|--------------------------------------------------------------------------
| Match API Routes — v1
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('matches', MatchController::class)->except(['destroy']);
    Route::post('matches/{id}/start',  [MatchController::class, 'start']);
    Route::post('matches/{id}/end',    [MatchController::class, 'end']);
    Route::post('matches/{id}/events', [MatchController::class, 'addEvent']);
    Route::put('matches/{id}/lineup',  [MatchController::class, 'setLineup']);
});
