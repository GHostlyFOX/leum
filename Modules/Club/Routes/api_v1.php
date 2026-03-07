<?php

use Illuminate\Support\Facades\Route;
use Modules\Club\Http\Controllers\V1\ClubController;

/*
|--------------------------------------------------------------------------
| Club API Routes — v1
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // Просмотр — все аутентифицированные пользователи с разрешением
    Route::get('clubs',      [ClubController::class, 'index'])->middleware('permission:clubs.view');
    Route::get('clubs/{id}', [ClubController::class, 'show'])->middleware('permission:clubs.view');

    // Создание / редактирование / удаление — admin + обладатели разрешений
    Route::post('clubs',        [ClubController::class, 'store'])->middleware('permission:clubs.create');
    Route::put('clubs/{id}',    [ClubController::class, 'update'])->middleware('permission:clubs.update');
    Route::delete('clubs/{id}', [ClubController::class, 'destroy'])->middleware('permission:clubs.delete');
});
