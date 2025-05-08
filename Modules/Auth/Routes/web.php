<?php

Route::prefix('auth')->group(function() {
    Route::get('/', 'AuthController@index')->name('login');
    Route::get('/forgot-password', 'AuthController@showForm')->name('password.request');
    Route::post('/forgot-password', 'AuthController@handleRequest')->name('password.forgot');
    Route::get('/reset-by-token/{token}', 'AuthController@verifyToken')->name('password.token');
    Route::get('/reset/sms', 'AuthController@showSmsForm')->name('password.sms.form');
    Route::post('/reset/sms', 'AuthController@verifySmsCode')->name('password.sms.verify');
    Route::get('/reset-password/{token}', 'AuthController@showResetForm')->name('password.reset');
    Route::post('/reset-password', 'AuthController@resetPassword')->name('password.update');
});
Route::prefix('register2')->group(function() {
    Route::get('/', 'RegisterController@index');
    Route::get('/agreement', 'RegisterController@agreement')->name('personal.data.agreement');
    Route::get('/register', 'RegisterController@register')->name('register');
});
