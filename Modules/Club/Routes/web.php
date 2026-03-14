<?php

use App\Livewire\Seasons;
use App\Livewire\TeamManagement;
use App\Livewire\ClubStaff;
use Modules\Club\Http\Controllers\ClubController;

/*
|--------------------------------------------------------------------------
| Club Web Routes
|--------------------------------------------------------------------------
*/

Route::prefix('club')->middleware(['auth', 'onboarded'])->group(function () {
    Route::get('/',        [ClubController::class, 'index'])->name('club.index');
    Route::get('/add',     [ClubController::class, 'add'])->name('club.add');
    Route::post('/add',    [ClubController::class, 'store'])->name('club.store');
    Route::get('/list',    [ClubController::class, 'list'])->name('club.list');

    // Сезоны (Livewire)
    Route::get('/seasons', Seasons::class)->name('club.seasons');
    
    // Команды (Livewire)
    Route::get('/teams',           TeamManagement::class)->name('club.teams');
    Route::get('/team/{id}',       [ClubController::class, 'teamShow'])->name('club.team.show');
    Route::get('/team/{id}/edit',  [ClubController::class, 'teamEdit'])->name('club.team.edit');
    Route::put('/team/{id}',       [ClubController::class, 'teamUpdate'])->name('club.team.update');
    Route::delete('/team/{id}',    [ClubController::class, 'teamDelete'])->name('club.team.delete');
    
    // Сотрудники
    Route::get('/staff',           ClubStaff::class)->name('club.staff');
    
    // Приглашения
    Route::get('/invites',         \App\Livewire\InviteManagement::class)->name('club.invites');
});
