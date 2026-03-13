<?php

use Illuminate\Support\Facades\Route;
use Modules\Team\Http\Controllers\V1\ImportExportController;
use Modules\Team\Http\Controllers\V1\InviteController;
use Modules\Team\Http\Controllers\V1\PdfController;
use Modules\Team\Http\Controllers\V1\SeasonController;
use Modules\Team\Http\Controllers\V1\TeamController;

/*
|--------------------------------------------------------------------------
| Team API Routes — v1
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // ═══════════════════════════════════════════════════════════════════════════
    // Teams
    // ═══════════════════════════════════════════════════════════════════════════

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

    // ═══════════════════════════════════════════════════════════════════════════
    // Import / Export
    // ═══════════════════════════════════════════════════════════════════════════

    Route::post('teams/{teamId}/players/import', [ImportExportController::class, 'importPlayers'])
        ->middleware('permission:teams.update');
    Route::get('teams/players/import/template', [ImportExportController::class, 'downloadTemplate'])
        ->middleware('permission:teams.view');
    Route::get('teams/{teamId}/players/export', [ImportExportController::class, 'exportPlayers'])
        ->middleware('permission:teams.view');
    Route::get('teams/{teamId}/attendance/export', [ImportExportController::class, 'exportAttendance'])
        ->middleware('permission:teams.view');

    // ═══════════════════════════════════════════════════════════════════════════
    // PDF Documents
    // ═══════════════════════════════════════════════════════════════════════════

    Route::get('teams/{teamId}/roster.pdf', [PdfController::class, 'teamRoster'])
        ->middleware('permission:teams.view');
    Route::get('tournaments/{tournamentId}/teams/{teamId}/application.pdf', [PdfController::class, 'tournamentApplication'])
        ->middleware('permission:teams.view');

    // ═══════════════════════════════════════════════════════════════════════════
    // Seasons
    // ═══════════════════════════════════════════════════════════════════════════

    Route::get('seasons', [SeasonController::class, 'index'])->middleware('permission:seasons.view');
    Route::post('seasons', [SeasonController::class, 'store'])->middleware('permission:seasons.create');
    Route::get('seasons/{id}', [SeasonController::class, 'show'])->middleware('permission:seasons.view');
    Route::put('seasons/{id}', [SeasonController::class, 'update'])->middleware('permission:seasons.update');
    Route::delete('seasons/{id}', [SeasonController::class, 'destroy'])->middleware('permission:seasons.delete');

    // Управление командами в сезоне
    Route::post('seasons/{id}/teams', [SeasonController::class, 'attachTeam'])
        ->middleware('permission:seasons.update');
    Route::delete('seasons/{id}/teams', [SeasonController::class, 'detachTeam'])
        ->middleware('permission:seasons.update');

    // ═══════════════════════════════════════════════════════════════════════════
    // Invite Links
    // ═══════════════════════════════════════════════════════════════════════════

    Route::get('invite-links', [InviteController::class, 'index'])->middleware('permission:invites.view');
    Route::post('invite-links', [InviteController::class, 'store'])->middleware('permission:invites.create');
    Route::get('invite-links/{id}', [InviteController::class, 'show'])->middleware('permission:invites.view');
    Route::delete('invite-links/{id}', [InviteController::class, 'destroy'])->middleware('permission:invites.delete');
});

// Публичные endpoints для инвайтов
Route::get('invite-links/{token}/validate', [InviteController::class, 'validateToken']);
Route::post('invite-links/{token}/accept', [InviteController::class, 'accept'])
    ->middleware('auth:sanctum');
