<?php

namespace Modules\Training\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Modules\Team\Models\TeamMember;
use Modules\Training\Models\Training;
use Modules\Training\Models\TrainingAttendance;

class TrainingService
{
    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Training::query()
            ->with(['coach', 'club', 'team', 'venue', 'trainingType']);

        if (! empty($filters['club_id'])) {
            $query->where('club_id', $filters['club_id']);
        }
        if (! empty($filters['team_id'])) {
            $query->where('team_id', $filters['team_id']);
        }
        if (! empty($filters['coach_id'])) {
            $query->where('coach_id', $filters['coach_id']);
        }
        if (! empty($filters['date_from'])) {
            $query->where('training_date', '>=', $filters['date_from']);
        }
        if (! empty($filters['date_to'])) {
            $query->where('training_date', '<=', $filters['date_to']);
        }
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderByDesc('training_date')->paginate($perPage);
    }

    public function find(int $id): Training
    {
        return Training::with([
            'coach', 'club', 'team', 'venue', 'trainingType',
            'attendance.player', 'attendance.markedBy', 'media.file',
        ])->findOrFail($id);
    }

    public function create(array $data): Training
    {
        return DB::transaction(function () use ($data) {
            $training = Training::create($data);

            $this->createAttendanceRecords($training);

            return $training->load(['coach', 'club', 'team', 'venue', 'trainingType']);
        });
    }

    public function update(Training $training, array $data): Training
    {
        $training->update($data);
        return $training->fresh();
    }

    public function cancel(Training $training): Training
    {
        $training->update(['status' => 'cancelled']);
        return $training->fresh();
    }

    public function markAttendance(int $trainingId, int $playerUserId, array $data): TrainingAttendance
    {
        $attendance = TrainingAttendance::where('training_id', $trainingId)
            ->where('player_user_id', $playerUserId)
            ->first();

        $updateData = [
            'attendance_status'  => $data['attendance_status'],
            'absence_reason'     => $data['absence_reason'] ?? null,
            'marked_by_user_id'  => auth()->id(),
        ];

        if ($data['attendance_status'] === 'present') {
            $updateData['confirmed_at'] = now();
        }

        if ($attendance) {
            $attendance->update($updateData);
            return $attendance->fresh();
        }

        return TrainingAttendance::create(array_merge($updateData, [
            'training_id'    => $trainingId,
            'player_user_id' => $playerUserId,
        ]));
    }

    private function createAttendanceRecords(Training $training): void
    {
        $playerMembers = TeamMember::where('team_id', $training->team_id)
            ->where('is_active', true)
            ->get();

        foreach ($playerMembers as $member) {
            TrainingAttendance::create([
                'training_id'       => $training->id,
                'player_user_id'    => $member->user_id,
                'marked_by_user_id' => $training->coach_id,
                'attendance_status' => 'pending',
            ]);
        }
    }
}
