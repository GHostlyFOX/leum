<?php

use Illuminate\Support\Facades\Route;
use Modules\Training\Http\Controllers\V1\AnnouncementController;
use Modules\Training\Http\Controllers\V1\EventResponseController;
use Modules\Training\Http\Controllers\V1\RecurringTrainingController;
use Modules\Training\Http\Controllers\V1\TrainingController;
use Modules\Training\Http\Controllers\V1\VenueController;

/*
|--------------------------------------------------------------------------
| Training API Routes — v1
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // ═══════════════════════════════════════════════════════════════════════════
    // Trainings
    // ═══════════════════════════════════════════════════════════════════════════

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

    // ═══════════════════════════════════════════════════════════════════════════
    // Venues
    // ═══════════════════════════════════════════════════════════════════════════

    Route::get('venues', [VenueController::class, 'index'])->middleware('permission:venues.view');
    Route::post('venues', [VenueController::class, 'store'])->middleware('permission:venues.create');
    Route::get('venues/{id}', [VenueController::class, 'show'])->middleware('permission:venues.view');
    Route::put('venues/{id}', [VenueController::class, 'update'])->middleware('permission:venues.update');
    Route::delete('venues/{id}', [VenueController::class, 'destroy'])->middleware('permission:venues.delete');

    // ═══════════════════════════════════════════════════════════════════════════
    // Announcements
    // ═══════════════════════════════════════════════════════════════════════════

    Route::get('announcements', [AnnouncementController::class, 'index'])->middleware('permission:announcements.view');
    Route::post('announcements', [AnnouncementController::class, 'store'])->middleware('permission:announcements.create');
    Route::get('announcements/{id}', [AnnouncementController::class, 'show'])->middleware('permission:announcements.view');
    Route::put('announcements/{id}', [AnnouncementController::class, 'update'])->middleware('permission:announcements.update');
    Route::delete('announcements/{id}', [AnnouncementController::class, 'destroy'])->middleware('permission:announcements.delete');
    Route::post('announcements/{id}/publish', [AnnouncementController::class, 'publish'])
        ->middleware('permission:announcements.publish');

    // ═══════════════════════════════════════════════════════════════════════════
    // Event Responses (RSVP)
    // ═══════════════════════════════════════════════════════════════════════════

    // Отклики на события
    Route::get('events/{eventType}/{eventId}/responses', [EventResponseController::class, 'index'])
        ->middleware('permission:event-responses.view');
    Route::post('events/{eventType}/{eventId}/responses', [EventResponseController::class, 'store'])
        ->middleware('permission:event-responses.create');
    Route::get('events/{eventType}/{eventId}/my-response', [EventResponseController::class, 'myResponse'])
        ->middleware('permission:event-responses.view');
    Route::put('events/{eventType}/{eventId}/responses/{userId}', [EventResponseController::class, 'update'])
        ->middleware('permission:event-responses.manage');
    Route::delete('events/{eventType}/{eventId}/responses/{userId}', [EventResponseController::class, 'destroy'])
        ->middleware('permission:event-responses.manage');

    // Массовое обновление
    Route::post('events/bulk/responses', [EventResponseController::class, 'bulkStore'])
        ->middleware('permission:event-responses.create');

    // Предстоящие события пользователя
    Route::get('users/{userId}/events/upcoming', [EventResponseController::class, 'upcoming'])
        ->middleware('permission:event-responses.view');

    // ═══════════════════════════════════════════════════════════════════════════
    // Recurring Trainings
    // ═══════════════════════════════════════════════════════════════════════════

    Route::get('recurring-trainings', [RecurringTrainingController::class, 'index'])
        ->middleware('permission:recurring-trainings.view');
    Route::post('recurring-trainings', [RecurringTrainingController::class, 'store'])
        ->middleware('permission:recurring-trainings.create');
    Route::get('recurring-trainings/{id}', [RecurringTrainingController::class, 'show'])
        ->middleware('permission:recurring-trainings.view');
    Route::put('recurring-trainings/{id}', [RecurringTrainingController::class, 'update'])
        ->middleware('permission:recurring-trainings.update');
    Route::delete('recurring-trainings/{id}', [RecurringTrainingController::class, 'destroy'])
        ->middleware('permission:recurring-trainings.delete');
    Route::post('recurring-trainings/{id}/generate', [RecurringTrainingController::class, 'generate'])
        ->middleware('permission:recurring-trainings.manage');
});
