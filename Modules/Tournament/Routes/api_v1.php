<?php

use Illuminate\Support\Facades\Route;
use Modules\Tournament\Http\Controllers\V1\TournamentController;

/*
|--------------------------------------------------------------------------
| Tournament API Routes — v1
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tournaments', TournamentController::class)->except(['destroy']);
    Route::post('tournaments/{id}/teams', [TournamentController::class, 'registerTeam']);
});
