<?php

namespace Modules\Club\Http\Controllers\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Club\Http\Requests\StoreClubRequest;
use Modules\Club\Http\Requests\UpdateClubRequest;
use Modules\Club\Http\Resources\ClubResource;
use Modules\Club\Services\ClubService;

class ClubController extends Controller
{
    public function __construct(
        private readonly ClubService $clubService
    ) {}

    /**
     * GET /api/v1/clubs
     */
    public function index(Request $request): JsonResponse
    {
        $clubs = $this->clubService->list(
            filters: $request->only(['sport_type_id', 'country_id', 'city_id', 'search']),
            perPage: $request->integer('per_page', 15),
        );

        return ClubResource::collection($clubs)->response();
    }

    /**
     * GET /api/v1/clubs/{id}
     */
    public function show(int $id): JsonResponse
    {
        $club = $this->clubService->find($id);

        return (new ClubResource($club))->response();
    }

    /**
     * POST /api/v1/clubs
     */
    public function store(StoreClubRequest $request): JsonResponse
    {
        $club = $this->clubService->create(
            data: collect($request->validated())->except('logo')->toArray(),
            logo: $request->file('logo'),
        );

        return (new ClubResource($club))->response()->setStatusCode(201);
    }

    /**
     * PUT /api/v1/clubs/{id}
     */
    public function update(UpdateClubRequest $request, int $id): JsonResponse
    {
        $club = $this->clubService->find($id);

        $updated = $this->clubService->update(
            club: $club,
            data: collect($request->validated())->except('logo')->toArray(),
            logo: $request->file('logo'),
        );

        return (new ClubResource($updated))->response();
    }

    /**
     * DELETE /api/v1/clubs/{id}
     */
    public function destroy(int $id): JsonResponse
    {
        $club = $this->clubService->find($id);
        $this->clubService->delete($club);

        return response()->json(null, 204);
    }
}
