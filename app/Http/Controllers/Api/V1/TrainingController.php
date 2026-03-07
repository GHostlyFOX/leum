<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\TrainingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    public function __construct(
        private readonly TrainingService $trainingService
    ) {}

    /**
     * GET /api/v1/trainings
     */
    public function index(Request $request): JsonResponse
    {
        $trainings = $this->trainingService->list(
            filters: $request->only(['club_id', 'team_id', 'coach_id', 'date_from', 'date_to', 'status']),
            perPage: $request->integer('per_page', 15),
        );

        return response()->json($trainings);
    }

    /**
     * GET /api/v1/trainings/{id}
     */
    public function show(int $id): JsonResponse
    {
        $training = $this->trainingService->find($id);
        return response()->json($training);
    }

    /**
     * POST /api/v1/trainings
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'coach_id'         => 'required|exists:users,id',
            'club_id'          => 'required|exists:clubs,id',
            'team_id'          => 'required|exists:teams,id',
            'training_date'    => 'required|date',
            'start_time'       => 'required|date_format:H:i',
            'duration_minutes' => 'required|integer|min:15|max:300',
            'venue_id'         => 'required|exists:venues,id',
            'training_type_id' => 'required|exists:ref_training_types,id',
            'notify_parents'   => 'boolean',
            'require_rsvp'     => 'boolean',
            'comment'          => 'nullable|string',
        ]);

        $training = $this->trainingService->create($validated);

        return response()->json($training, 201);
    }

    /**
     * PUT /api/v1/trainings/{id}
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $training = $this->trainingService->find($id);

        $validated = $request->validate([
            'training_date'    => 'sometimes|date',
            'start_time'       => 'sometimes|date_format:H:i',
            'duration_minutes' => 'sometimes|integer|min:15|max:300',
            'venue_id'         => 'sometimes|exists:venues,id',
            'training_type_id' => 'sometimes|exists:ref_training_types,id',
            'status'           => 'sometimes|in:scheduled,completed,cancelled',
            'notify_parents'   => 'boolean',
            'require_rsvp'     => 'boolean',
            'comment'          => 'nullable|string',
        ]);

        $updated = $this->trainingService->update($training, $validated);

        return response()->json($updated);
    }

    /**
     * POST /api/v1/trainings/{id}/cancel
     */
    public function cancel(int $id): JsonResponse
    {
        $training = $this->trainingService->find($id);
        $cancelled = $this->trainingService->cancel($training);

        return response()->json($cancelled);
    }

    /**
     * PATCH /api/v1/trainings/{trainingId}/attendance/{playerUserId}
     */
    public function markAttendance(Request $request, int $trainingId, int $playerUserId): JsonResponse
    {
        $validated = $request->validate([
            'attendance_status' => 'required|in:pending,present,absent',
            'marked_by_user_id' => 'required|exists:users,id',
            'absence_reason'    => 'nullable|string',
        ]);

        $attendance = $this->trainingService->markAttendance($trainingId, $playerUserId, $validated);

        return response()->json($attendance);
    }
}
