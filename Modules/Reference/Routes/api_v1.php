<?php

use Illuminate\Support\Facades\Route;
use Modules\Reference\Http\Controllers\V1\ReferenceController;

/*
|--------------------------------------------------------------------------
| Reference API Routes — v1
|--------------------------------------------------------------------------
| Публичные эндпоинты справочников (без аутентификации)
*/

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
