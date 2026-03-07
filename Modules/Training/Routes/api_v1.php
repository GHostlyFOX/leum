<?php

use Illuminate\Support\Facades\Route;
use Modules\Training\Http\Controllers\V1\TrainingController;

/*
|--------------------------------------------------------------------------
| Training API Routes — v1
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('trainings', TrainingController::class)->except(['destroy']);
    Route::post('trainings/{id}/cancel',                             [TrainingController::class, 'cancel']);
    Route::patch('trainings/{trainingId}/attendance/{playerUserId}', [TrainingController::class, 'markAttendance']);
});
