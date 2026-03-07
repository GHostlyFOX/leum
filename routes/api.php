<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ClubController;
use App\Http\Controllers\Api\V1\MatchController;
use App\Http\Controllers\Api\V1\ReferenceController;
use App\Http\Controllers\Api\V1\TeamController;
use App\Http\Controllers\Api\V1\TournamentController;
use App\Http\Controllers\Api\V1\TrainingController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — v1
|--------------------------------------------------------------------------
|
| Префикс /api/v1 задаётся ниже через Route::prefix.
| Авторизация: Laravel Sanctum (Bearer Token).
|
*/

Route::prefix('v1')->group(function () {

    // ── Аутентификация (публичные) ───────────────────────────────────────
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login',    [AuthController::class, 'login']);
    });

    // ── Справочники (публичные) ──────────────────────────────────────────
    Route::prefix('refs')->group(function () {
        Route::get('sport-types',       [ReferenceController::class, 'sportTypes']);
        Route::get('club-types',        [ReferenceController::class, 'clubTypes']);
        Route::get('user-roles',        [ReferenceController::class, 'userRoles']);
        Route::get('positions',         [ReferenceController::class, 'positions']);
        Route::get('dominant-feet',     [ReferenceController::class, 'dominantFeet']);
        Route::get('kinship-types',     [ReferenceController::class, 'kinshipTypes']);
        Route::get('match-event-types', [ReferenceController::class, 'matchEventTypes']);
        Route::get('countries',         [ReferenceController::class, 'countries']);
        Route::get('cities',            [ReferenceController::class, 'cities']);
    });

    // ── Защищённые маршруты (Sanctum) ────────────────────────────────────
    Route::middleware('auth:sanctum')->group(function () {

        // Выход
        Route::post('auth/logout', [AuthController::class, 'logout']);

        // Текущий пользователь
        Route::get('me', [UserController::class, 'me']);

        // Пользователи
        Route::get('users',                       [UserController::class, 'index']);
        Route::get('users/{id}',                  [UserController::class, 'show']);
        Route::put('users/{id}',                  [UserController::class, 'update']);
        Route::post('users/{id}/player-profile',  [UserController::class, 'createPlayerProfile']);
        Route::post('users/{id}/coach-profile',   [UserController::class, 'createCoachProfile']);

        // Клубы
        Route::apiResource('clubs', ClubController::class);

        // Команды (вложены в клубы для создания / списка)
        Route::get('clubs/{clubId}/teams',    [TeamController::class, 'index']);
        Route::post('clubs/{clubId}/teams',   [TeamController::class, 'store']);
        Route::get('teams/{id}',              [TeamController::class, 'show']);
        Route::put('teams/{id}',              [TeamController::class, 'update']);
        Route::delete('teams/{id}',           [TeamController::class, 'destroy']);
        Route::post('teams/{teamId}/members', [TeamController::class, 'addMember']);

        // Тренировки
        Route::apiResource('trainings', TrainingController::class)->except(['destroy']);
        Route::post('trainings/{id}/cancel',                              [TrainingController::class, 'cancel']);
        Route::patch('trainings/{trainingId}/attendance/{playerUserId}',  [TrainingController::class, 'markAttendance']);

        // Матчи
        Route::apiResource('matches', MatchController::class)->except(['destroy']);
        Route::post('matches/{id}/start',   [MatchController::class, 'start']);
        Route::post('matches/{id}/end',     [MatchController::class, 'end']);
        Route::post('matches/{id}/events',  [MatchController::class, 'addEvent']);
        Route::put('matches/{id}/lineup',   [MatchController::class, 'setLineup']);

        // Турниры
        Route::apiResource('tournaments', TournamentController::class)->except(['destroy']);
        Route::post('tournaments/{id}/teams', [TournamentController::class, 'registerTeam']);
    });
});
