<?php

use App\Services\Telegram\TelegramService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Telegram webhook command
Artisan::command('telegram:set-webhook {--url= : URL для webhook}', function ($url) {
    $url = $url ?: config('app.url') . '/telegram/webhook';
    
    $this->info("Установка webhook: {$url}");
    
    $telegramService = new TelegramService();
    $result = $telegramService->setWebhook($url);
    
    if ($result) {
        $this->info('✅ Webhook успешно установлен!');
        $this->info("Telegram будет отправлять обновления на: {$url}");
    } else {
        $this->error('❌ Ошибка установки webhook');
        $this->error('Проверьте:');
        $this->error('1. TELEGRAM_BOT_TOKEN в .env');
        $this->error('2. Доступность URL по HTTPS');
        $this->error('3. Что бот создан в @BotFather');
    }
})->purpose('Установка webhook для Telegram бота');
