<?php

namespace Modules\Team\Http\Controllers\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Team\Http\Requests\AddMemberRequest;
use Modules\Team\Http\Requests\StoreTeamRequest;
use Modules\Team\Http\Requests\UpdateTeamRequest;
use Modules\Team\Http\Resources\TeamMemberResource;
use Modules\Team\Http\Resources\TeamResource;
use Modules\Team\Services\TeamService;

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

        return TeamResource::collection($teams)->response();
    }

    /**
     * GET /api/v1/teams/{id}
     */
    public function show(int $id): JsonResponse
    {
        $team = $this->teamService->find($id);

        return (new TeamResource($team))->response();
    }

    /**
     * POST /api/v1/clubs/{clubId}/teams
     */
    public function store(int $clubId, StoreTeamRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['club_id'] = $clubId;

        $team = $this->teamService->create($data);

        return (new TeamResource($team))->response()->setStatusCode(201);
    }

    /**
     * PUT /api/v1/teams/{id}
     */
    public function update(UpdateTeamRequest $request, int $id): JsonResponse
    {
        $team    = $this->teamService->find($id);
        $updated = $this->teamService->update($team, $request->validated());

        return (new TeamResource($updated))->response();
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
    public function addMember(int $teamId, AddMemberRequest $request): JsonResponse
    {
        $member = $this->teamService->addMember($teamId, $request->validated());

        return (new TeamMemberResource($member))->response()->setStatusCode(201);
    }
}
