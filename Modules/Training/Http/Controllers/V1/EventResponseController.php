<?php

namespace Modules\Training\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResponseResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Match\Models\GameMatch;
use Modules\Tournament\Models\Tournament;
use Modules\Training\Models\EventResponse;
use Modules\Training\Models\Training;

class EventResponseController extends Controller
{
    /**
     * Получить отклики на событие
     */
    public function index(string $eventType, int $eventId, Request $request): JsonResponse
    {
        $request->validate([
            'status' => 'nullable|in:yes,no,maybe,pending',
            'with_users' => 'nullable|boolean',
        ]);

        // Проверяем существование события
        $event = $this->getEvent($eventType, $eventId);
        if (!$event) {
            return response()->json([
                'message' => 'Событие не найдено',
            ], 404);
        }

        $query = EventResponse::forEvent($eventType, $eventId);

        if ($request->has('status')) {
            $query->byStatus($request->status);
        }

        if ($request->boolean('with_users', true)) {
            $query->with('user');
        }

        $responses = $query->get();

        // Формируем сводку
        $summary = EventResponse::getSummary($eventType, $eventId);

        return response()->json([
            'summary' => $summary,
            'responses' => EventResponseResource::collection($responses),
        ]);
    }

    /**
     * Создать/обновить отклик текущего пользователя
     */
    public function store(string $eventType, int $eventId, Request $request): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:yes,no,maybe',
            'comment' => 'nullable|string|max:500',
        ]);

        // Проверяем существование события
        $event = $this->getEvent($eventType, $eventId);
        if (!$event) {
            return response()->json([
                'message' => 'Событие не найдено',
            ], 404);
        }

        $userId = auth()->id();

        // Получаем или создаём отклик
        $response = EventResponse::getOrCreate($eventType, $eventId, $userId);
        $response->updateStatus($validated['status'], $validated['comment'] ?? null);
        $response->load('user');

        $isNew = $response->wasRecentlyCreated;

        return response()->json([
            'data' => new EventResponseResource($response),
            'message' => $isNew ? 'Отклик создан' : 'Отклик обновлён',
        ], $isNew ? 201 : 200);
    }

    /**
     * Получить свой отклик
     */
    public function myResponse(string $eventType, int $eventId): JsonResponse
    {
        $userId = auth()->id();

        $response = EventResponse::forEvent($eventType, $eventId)
            ->byUser($userId)
            ->with('user')
            ->first();

        if (!$response) {
            return response()->json([
                'status' => 'pending',
            ]);
        }

        return response()->json([
            'data' => new EventResponseResource($response),
        ]);
    }

    /**
     * Обновить отклик другого пользователя (для родителей/тренеров)
     */
    public function update(string $eventType, int $eventId, int $userId, Request $request): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:yes,no,maybe,pending',
            'comment' => 'nullable|string|max:500',
        ]);

        $response = EventResponse::forEvent($eventType, $eventId)
            ->byUser($userId)
            ->first();

        if (!$response) {
            // Создаём новый отклик
            $response = EventResponse::getOrCreate($eventType, $eventId, $userId);
        }

        $response->updateStatus($validated['status'], $validated['comment'] ?? null);
        $response->load('user');

        return response()->json([
            'data' => new EventResponseResource($response),
            'message' => 'Отклик обновлён',
        ]);
    }

    /**
     * Удалить отклик
     */
    public function destroy(string $eventType, int $eventId, int $userId): JsonResponse
    {
        $response = EventResponse::forEvent($eventType, $eventId)
            ->byUser($userId)
            ->first();

        if ($response) {
            // Сбрасываем в pending вместо удаления
            $response->updateStatus('pending');
        }

        return response()->json(null, 204);
    }

    /**
     * Массовое обновление откликов
     */
    public function bulkStore(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'event_ids' => 'required|array|min:1|max:50',
            'event_ids.*' => 'integer',
            'event_type' => 'required|in:training,match,tournament',
            'status' => 'required|in:yes,no,maybe,pending',
            'comment' => 'nullable|string|max:500',
        ]);

        $userId = auth()->id();
        $responses = [];

        foreach ($validated['event_ids'] as $eventId) {
            $response = EventResponse::getOrCreate(
                $validated['event_type'],
                $eventId,
                $userId
            );
            $response->updateStatus($validated['status'], $validated['comment'] ?? null);
            $responses[] = $response;
        }

        return response()->json([
            'updated' => count($responses),
            'responses' => EventResponseResource::collection(collect($responses)),
        ]);
    }

    /**
     * Получить предстоящие события пользователя с откликами
     */
    public function upcoming(int $userId, Request $request): JsonResponse
    {
        $request->validate([
            'event_type' => 'nullable|in:training,match,tournament,all',
            'response_status' => 'nullable|in:yes,no,maybe,pending,all',
            'days' => 'nullable|integer|max:90',
        ]);

        $days = $request->input('days', 30);
        $eventType = $request->input('event_type', 'all');
        $responseStatus = $request->input('response_status', 'all');

        // Получаем отклики пользователя
        $query = EventResponse::byUser($userId)
            ->whereHas('event', function ($q) use ($days) {
                $q->where('start_datetime', '>', now())
                  ->where('start_datetime', '<', now()->addDays($days));
            });

        if ($eventType !== 'all') {
            $query->byEventType($eventType);
        }

        if ($responseStatus !== 'all') {
            $query->byStatus($responseStatus);
        }

        $responses = $query->with('event')->get();

        $result = $responses->map(function ($response) {
            return [
                'event' => $this->formatEvent($response->event),
                'response' => new EventResponseResource($response),
            ];
        });

        return response()->json($result);
    }

    /**
     * Получить событие по типу и ID
     */
    private function getEvent(string $eventType, int $eventId)
    {
        return match ($eventType) {
            'training' => Training::find($eventId),
            'match' => GameMatch::find($eventId),
            'tournament' => Tournament::find($eventId),
            default => null,
        };
    }

    /**
     * Форматировать событие для ответа
     */
    private function formatEvent($event): array
    {
        if ($event instanceof Training) {
            return [
                'id' => $event->id,
                'type' => 'training',
                'title' => $event->title,
                'start_datetime' => $event->start_datetime,
            ];
        }

        if ($event instanceof GameMatch) {
            return [
                'id' => $event->id,
                'type' => 'match',
                'title' => $event->title,
                'start_datetime' => $event->match_datetime,
            ];
        }

        if ($event instanceof Tournament) {
            return [
                'id' => $event->id,
                'type' => 'tournament',
                'title' => $event->name,
                'start_datetime' => $event->start_date,
            ];
        }

        return [];
    }
}
