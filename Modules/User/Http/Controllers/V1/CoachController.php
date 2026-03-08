<?php

namespace Modules\User\Http\Controllers\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\User\Http\Requests\CreateCoachProfileRequest;
use Modules\User\Http\Requests\UpdateCoachProfileRequest;
use Modules\User\Http\Resources\CoachProfileResource;
use Modules\User\Services\CoachService;

class CoachController extends Controller
{
    public function __construct(
        private readonly CoachService $coachService
    ) {}

    /**
     * GET /api/v1/coaches
     */
    public function index(Request $request): JsonResponse
    {
        $coaches = $this->coachService->list(
            filters: $request->only(['sport_type_id', 'club_id', 'search']),
            perPage: $request->integer('per_page', 15),
        );

        return CoachProfileResource::collection($coaches)->response();
    }

    /**
     * GET /api/v1/coaches/{id}
     */
    public function show(int $id): JsonResponse
    {
        $coach = $this->coachService->find($id);

        return (new CoachProfileResource($coach))->response();
    }

    /**
     * PUT /api/v1/coaches/{id}
     */
    public function update(UpdateCoachProfileRequest $request, int $id): JsonResponse
    {
        $coach   = $this->coachService->findOrFail($id);
        $updated = $this->coachService->update($coach, $request->validated());

        return (new CoachProfileResource($updated))->response();
    }

    /**
     * DELETE /api/v1/coaches/{id}
     */
    public function destroy(int $id): JsonResponse
    {
        $coach = $this->coachService->findOrFail($id);
        $this->coachService->delete($coach);

        return response()->json(null, 204);
    }
}
