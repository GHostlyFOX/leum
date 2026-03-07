<?php

namespace Modules\Training\Http\Controllers\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Training\Services\TrainingService;

class TrainingController extends Controller
{
    public function __construct(
        private readonly TrainingService $trainingService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $trainings = $this->trainingService->list(
            filters: $request->only(['club_id', 'team_id', 'coach_id', 'date_from', 'date_to', 'status']),
            perPage: $request->integer('per_page', 15),
        );

        return response()->json($trainings);
    }

    public function show(int $id): JsonResponse
    {
        $training = $this->trainingService->find($id);
        return response()->json($training);
    }

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

    public function update(Request $request, int $id): JsonResponse
    {
        $training = $this->trainingService->find($id);

        $validated = $request->validate([
            'training_date'    => 'sometimes|date',
            'start_time'       => 'sometimes|date_format:H:i',
            'duration_minutes' => 'sometimes|integer|min:15|max:300',
            'venue_id'         => 'sometimes|exists:venues,id',
            'training_type_id' => 'sometimes|exists:ref_training_types,id',
            'notify_parents'   => 'boolean',
            'require_rsvp'     => 'boolean',
            'comment'          => 'nullable|string',
        ]);

        $updated = $this->trainingService->update($training, $validated);

        return response()->json($updated);
    }

    public function cancel(int $id): JsonResponse
    {
        $training = $this->trainingService->find($id);
        $this->trainingService->cancel($training);

        return response()->json(['message' => 'Тренировка отменена']);
    }

    public function markAttendance(Request $request, int $trainingId, int $playerUserId): JsonResponse
    {
        $validated = $request->validate([
            'attendance_status' => 'required|in:pending,present,absent',
            'absence_reason'    => 'nullable|string',
        ]);

        $attendance = $this->trainingService->markAttendance(
            $trainingId, $playerUserId, $validated
        );

        return response()->json($attendance);
    }
}
