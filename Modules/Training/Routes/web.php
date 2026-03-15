<?php

use Modules\Training\Livewire\TrainingList;
use Modules\Training\Livewire\TrainingCalendar;
use Modules\Training\Livewire\RecurringTrainings;
use Modules\Training\Livewire\VenueList;
use Modules\Training\Livewire\AnnouncementCreate;

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'onboarded'])->group(function () {
    Route::get('trainings', TrainingList::class)->name('trainings.index');
    Route::get('trainings/calendar', TrainingCalendar::class)->name('trainings.calendar');
    Route::get('trainings/recurring', RecurringTrainings::class)->name('trainings.recurring');
    Route::get('venues', VenueList::class)->name('venues.index');
    Route::get('announcements/create', AnnouncementCreate::class)->name('announcement.create');
});
