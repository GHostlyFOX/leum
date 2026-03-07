<?php

use Modules\Club\Http\Controllers\ClubController;

/*
|--------------------------------------------------------------------------
| Club Web Routes
|--------------------------------------------------------------------------
*/

Route::prefix('club')->middleware('auth')->group(function () {
    Route::get('/',     [ClubController::class, 'index'])->name('club.index');
    Route::get('/add',  [ClubController::class, 'add'])->name('club.add');
    Route::post('/add', [ClubController::class, 'store'])->name('club.store');
    Route::get('/list', [ClubController::class, 'list'])->name('club.list');
});
