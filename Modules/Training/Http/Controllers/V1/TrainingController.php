<?php

namespace Modules\Training\Http\Controllers\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Training\Http\Requests\MarkAttendanceRequest;
use Modules\Training\Http\Requests\StoreTrainingRequest;
use Modules\Training\Http\Requests\UpdateTrainingRequest;
use Modules\Training\Http\Resources\TrainingAttendanceResource;
use Modules\Training\Http\Resources\TrainingResource;
use Modules\Training\Services\TrainingService;

class TrainingController extends Controller
{
    public function __construct(
        private readonly TrainingService $trainingService
    ) {}

    /**
     * GET /api/v1/trainings
     */
    public function index(\Illuminate\Http\Request $request): JsonResponse
    {
        $trainings = $this->trainingService->list(
            filters: $request->only(['club_id', 'team_id', 'coach_id', 'date_from', 'date_to', 'status']),
            perPage: $request->integer('per_page', 15),
        );

        return TrainingResource::collection($trainings)->response();
    }

    /**
     * GET /api/v1/trainings/{id}
     */
    public function show(int $id): JsonResponse
    {
        $training = $this->trainingService->find($id);

        return (new TrainingResource($training))->response();
    }

    /**
     * POST /api/v1/trainings
     */
    public function store(StoreTrainingRequest $request): JsonResponse
    {
        $training = $this->trainingService->create($request->validated());

        return (new TrainingResource($training))->response()->setStatusCode(201);
    }

    /**
     * PUT /api/v1/trainings/{id}
     */
    public function update(UpdateTrainingRequest $request, int $id): JsonResponse
    {
        $training = $this->trainingService->find($id);
        $updated  = $this->trainingService->update($training, $request->validated());

        return (new TrainingResource($updated))->response();
    }

    /**
     * POST /api/v1/trainings/{id}/cancel
     */
    public function cancel(int $id): JsonResponse
    {
        $training  = $this->trainingService->find($id);
        $cancelled = $this->trainingService->cancel($training);

        return (new TrainingResource($cancelled))->response();
    }

    /**
     * PATCH /api/v1/trainings/{trainingId}/attendance/{playerUserId}
     */
    public function markAttendance(MarkAttendanceRequest $request, int $trainingId, int $playerUserId): JsonResponse
    {
        $attendance = $this->trainingService->markAttendance(
            $trainingId, $playerUserId, $request->validated()
        );

        return (new TrainingAttendanceResource($attendance))->response();
    }
}
