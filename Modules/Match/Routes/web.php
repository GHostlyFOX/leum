<?php

use Modules\Match\Livewire\MatchList;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'onboarded'])->group(function () {
    Route::get('matches', MatchList::class)->name('matches.index');
});
