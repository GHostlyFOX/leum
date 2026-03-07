<?php

use Illuminate\Support\Facades\Route;
use Modules\Team\Http\Controllers\V1\TeamController;

/*
|--------------------------------------------------------------------------
| Team API Routes — v1
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // Просмотр
    Route::get('clubs/{clubId}/teams', [TeamController::class, 'index'])->middleware('permission:teams.view');
    Route::get('teams/{id}',          [TeamController::class, 'show'])->middleware('permission:teams.view');

    // Создание / редактирование / удаление
    Route::post('clubs/{clubId}/teams', [TeamController::class, 'store'])->middleware('permission:teams.create');
    Route::put('teams/{id}',            [TeamController::class, 'update'])->middleware('permission:teams.update');
    Route::delete('teams/{id}',         [TeamController::class, 'destroy'])->middleware('permission:teams.delete');

    // Управление составом
    Route::post('teams/{teamId}/members', [TeamController::class, 'addMember'])
        ->middleware('permission:teams.members');
});
