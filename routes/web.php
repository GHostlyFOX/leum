<?php

use App\Http\Livewire\Index;
use App\Http\Livewire\Login;
use App\Http\Livewire\Register;
use App\Http\Livewire\ForgotPassword;
use App\Http\Livewire\Profile;
use App\Http\Livewire\Settings;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Основные маршруты приложения «Детская лига».
| Маршруты модулей (Auth, Club и др.) подключаются автоматически
| через RouteServiceProvider каждого модуля.
|
*/

// Главная страница (дашборд)
Route::get('/', Index::class)->name('home');

// Авторизация (Livewire-страницы — используются как fallback,
// основные маршруты в Modules/Auth/Routes/web.php)
Route::get('login', Login::class)->name('auth.loginForm');
Route::get('register', Register::class);
Route::get('forgot-password', ForgotPassword::class);

// Личный кабинет
Route::middleware('auth')->group(function () {
    Route::get('profile', Profile::class);
    Route::get('settings', Settings::class);
});
