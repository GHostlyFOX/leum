<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\TeamService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function __construct(
        private readonly TeamService $teamService
    ) {}

    /**
     * GET /api/v1/clubs/{clubId}/teams
     */
    public function index(int $clubId, Request $request): JsonResponse
    {
        $teams = $this->teamService->listByClub(
            clubId: $clubId,
            perPage: $request->integer('per_page', 15),
        );

        return response()->json($teams);
    }

    /**
     * GET /api/v1/teams/{id}
     */
    public function show(int $id): JsonResponse
    {
        $team = $this->teamService->find($id);
        return response()->json($team);
    }

    /**
     * POST /api/v1/clubs/{clubId}/teams
     */
    public function store(Request $request, int $clubId): JsonResponse
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'gender'        => 'required|in:boys,girls,mixed',
            'birth_year'    => 'required|integer|min:2000|max:2030',
            'sport_type_id' => 'required|exists:ref_sport_types,id',
            'country_id'    => 'nullable|exists:countries,id',
            'city_id'       => 'nullable|exists:cities,id',
        ]);

        $validated['club_id'] = $clubId;
        $team = $this->teamService->create($validated);

        return response()->json($team, 201);
    }

    /**
     * PUT /api/v1/teams/{id}
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $team = $this->teamService->find($id);

        $validated = $request->validate([
            'name'          => 'sometimes|string|max:255',
            'description'   => 'nullable|string',
            'gender'        => 'sometimes|in:boys,girls,mixed',
            'birth_year'    => 'sometimes|integer|min:2000|max:2030',
            'sport_type_id' => 'sometimes|exists:ref_sport_types,id',
            'country_id'    => 'nullable|exists:countries,id',
            'city_id'       => 'nullable|exists:cities,id',
        ]);

        $updated = $this->teamService->update($team, $validated);

        return response()->json($updated);
    }

    /**
     * DELETE /api/v1/teams/{id}
     */
    public function destroy(int $id): JsonResponse
    {
        $team = $this->teamService->find($id);
        $this->teamService->delete($team);

        return response()->json(null, 204);
    }

    /**
     * POST /api/v1/teams/{teamId}/members
     */
    public function addMember(Request $request, int $teamId): JsonResponse
    {
        $validated = $request->validate([
            'user_id'   => 'required|exists:users,id',
            'club_id'   => 'required|exists:clubs,id',
            'role_id'   => 'required|exists:ref_user_roles,id',
            'joined_at' => 'nullable|date',
        ]);

        $member = $this->teamService->addMember($teamId, $validated);

        return response()->json($member, 201);
    }
}
