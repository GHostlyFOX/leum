<?php

namespace Modules\Match\Http\Controllers\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Match\Services\MatchService;

class MatchController extends Controller
{
    public function __construct(
        private readonly MatchService $matchService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $matches = $this->matchService->list(
            filters: $request->only(['club_id', 'team_id', 'tournament_id', 'match_type', 'date_from', 'date_to']),
            perPage: $request->integer('per_page', 15),
        );

        return response()->json($matches);
    }

    public function show(int $id): JsonResponse
    {
        $match = $this->matchService->find($id);
        return response()->json($match);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'match_type'            => 'required|in:friendly,tournament_group,tournament_playoff',
            'tournament_id'         => 'nullable|exists:tournaments,id',
            'sport_type_id'         => 'required|exists:ref_sport_types,id',
            'venue_id'              => 'required|exists:venues,id',
            'name'                  => 'required|string|max:255',
            'description'           => 'nullable|string',
            'club_id'               => 'required|exists:clubs,id',
            'team_id'               => 'required|exists:teams,id',
            'opponent_team_id'      => 'nullable|exists:teams,id',
            'opponent_id'           => 'nullable|exists:opponents,id',
            'scheduled_at'          => 'required|date',
            'half_duration_minutes' => 'required|integer|min:5|max:60',
            'halves_count'          => 'required|integer|min:1|max:4',
            'is_away'               => 'boolean',
        ]);

        $match = $this->matchService->create($validated);

        return response()->json($match, 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $match = $this->matchService->find($id);

        $validated = $request->validate([
            'venue_id'              => 'sometimes|exists:venues,id',
            'name'                  => 'sometimes|string|max:255',
            'description'           => 'nullable|string',
            'scheduled_at'          => 'sometimes|date',
            'half_duration_minutes' => 'sometimes|integer|min:5|max:60',
            'halves_count'          => 'sometimes|integer|min:1|max:4',
            'is_away'               => 'boolean',
        ]);

        $updated = $this->matchService->update($match, $validated);

        return response()->json($updated);
    }

    public function start(int $id): JsonResponse
    {
        $match = $this->matchService->find($id);
        $started = $this->matchService->startMatch($match);

        return response()->json($started);
    }

    public function end(int $id): JsonResponse
    {
        $match = $this->matchService->find($id);
        $ended = $this->matchService->endMatch($match);

        return response()->json($ended);
    }

    public function addEvent(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'event_type_id'     => 'required|exists:ref_match_event_types,id',
            'match_minute'      => 'required|integer|min:0',
            'player_user_id'    => 'required|exists:users,id',
            'assistant_user_id' => 'nullable|exists:users,id',
        ]);

        $event = $this->matchService->addEvent($id, $validated);

        return response()->json($event, 201);
    }

    public function setLineup(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'players'                    => 'required|array|min:1',
            'players.*.player_user_id'   => 'required|exists:users,id',
            'players.*.position_id'      => 'required|exists:ref_positions,id',
            'players.*.is_starter'       => 'boolean',
            'players.*.absence_reason'   => 'nullable|string',
            'players.*.parent_user_id'   => 'nullable|exists:users,id',
        ]);

        $this->matchService->setLineup($id, $validated['players']);

        return response()->json(['message' => 'Состав обновлён']);
    }
}
