<?php

declare(strict_types=1);

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Telegram\TelegramService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\User\Models\User;

class TelegramController extends Controller
{
    public function __construct(
        private TelegramService $telegramService
    ) {}

    /**
     * Генерация кода для привязки Telegram
     */
    public function generateCode(): JsonResponse
    {
        $user = Auth::user();
        
        // Генерируем 6-значный код
        $code = rand(100000, 999999);
        
        // Сохраняем в кэш на 10 минут
        cache()->put('telegram_connect:' . $code, $user->id, 600);
        
        return response()->json([
            'code' => $code,
            'expires_at' => now()->addMinutes(10)->toISOString(),
            'bot_username' => config('services.telegram.bot_username', 'sbor_team_bot'),
        ]);
    }

    /**
     * Привязка Telegram по коду (вызывается из веба)
     */
    public function connectByCode(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|integer|digits:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $code = $request->input('code');
        $userId = cache()->get('telegram_connect:' . $code);

        if (!$userId) {
            return response()->json(['error' => 'Код истек или не найден'], 404);
        }

        if ($userId !== Auth::id()) {
            return response()->json(['error' => 'Неверный код'], 403);
        }

        // Код верный, но chat_id еще не получен
        // Пользователь должен написать боту /connect КОД
        
        return response()->json([
            'message' => 'Код подтвержден. Отправьте боту команду: /connect ' . $code,
            'bot_username' => config('services.telegram.bot_username', 'sbor_team_bot'),
        ]);
    }

    /**
     * Отключение Telegram
     */
    public function disconnect(): JsonResponse
    {
        $user = Auth::user();
        $user->telegram_chat_id = null;
        $user->telegram_id = null;
        $user->telegram_username = null;
        $user->save();

        return response()->json([
            'message' => 'Telegram отключен',
        ]);
    }

    /**
     * Получить статус подключения
     */
    public function status(): JsonResponse
    {
        $user = Auth::user();
        
        return response()->json([
            'connected' => !empty($user->telegram_chat_id),
            'telegram_username' => $user->telegram_username,
            'telegram_id' => $user->telegram_id,
        ]);
    }

    /**
     * Webhook для получения сообщений от Telegram
     */
    public function webhook(Request $request): JsonResponse
    {
        $data = $request->all();

        // Логируем для отладки
        \Log::info('Telegram webhook: ', $data);

        // Обработка команды /start
        if (isset($data['message']['text']) && $data['message']['text'] === '/start') {
            $chatId = $data['message']['chat']['id'];
            $this->telegramService->sendMessage(
                $chatId,
                "👋 <b>Добро пожаловать в Сбор!</b>\n\n" .
                "Для подключения уведомлений:\n" .
                "1. Войдите на сайт sbor.team\n" .
                "2. Перейдите в Профиль → Уведомления\n" .
                "3. Нажмите 'Подключить Telegram'\n" .
                "4. Отправьте боту полученный код\n\n" .
                "Или отправьте: <code>/connect КОД</code>"
            );
        }

        // Обработка команды /connect КОД
        if (isset($data['message']['text']) && str_starts_with($data['message']['text'], '/connect')) {
            $parts = explode(' ', $data['message']['text']);
            $code = $parts[1] ?? null;
            $chatId = $data['message']['chat']['id'];
            
            if ($code) {
                $this->handleConnectCommand($chatId, $code, $data['message']['from']);
            } else {
                $this->telegramService->sendMessage(
                    $chatId,
                    "❌ <b>Ошибка</b>\n\n" .
                    "Укажите код после команды:\n" .
                    "<code>/connect 123456</code>"
                );
            }
        }

        // Обработка команды /schedule
        if (isset($data['message']['text']) && $data['message']['text'] === '/schedule') {
            $chatId = $data['message']['chat']['id'];
            $this->handleScheduleCommand($chatId);
        }

        // Обработка команды /next
        if (isset($data['message']['text']) && $data['message']['text'] === '/next') {
            $chatId = $data['message']['chat']['id'];
            $this->handleNextCommand($chatId);
        }

        return response()->json(['ok' => true]);
    }

    /**
     * Обработка команды /connect
     */
    private function handleConnectCommand(int $chatId, string $code, array $from): void
    {
        $userId = cache()->get('telegram_connect:' . $code);

        if (!$userId) {
            $this->telegramService->sendMessage(
                $chatId,
                "❌ <b>Код истек или не найден</b>\n\n" .
                "Получите новый код на сайте в разделе Профиль → Уведомления"
            );
            return;
        }

        $user = User::find($userId);
        if (!$user) {
            $this->telegramService->sendMessage($chatId, "❌ Пользователь не найден");
            return;
        }

        // Сохраняем данные Telegram
        $user->telegram_chat_id = $chatId;
        $user->telegram_id = $from['id'] ?? null;
        $user->telegram_username = $from['username'] ?? null;
        $user->save();

        // Удаляем код из кэша
        cache()->forget('telegram_connect:' . $code);

        // Отправляем приветствие
        $this->telegramService->sendMessage(
            $chatId,
            "✅ <b>Аккаунт подключен!</b>\n\n" .
            "Привет, {$user->first_name}!\n\n" .
            "Теперь вы будете получать:\n" .
            "• Напоминания о тренировках\n" .
            "• Уведомления о матчах\n" .
            "• Важные объявления\n\n" .
            "<b>Команды:</b>\n" .
            "/schedule - расписание\n" .
            "/next - ближайшее событие"
        );
    }

    /**
     * Обработка команды /schedule
     */
    private function handleScheduleCommand(int $chatId): void
    {
        $user = User::where('telegram_chat_id', $chatId)->first();
        
        if (!$user) {
            $this->telegramService->sendMessage(
                $chatId,
                "❌ Аккаунт не подключен. Отправьте /start для инструкций."
            );
            return;
        }

        $this->telegramService->sendMessage($chatId, "📅 Ищем расписание...");
    }

    /**
     * Обработка команды /next
     */
    private function handleNextCommand(int $chatId): void
    {
        $user = User::where('telegram_chat_id', $chatId)->first();
        
        if (!$user) {
            $this->telegramService->sendMessage(
                $chatId,
                "❌ Аккаунт не подключен."
            );
            return;
        }

        $this->telegramService->sendMessage($chatId, "🔍 Ищем ближайшее событие...");
    }

    /**
     * Установка webhook
     */
    public function setWebhook(): JsonResponse
    {
        $url = route('telegram.webhook');
        $result = $this->telegramService->setWebhook($url);

        return response()->json([
            'success' => $result['success'] ?? false,
            'url' => $url,
            'error' => $result['error'] ?? null,
        ]);
    }
}
