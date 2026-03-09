<?php

use App\Http\Livewire\ForgotPassword;
use App\Http\Livewire\Index;
use App\Http\Livewire\Landing;
use App\Http\Livewire\Login;
use App\Http\Livewire\Profile;
use App\Http\Livewire\Register;
use App\Http\Livewire\ClubOnboarding;
use App\Http\Livewire\Onboarding;
use App\Http\Livewire\Settings;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Основные маршруты приложения «Сбор» (SquadUp.ru).
| Маршруты модулей (Auth, Club и др.) подключаются автоматически
| через RouteServiceProvider каждого модуля.
|
*/

// ── Публичная главная страница (landing) ─────────────────────────
Route::get('/', Landing::class)->name('landing');

// ── Авторизация (только для гостей) ──────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('login', Login::class)->name('auth.loginForm');
    Route::get('register', Register::class)->name('auth.register');
    Route::get('forgot-password', ForgotPassword::class);
});

// ── Онбординг (auth, но без проверки onboarded) ─────────────────
Route::middleware('auth')->group(function () {
    Route::get('onboarding', Onboarding::class)->name('onboarding');
    Route::get('onboarding/club', ClubOnboarding::class)->name('club.onboarding');
});

// ── Защищённые страницы (требуется авторизация + онбординг) ──────
Route::middleware(['auth', 'onboarded'])->group(function () {
    Route::get('dashboard', Index::class)->name('home');
    Route::get('profile', Profile::class);
    Route::get('settings', Settings::class);
});
