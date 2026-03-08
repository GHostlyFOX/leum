<?php

namespace Modules\Match\Http\Controllers\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Match\Http\Requests\CreateMatchEventRequest;
use Modules\Match\Http\Requests\SetLineupRequest;
use Modules\Match\Http\Requests\StoreMatchRequest;
use Modules\Match\Http\Requests\UpdateMatchRequest;
use Modules\Match\Http\Resources\MatchEventResource;
use Modules\Match\Http\Resources\MatchResource;
use Modules\Match\Services\MatchService;

class MatchController extends Controller
{
    public function __construct(
        private readonly MatchService $matchService
    ) {}

    /**
     * GET /api/v1/matches
     */
    public function index(Request $request): JsonResponse
    {
        $matches = $this->matchService->list(
            filters: $request->only(['club_id', 'team_id', 'tournament_id', 'match_type', 'date_from', 'date_to']),
            perPage: $request->integer('per_page', 15),
        );

        return MatchResource::collection($matches)->response();
    }

    /**
     * GET /api/v1/matches/{id}
     */
    public function show(int $id): JsonResponse
    {
        $match = $this->matchService->find($id);

        return (new MatchResource($match))->response();
    }

    /**
     * POST /api/v1/matches
     */
    public function store(StoreMatchRequest $request): JsonResponse
    {
        $match = $this->matchService->create($request->validated());

        return (new MatchResource($match))->response()->setStatusCode(201);
    }

    /**
     * PUT /api/v1/matches/{id}
     */
    public function update(UpdateMatchRequest $request, int $id): JsonResponse
    {
        $match   = $this->matchService->find($id);
        $updated = $this->matchService->update($match, $request->validated());

        return (new MatchResource($updated))->response();
    }

    /**
     * POST /api/v1/matches/{id}/start
     */
    public function start(int $id): JsonResponse
    {
        $match   = $this->matchService->find($id);
        $started = $this->matchService->startMatch($match);

        return (new MatchResource($started))->response();
    }

    /**
     * POST /api/v1/matches/{id}/end
     */
    public function end(int $id): JsonResponse
    {
        $match = $this->matchService->find($id);
        $ended = $this->matchService->endMatch($match);

        return (new MatchResource($ended))->response();
    }

    /**
     * POST /api/v1/matches/{id}/events
     */
    public function addEvent(CreateMatchEventRequest $request, int $id): JsonResponse
    {
        $event = $this->matchService->addEvent($id, $request->validated());

        return (new MatchEventResource($event))->response()->setStatusCode(201);
    }

    /**
     * PUT /api/v1/matches/{id}/lineup
     */
    public function setLineup(SetLineupRequest $request, int $id): JsonResponse
    {
        $this->matchService->setLineup($id, $request->validated()['players']);

        return response()->json(['message' => 'Состав обновлён']);
    }
}
