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
    
    // Проверка токена
    $token = config('services.telegram.bot_token') ?: env('TELEGRAM_BOT_TOKEN');
    if (empty($token)) {
        $this->error('❌ TELEGRAM_BOT_TOKEN не найден!');
        $this->info('Проверьте:');
        $this->info('1. Файл .env содержит TELEGRAM_BOT_TOKEN=ваш_токен');
        $this->info('2. Конфиг кэша очищен: php artisan config:clear');
        return 1;
    }
    
    $this->info('Токен найден: ' . substr($token, 0, 10) . '...');
    
    $telegramService = new TelegramService();
    $result = $telegramService->setWebhook($url);
    
    if ($result) {
        $this->info('✅ Webhook успешно установлен!');
        $this->info("Telegram будет отправлять обновления на: {$url}");
    } else {
        $this->error('❌ Ошибка установки webhook');
        $this->error('Проверьте:');
        $this->error('1. Корректность токена (возьмите новый у @BotFather)');
        $this->error('2. Доступность URL по HTTPS (откройте ' . $url . ' в браузере)');
        $this->error('3. Что бот не удален в @BotFather');
    }
})->purpose('Установка webhook для Telegram бота');
