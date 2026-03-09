<?php

Route::prefix('auth')->group(function() {
    Route::get('/', 'AuthController@index')->name('auth.index');
    Route::post('/login', 'AuthController@login')->name('auth.login');
    Route::post('/logout', 'AuthController@logout')->middleware('auth')->name('auth.logout');
    Route::get('/logout', function () {
        return redirect()->route('auth.index');
    });
    Route::get('/forgot-password', 'AuthController@showForm')->name('password.request');
    Route::post('/forgot-password', 'AuthController@handleRequest')->name('password.forgot');
    Route::get('/reset-by-token/{token}', 'AuthController@verifyToken')->name('password.token');
    Route::get('/reset/sms', 'AuthController@showSmsForm')->name('password.sms.form');
    Route::post('/reset/sms', 'AuthController@verifySmsCode')->name('password.sms.verify');
    Route::get('/reset-password/{token}', 'AuthController@showResetForm')->name('password.reset');
    Route::post('/reset-password', 'AuthController@resetPassword')->name('password.update');
});
// Старые маршруты register2 удалены.
// Регистрация теперь через Livewire-визард: GET /register (routes/web.php)
// Согласие на обработку ПД пока доступно по прямому маршруту:
Route::get('agreement', 'RegisterController@agreement')->name('personal.data.agreement');
