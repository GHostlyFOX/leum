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

Route::prefix('club')->middleware('auth')->group(function() {
    Route::get('/', 'ClubController@index')->name('home');
    Route::get('list', 'ClubController@list')->name('club-list');
    Route::get('add', 'ClubController@add')->name('club-add');
    Route::post('save', 'ClubController@saveClub')->name('club-save');
    Route::get('team/list', 'ClubController@teamList')->name('team-list');
    Route::get('team/add', 'ClubController@teamAdd')->name('team-add');
    Route::get('staff', 'ClubController@index')->name('stuff');
    Route::get('refs', 'ClubController@index')->name('refs');
});
