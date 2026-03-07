<?php

use Illuminate\Support\Facades\Route;
use Modules\Training\Http\Controllers\V1\TrainingController;

/*
|--------------------------------------------------------------------------
| Training API Routes — v1
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // Просмотр
    Route::get('trainings',      [TrainingController::class, 'index'])->middleware('permission:trainings.view');
    Route::get('trainings/{id}', [TrainingController::class, 'show'])->middleware('permission:trainings.view');

    // Создание / редактирование — coach+
    Route::post('trainings',      [TrainingController::class, 'store'])->middleware('permission:trainings.create');
    Route::put('trainings/{id}',  [TrainingController::class, 'update'])->middleware('permission:trainings.update');

    // Отмена
    Route::post('trainings/{id}/cancel', [TrainingController::class, 'cancel'])
        ->middleware('permission:trainings.cancel');

    // Посещаемость
    Route::patch('trainings/{trainingId}/attendance/{playerUserId}', [TrainingController::class, 'markAttendance'])
        ->middleware('permission:trainings.attendance');
});
