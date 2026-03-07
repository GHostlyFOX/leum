<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\TournamentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    public function __construct(
        private readonly TournamentService $tournamentService
    ) {}

    /**
     * GET /api/v1/tournaments
     */
    public function index(Request $request): JsonResponse
    {
        $tournaments = $this->tournamentService->list(
            filters: $request->only(['sport_type_id', 'year']),
            perPage: $request->integer('per_page', 15),
        );

        return response()->json($tournaments);
    }

    /**
     * GET /api/v1/tournaments/{id}
     */
    public function show(int $id): JsonResponse
    {
        $tournament = $this->tournamentService->find($id);
        return response()->json($tournament);
    }

    /**
     * POST /api/v1/tournaments
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'tournament_type_id'    => 'required|exists:ref_tournament_types,id',
            'name'                  => 'required|string|max:255',
            'starts_at'             => 'required|date',
            'ends_at'               => 'required|date|after_or_equal:starts_at',
            'half_duration_minutes' => 'required|integer|min:5|max:90',
            'halves_count'          => 'required|integer|min:1|max:4',
            'organizer'             => 'nullable|string|max:255',
        ]);

        $tournament = $this->tournamentService->create($validated);

        return response()->json($tournament, 201);
    }

    /**
     * PUT /api/v1/tournaments/{id}
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $tournament = $this->tournamentService->find($id);

        $validated = $request->validate([
            'name'                  => 'sometimes|string|max:255',
            'starts_at'             => 'sometimes|date',
            'ends_at'              => 'sometimes|date|after_or_equal:starts_at',
            'half_duration_minutes' => 'sometimes|integer|min:5|max:90',
            'halves_count'          => 'sometimes|integer|min:1|max:4',
            'organizer'             => 'nullable|string|max:255',
        ]);

        $updated = $this->tournamentService->update($tournament, $validated);

        return response()->json($updated);
    }

    /**
     * POST /api/v1/tournaments/{id}/teams
     */
    public function registerTeam(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'club_id' => 'required|exists:clubs,id',
            'team_id' => 'required|exists:teams,id',
        ]);

        $entry = $this->tournamentService->registerTeam($id, $validated);

        return response()->json($entry, 201);
    }
}
