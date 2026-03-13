<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\Telegram\TelegramService;
use Illuminate\Console\Command;

class TelegramSetWebhook extends Command
{
    protected $signature = 'telegram:set-webhook {--url= : URL для webhook (по умолчанию из config)}';
    protected $description = 'Установка webhook для Telegram бота';

    public function handle()
    {
        $url = $this->option('url') ?: config('app.url') . '/telegram/webhook';
        
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
        
        return $result ? 0 : 1;
    }
}
