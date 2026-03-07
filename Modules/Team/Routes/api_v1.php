<?php

use Illuminate\Support\Facades\Route;
use Modules\Team\Http\Controllers\V1\TeamController;

/*
|--------------------------------------------------------------------------
| Team API Routes — v1
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    // Вложенные в клубы
    Route::get('clubs/{clubId}/teams',    [TeamController::class, 'index']);
    Route::post('clubs/{clubId}/teams',   [TeamController::class, 'store']);

    // Самостоятельные
    Route::get('teams/{id}',              [TeamController::class, 'show']);
    Route::put('teams/{id}',              [TeamController::class, 'update']);
    Route::delete('teams/{id}',           [TeamController::class, 'destroy']);
    Route::post('teams/{teamId}/members', [TeamController::class, 'addMember']);
});
