<?php

use Modules\Auth\Http\Controllers\AuthController;
use Modules\Auth\Http\Controllers\RegisterController;

Route::prefix('auth')->group(function() {
    Route::get('/', [AuthController::class, 'index'])->name('auth.index');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('auth.logout');
    Route::get('/logout', function () {
        return redirect()->route('auth.index');
    });
    Route::get('/forgot-password', [AuthController::class, 'showForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'handleRequest'])->name('password.forgot');
    Route::get('/reset-by-token/{token}', [AuthController::class, 'verifyToken'])->name('password.token');
    Route::get('/reset/sms', [AuthController::class, 'showSmsForm'])->name('password.sms.form');
    Route::post('/reset/sms', [AuthController::class, 'verifySmsCode'])->name('password.sms.verify');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});
// Старые маршруты register2 удалены.
// Регистрация теперь через Livewire-визард: GET /register (routes/web.php)
// Согласие на обработку ПД пока доступно по прямому маршруту:
Route::get('agreement', [RegisterController::class, 'agreement'])->name('personal.data.agreement');
