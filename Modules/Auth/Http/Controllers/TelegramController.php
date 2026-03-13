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
     * Привязка Telegram к аккаунту
     */
    public function connect(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'telegram_id' => 'required|integer|unique:users,telegram_id',
            'username' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $user->telegram_id = $request->input('telegram_id');
        $user->telegram_username = $request->input('username');
        $user->save();

        // Отправляем приветственное сообщение
        $this->telegramService->sendMessage(
            $request->input('telegram_id'),
            "👋 <b>Привет, {$user->first_name}!</b>\n\n" .
            "Вы успешно подключили уведомления от платформы <b>Сбор</b>.\n\n" .
            "Теперь вы будете получать:\n" .
            "• Напоминания о тренировках\n" .
            "• Уведомления о матчах\n" .
            "• Важные объявления\n\n" .
            "Доступные команды:\n" .
            "/schedule - расписание на сегодня\n" .
            "/next - ближайшее событие\n" .
            "/help - помощь"
        );

        return response()->json([
            'message' => 'Telegram успешно подключен',
        ]);
    }

    /**
     * Отключение Telegram
     */
    public function disconnect(): JsonResponse
    {
        $user = Auth::user();
        $user->telegram_id = null;
        $user->telegram_username = null;
        $user->save();

        return response()->json([
            'message' => 'Telegram отключен',
        ]);
    }

    /**
     * Webhook для получения сообщений от Telegram
     */
    public function webhook(Request $request): JsonResponse
    {
        $data = $request->all();

        // Обработка команды /start
        if (isset($data['message']['text']) && $data['message']['text'] === '/start') {
            $chatId = $data['message']['chat']['id'];
            $this->telegramService->sendMessage(
                $chatId,
                "👋 <b>Добро пожаловать!</b>\n\n" .
                "Для подключения уведомлений перейдите в личный кабинет на сайте и отсканируйте QR-код.\n\n" .
                "<a href='https://sbor.team'>sbor.team</a>"
            );
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
     * Обработка команды /schedule
     */
    private function handleScheduleCommand(int $chatId): void
    {
        $user = User::where('telegram_id', $chatId)->first();
        
        if (!$user) {
            $this->telegramService->sendMessage(
                $chatId,
                "❌ Ваш аккаунт не подключен. Перейдите на сайт для подключения."
            );
            return;
        }

        // Получаем предстоящие тренировки
        $trainings = \DB::table('training_attendance')
            ->where('player_user_id', $user->id)
            ->join('trainings', 'training_attendance.training_id', '=', 'trainings.id')
            ->where('trainings.training_date', '>=', now())
            ->where('trainings.status', 'scheduled')
            ->orderBy('trainings.training_date')
            ->limit(5)
            ->get();

        if ($trainings->isEmpty()) {
            $this->telegramService->sendMessage($chatId, "📅 На ближайшее время ничего не запланировано.");
            return;
        }

        $text = "📅 <b>Расписание:</b>\n\n";
        foreach ($trainings as $training) {
            $text .= "🏃 {$training->training_date} в {$training->start_time}\n";
        }

        $this->telegramService->sendMessage($chatId, $text);
    }

    /**
     * Обработка команды /next
     */
    private function handleNextCommand(int $chatId): void
    {
        $user = User::where('telegram_id', $chatId)->first();
        
        if (!$user) {
            $this->telegramService->sendMessage(
                $chatId,
                "❌ Ваш аккаунт не подключен."
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
            'success' => $result,
            'message' => $result ? 'Webhook установлен' : 'Ошибка установки webhook',
        ]);
    }
}
