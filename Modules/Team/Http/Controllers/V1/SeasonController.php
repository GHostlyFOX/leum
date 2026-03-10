<?php

namespace Modules\Team\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SeasonResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Team\Models\Season;
use Modules\Team\Models\Team;

class SeasonController extends Controller
{
    /**
     * Список сезонов
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'club_id' => 'required|integer|exists:clubs,id',
            'status' => 'nullable|in:planned,active,archived',
            'year' => 'nullable|integer',
        ]);

        $query = Season::byClub($request->club_id)
            ->with(['sportType', 'teams']);

        if ($request->has('status')) {
            $query->byStatus($request->status);
        }

        if ($request->has('year')) {
            $query->byYear($request->year);
        }

        $seasons = $query->orderBy('start_date', 'desc')
            ->paginate($request->per_page ?? 15);

        return response()->json([
            'data' => SeasonResource::collection($seasons),
            'meta' => [
                'current_page' => $seasons->currentPage(),
                'last_page' => $seasons->lastPage(),
                'per_page' => $seasons->perPage(),
                'total' => $seasons->total(),
            ],
        ]);
    }

    /**
     * Создать сезон
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'club_id' => 'required|integer|exists:clubs,id',
            'sport_type_id' => 'required|integer|exists:ref_sport_types,id',
            'status' => 'nullable|in:planned,active,archived',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $season = Season::create($validated);
        $season->load(['sportType', 'teams']);

        return response()->json([
            'data' => new SeasonResource($season),
            'message' => 'Сезон успешно создан',
        ], 201);
    }

    /**
     * Получить сезон
     */
    public function show(int $id): JsonResponse
    {
        $season = Season::with(['sportType', 'teams'])->findOrFail($id);

        return response()->json([
            'data' => new SeasonResource($season),
        ]);
    }

    /**
     * Обновить сезон
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $season = Season::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'sport_type_id' => 'sometimes|integer|exists:ref_sport_types,id',
            'status' => 'sometimes|in:planned,active,archived',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after:start_date',
        ]);

        $season->update($validated);
        $season->load(['sportType', 'teams']);

        return response()->json([
            'data' => new SeasonResource($season),
            'message' => 'Сезон успешно обновлён',
        ]);
    }

    /**
     * Удалить сезон
     */
    public function destroy(int $id): JsonResponse
    {
        $season = Season::findOrFail($id);
        $season->delete();

        return response()->json(null, 204);
    }

    /**
     * Добавить команду к сезону
     */
    public function attachTeam(Request $request, int $id): JsonResponse
    {
        $season = Season::findOrFail($id);

        $validated = $request->validate([
            'team_id' => 'required|integer|exists:teams,id',
        ]);

        $team = Team::findOrFail($validated['team_id']);

        // Проверяем что команда принадлежит тому же клубу
        if ($team->club_id !== $season->club_id) {
            return response()->json([
                'message' => 'Команда не принадлежит клубу сезона',
            ], 422);
        }

        // Проверяем что команда ещё не добавлена
        if ($season->teams()->where('team_id', $team->id)->exists()) {
            return response()->json([
                'message' => 'Команда уже участвует в сезоне',
            ], 422);
        }

        $season->teams()->attach($team->id);

        return response()->json([
            'data' => new SeasonResource($season->load(['sportType', 'teams'])),
            'message' => 'Команда добавлена к сезону',
        ]);
    }

    /**
     * Удалить команду из сезона
     */
    public function detachTeam(Request $request, int $id): JsonResponse
    {
        $season = Season::findOrFail($id);

        $validated = $request->validate([
            'team_id' => 'required|integer|exists:teams,id',
        ]);

        $season->teams()->detach($validated['team_id']);

        return response()->json([
            'data' => new SeasonResource($season->load(['sportType', 'teams'])),
            'message' => 'Команда удалена из сезона',
        ]);
    }
}
