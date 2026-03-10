<?php

namespace Modules\Training\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\RecurringTrainingResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Training\Models\RecurringTraining;

class RecurringTrainingController extends Controller
{
    /**
     * Список шаблонов регулярных тренировок
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'club_id' => 'nullable|integer|exists:clubs,id',
            'team_id' => 'nullable|integer|exists:teams,id',
            'is_active' => 'nullable|boolean',
        ]);

        $query = RecurringTraining::with(['team', 'venue', 'coach']);

        if ($request->has('team_id')) {
            $query->where('team_id', $request->team_id);
        }

        if ($request->has('club_id')) {
            $query->whereHas('team', function ($q) use ($request) {
                $q->where('club_id', $request->club_id);
            });
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $templates = $query->orderBy('name')
            ->paginate($request->per_page ?? 15);

        return response()->json([
            'data' => RecurringTrainingResource::collection($templates),
            'meta' => [
                'current_page' => $templates->currentPage(),
                'last_page' => $templates->lastPage(),
                'per_page' => $templates->perPage(),
                'total' => $templates->total(),
            ],
        ]);
    }

    /**
     * Создать шаблон регулярных тренировок
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'team_id' => 'required|integer|exists:teams,id',
            'venue_id' => 'nullable|integer|exists:venues,id',
            'coach_id' => 'nullable|integer|exists:users,id',
            'schedule' => 'required|array',
            'schedule.*.day_of_week' => 'required|integer|min:0|max:6',
            'schedule.*.start_time' => 'required|date_format:H:i',
            'auto_create' => 'nullable|array',
            'auto_create.advance_days' => 'nullable|integer|min:1',
            'auto_create.until_date' => 'nullable|date',
            'duration_minutes' => 'nullable|integer|min:15',
            'notify_parents' => 'nullable|boolean',
            'require_rsvp' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['schedule'] = json_encode($validated['schedule']);
        if (isset($validated['auto_create'])) {
            $validated['auto_create'] = json_encode($validated['auto_create']);
        }

        $template = RecurringTraining::create($validated);
        $template->load(['team', 'venue', 'coach']);

        return response()->json([
            'data' => new RecurringTrainingResource($template),
            'message' => 'Шаблон создан',
        ], 201);
    }

    /**
     * Получить шаблон
     */
    public function show(int $id): JsonResponse
    {
        $template = RecurringTraining::with(['team', 'venue', 'coach'])
            ->findOrFail($id);

        return response()->json([
            'data' => new RecurringTrainingResource($template),
        ]);
    }

    /**
     * Обновить шаблон
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $template = RecurringTraining::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'venue_id' => 'sometimes|nullable|integer|exists:venues,id',
            'coach_id' => 'sometimes|nullable|integer|exists:users,id',
            'schedule' => 'sometimes|array',
            'schedule.*.day_of_week' => 'required_with:schedule|integer|min:0|max:6',
            'schedule.*.start_time' => 'required_with:schedule|date_format:H:i',
            'auto_create' => 'sometimes|nullable|array',
            'duration_minutes' => 'sometimes|nullable|integer|min:15',
            'notify_parents' => 'sometimes|boolean',
            'require_rsvp' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
        ]);

        if (isset($validated['schedule'])) {
            $validated['schedule'] = json_encode($validated['schedule']);
        }
        if (array_key_exists('auto_create', $validated) && $validated['auto_create'] !== null) {
            $validated['auto_create'] = json_encode($validated['auto_create']);
        }

        $template->update($validated);
        $template->load(['team', 'venue', 'coach']);

        return response()->json([
            'data' => new RecurringTrainingResource($template),
            'message' => 'Шаблон обновлён',
        ]);
    }

    /**
     * Удалить шаблон
     */
    public function destroy(int $id): JsonResponse
    {
        $template = RecurringTraining::findOrFail($id);
        $template->delete();

        return response()->json(null, 204);
    }

    /**
     * Сгенерировать тренировки из шаблона
     */
    public function generate(int $id, Request $request): JsonResponse
    {
        $template = RecurringTraining::with(['team', 'venue'])->findOrFail($id);

        $request->validate([
            'until_date' => 'nullable|date|after:today',
            'count' => 'nullable|integer|min:1|max:52',
        ]);

        $untilDate = $request->input('until_date', now()->addMonth()->format('Y-m-d'));
        $count = $request->input('count', 10);

        // TODO: Реализовать логику генерации тренировок
        // Это будет вынесено в Job или Service

        return response()->json([
            'message' => 'Генерация тренировок запущена',
            'data' => [
                'template_id' => $template->id,
                'until_date' => $untilDate,
                'max_count' => $count,
            ],
        ]);
    }
}
