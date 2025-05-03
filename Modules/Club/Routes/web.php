<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('club')->group(function() {
    Route::get('/', 'ClubController@index');
    Route::get('team/list', 'ClubController@teamList')->name('team-list');
    Route::get('staff', 'ClubController@index')->name('stuff');
    Route::get('refs', 'ClubController@index')->name('refs');
});
