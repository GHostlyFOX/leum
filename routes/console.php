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
    
    if ($result['success']) {
        $this->info('✅ Webhook успешно установлен!');
        $this->info("Telegram будет отправлять обновления на: {$url}");
        if (isset($result['result']) && $result['result'] !== true) {
            $this->info('Ответ: ' . json_encode($result['result']));
        }
    } else {
        $this->error('❌ Ошибка установки webhook');
        $this->error('Ошибка: ' . ($result['error'] ?? 'Unknown'));
        $this->error('');
        $this->error('Проверьте:');
        $this->error('1. Корректность токена (возьмите новый у @BotFather)');
        $this->error('2. Доступность URL по HTTPS (откройте ' . $url . ' в браузере)');
        $this->error('3. Что бот не удален в @BotFather');
    }
})->purpose('Установка webhook для Telegram бота');

// Telegram bot info command
Artisan::command('telegram:info', function () {
    $token = config('services.telegram.bot_token') ?: env('TELEGRAM_BOT_TOKEN');
    
    if (empty($token)) {
        $this->error('❌ TELEGRAM_BOT_TOKEN не найден!');
        return 1;
    }
    
    $this->info('Токен: ' . substr($token, 0, 10) . '...');
    $this->info('Проверка бота...');
    
    try {
        $response = \Illuminate\Support\Facades\Http::get('https://api.telegram.org/bot' . $token . '/getMe');
        $data = $response->json();
        
        if ($data['ok'] ?? false) {
            $bot = $data['result'];
            $this->info('✅ Бот найден!');
            $this->info('Имя: ' . ($bot['first_name'] ?? 'Unknown'));
            $this->info('Username: @' . ($bot['username'] ?? 'Unknown'));
            $this->info('ID: ' . ($bot['id'] ?? 'Unknown'));
            
            // Проверяем webhook
            $this->info('');
            $this->info('Проверка webhook...');
            $whResponse = \Illuminate\Support\Facades\Http::get('https://api.telegram.org/bot' . $token . '/getWebhookInfo');
            $whData = $whResponse->json();
            
            if ($whData['ok'] ?? false) {
                $info = $whData['result'];
                if (!empty($info['url'])) {
                    $this->info('✅ Webhook установлен: ' . $info['url']);
                } else {
                    $this->warn('⚠️ Webhook не установлен');
                }
            }
        } else {
            $this->error('❌ Ошибка: ' . ($data['description'] ?? 'Unknown error'));
        }
    } catch (\Exception $e) {
        $this->error('❌ Ошибка запроса: ' . $e->getMessage());
    }
})->purpose('Проверка информации о Telegram боте');
