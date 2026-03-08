<?php

namespace Modules\User\Http\Controllers\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\User\Http\Requests\CreatePlayerProfileRequest;
use Modules\User\Http\Requests\UpdatePlayerProfileRequest;
use Modules\User\Http\Resources\PlayerProfileResource;
use Modules\User\Services\PlayerService;

class PlayerController extends Controller
{
    public function __construct(
        private readonly PlayerService $playerService
    ) {}

    /**
     * GET /api/v1/players
     */
    public function index(Request $request): JsonResponse
    {
        $players = $this->playerService->list(
            filters: $request->only(['sport_type_id', 'club_id', 'team_id', 'search']),
            perPage: $request->integer('per_page', 15),
        );

        return PlayerProfileResource::collection($players)->response();
    }

    /**
     * GET /api/v1/players/{id}
     */
    public function show(int $id): JsonResponse
    {
        $player = $this->playerService->find($id);

        return (new PlayerProfileResource($player))->response();
    }

    /**
     * PUT /api/v1/players/{id}
     */
    public function update(UpdatePlayerProfileRequest $request, int $id): JsonResponse
    {
        $player  = $this->playerService->findOrFail($id);
        $updated = $this->playerService->update($player, $request->validated());

        return (new PlayerProfileResource($updated))->response();
    }

    /**
     * DELETE /api/v1/players/{id}
     */
    public function destroy(int $id): JsonResponse
    {
        $player = $this->playerService->findOrFail($id);
        $this->playerService->delete($player);

        return response()->json(null, 204);
    }
}
