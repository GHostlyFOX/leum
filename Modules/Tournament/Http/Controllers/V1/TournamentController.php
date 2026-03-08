<?php

namespace Modules\Tournament\Http\Controllers\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Tournament\Http\Requests\RegisterTeamRequest;
use Modules\Tournament\Http\Requests\StoreTournamentRequest;
use Modules\Tournament\Http\Requests\UpdateTournamentRequest;
use Modules\Tournament\Http\Resources\TournamentResource;
use Modules\Tournament\Http\Resources\TournamentTeamResource;
use Modules\Tournament\Services\TournamentService;

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

        return TournamentResource::collection($tournaments)->response();
    }

    /**
     * GET /api/v1/tournaments/{id}
     */
    public function show(int $id): JsonResponse
    {
        $tournament = $this->tournamentService->find($id);

        return (new TournamentResource($tournament))->response();
    }

    /**
     * POST /api/v1/tournaments
     */
    public function store(StoreTournamentRequest $request): JsonResponse
    {
        $tournament = $this->tournamentService->create($request->validated());

        return (new TournamentResource($tournament))->response()->setStatusCode(201);
    }

    /**
     * PUT /api/v1/tournaments/{id}
     */
    public function update(UpdateTournamentRequest $request, int $id): JsonResponse
    {
        $tournament = $this->tournamentService->find($id);
        $updated    = $this->tournamentService->update($tournament, $request->validated());

        return (new TournamentResource($updated))->response();
    }

    /**
     * POST /api/v1/tournaments/{id}/teams
     */
    public function registerTeam(RegisterTeamRequest $request, int $id): JsonResponse
    {
        $entry = $this->tournamentService->registerTeam($id, $request->validated());

        return (new TournamentTeamResource($entry))->response()->setStatusCode(201);
    }
}
