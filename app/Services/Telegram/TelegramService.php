<?php

declare(strict_types=1);

namespace App\Services\Telegram;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    private string $botToken;
    private string $apiUrl = 'https://api.telegram.org/bot';

    public function __construct()
    {
        $this->botToken = config('services.telegram.bot_token') ?: env('TELEGRAM_BOT_TOKEN', '');
    }

    /**
     * Отправить сообщение
     */
    public function sendMessage(int $chatId, string $text, array $keyboard = []): bool
    {
        if (empty($this->botToken)) {
            Log::warning('Telegram bot token not configured');
            return false;
        }

        try {
            $data = [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'HTML',
            ];

            if (!empty($keyboard)) {
                $data['reply_markup'] = json_encode(['inline_keyboard' => $keyboard]);
            }

            $response = Http::post($this->apiUrl . $this->botToken . '/sendMessage', $data);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Telegram send message error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Установить webhook
     */
    public function setWebhook(string $url): bool
    {
        if (empty($this->botToken)) {
            return false;
        }

        try {
            $response = Http::post($this->apiUrl . $this->botToken . '/setWebhook', [
                'url' => $url,
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Telegram set webhook error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Отправить уведомление о тренировке
     */
    public function sendTrainingReminder(int $chatId, array $data): bool
    {
        $text = "🏃 <b>Напоминание о тренировке</b>\n\n";
        $text .= "📅 <b>Дата:</b> {$data['date']}\n";
        $text .= "⏰ <b>Время:</b> {$data['time']}\n";
        $text .= "👥 <b>Команда:</b> {$data['team']}\n";
        
        if (!empty($data['venue'])) {
            $text .= "📍 <b>Место:</b> {$data['venue']}\n";
        }

        $keyboard = [
            [
                ['text' => '✅ Иду', 'callback_data' => "training_{$data['id']}_confirm"],
                ['text' => '❌ Не иду', 'callback_data' => "training_{$data['id']}_decline"],
            ]
        ];

        return $this->sendMessage($chatId, $text, $keyboard);
    }

    /**
     * Отправить уведомление о матче
     */
    public function sendMatchNotification(int $chatId, array $data): bool
    {
        $text = "⚽ <b>Матч</b>\n\n";
        $text .= "{$data['team']} vs {$data['opponent']}\n\n";
        $text .= "📅 <b>Дата:</b> {$data['date']}\n";
        $text .= "⏰ <b>Время:</b> {$data['time']}\n";
        
        if (!empty($data['venue'])) {
            $text .= "📍 <b>Место:</b> {$data['venue']}\n";
        }

        return $this->sendMessage($chatId, $text);
    }
}
