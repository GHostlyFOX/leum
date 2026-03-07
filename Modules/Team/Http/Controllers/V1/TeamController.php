<?php

namespace Modules\Team\Http\Controllers\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Team\Services\TeamService;

class TeamController extends Controller
{
    public function __construct(
        private readonly TeamService $teamService
    ) {}

    public function index(int $clubId, Request $request): JsonResponse
    {
        $teams = $this->teamService->listByClub(
            clubId: $clubId,
            perPage: $request->integer('per_page', 15),
        );

        return response()->json($teams);
    }

    public function show(int $id): JsonResponse
    {
        $team = $this->teamService->find($id);
        return response()->json($team);
    }

    public function store(int $clubId, Request $request): JsonResponse
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

    public function destroy(int $id): JsonResponse
    {
        $team = $this->teamService->find($id);
        $this->teamService->delete($team);

        return response()->json(null, 204);
    }

    public function addMember(int $teamId, Request $request): JsonResponse
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
