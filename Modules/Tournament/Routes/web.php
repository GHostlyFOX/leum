<?php

use Modules\Tournament\Livewire\TournamentList;
use Modules\Tournament\Livewire\TournamentCreate;
use Modules\Tournament\Livewire\TournamentDetail;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'onboarded'])->group(function () {
    Route::get('tournaments', TournamentList::class)->name('tournaments.index');
    Route::get('tournaments/create', TournamentCreate::class)->name('tournament.create');
    Route::get('tournament/{id}', TournamentDetail::class)->name('tournament.detail');
});
