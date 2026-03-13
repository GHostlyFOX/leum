<?php

use App\Livewire\ForgotPassword;
use App\Livewire\Dashboard;
use App\Livewire\Landing;
use App\Livewire\Login;
use App\Livewire\Profile;
use App\Livewire\Register;
use App\Livewire\ClubOnboarding;
use App\Livewire\JoinTeam;
use App\Livewire\Onboarding;
use App\Livewire\Settings;
use App\Livewire\JoinRequests;
use App\Livewire\ClubSearch;
use App\Livewire\ActivityLog;
use App\Livewire\CoachAchievements;
use App\Livewire\MatchDetail;
use App\Livewire\ParentDashboard;
use App\Livewire\PlayerImport;
use App\Livewire\TeamCalendar;
use App\Livewire\TrainingDetail;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Основные маршруты приложения «Сбор» (sbor.team).
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

// ── Публичная страница приглашения ────────────────────────────────
Route::get('join/{token}', JoinTeam::class)->name('join.team');

// ── Онбординг (auth, но без проверки onboarded) ─────────────────
Route::middleware('auth')->group(function () {
    Route::get('onboarding', Onboarding::class)->name('onboarding');
    Route::get('onboarding/club', ClubOnboarding::class)->name('club.onboarding');
    Route::get('onboarding/search', ClubSearch::class)->name('club.search');
});

// ── Защищённые страницы (требуется авторизация + онбординг) ──────
Route::middleware(['auth', 'onboarded'])->group(function () {
    Route::get('dashboard', Dashboard::class)->name('home');
    Route::get('profile', Profile::class);
    Route::get('settings', Settings::class);
    Route::get('join-requests', JoinRequests::class)->name('join.requests');
    Route::get('players/import', PlayerImport::class)->name('players.import');
    Route::get('parent/dashboard', ParentDashboard::class)->name('parent.dashboard');
    Route::get('coach/{coachId}/achievements', CoachAchievements::class)->name('coach.achievements');
    Route::get('team/calendar', TeamCalendar::class)->name('team.calendar');
    Route::get('training/{id}', TrainingDetail::class)->name('training.detail');
    Route::get('match/{id}', MatchDetail::class)->name('match.detail');
    Route::get('admin/activity-log', ActivityLog::class)->name('admin.activity-log');
});

// Telegram webhook (публичный)
Route::post('/telegram/webhook', [\Modules\Auth\Http\Controllers\TelegramController::class, 'webhook'])
    ->name('telegram.webhook');
