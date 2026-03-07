<?php

namespace App\Services;

use App\Models\Training;
use App\Models\TrainingAttendance;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class TrainingService
{
    /**
     * Список тренировок с фильтрами.
     */
    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Training::query()
            ->with(['coach', 'team', 'venue', 'trainingType']);

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

        return $query->orderBy('training_date')->orderBy('start_time')->paginate($perPage);
    }

    /**
     * Получить тренировку по ID.
     */
    public function find(int $id): Training
    {
        return Training::with([
            'coach', 'club', 'team', 'venue', 'trainingType',
            'attendance.player', 'media.file',
        ])->findOrFail($id);
    }

    /**
     * Создать тренировку.
     */
    public function create(array $data): Training
    {
        return DB::transaction(function () use ($data) {
            $training = Training::create($data);

            // Автоматически создаём записи посещаемости для всех активных игроков команды
            $this->createAttendanceRecords($training);

            return $training;
        });
    }

    /**
     * Обновить тренировку.
     */
    public function update(Training $training, array $data): Training
    {
        $training->update($data);
        return $training->fresh();
    }

    /**
     * Отменить тренировку.
     */
    public function cancel(Training $training): Training
    {
        $training->update(['status' => 'cancelled']);
        return $training->fresh();
    }

    /**
     * Отметить посещаемость.
     */
    public function markAttendance(int $trainingId, int $playerUserId, array $data): TrainingAttendance
    {
        return TrainingAttendance::updateOrCreate(
            [
                'training_id'    => $trainingId,
                'player_user_id' => $playerUserId,
            ],
            $data
        );
    }

    /**
     * Создать записи посещаемости для всех активных игроков команды.
     */
    private function createAttendanceRecords(Training $training): void
    {
        $members = $training->team->members()
            ->where('is_active', true)
            ->whereHas('role', fn ($q) => $q->where('name', 'player'))
            ->get();

        foreach ($members as $member) {
            TrainingAttendance::create([
                'training_id'       => $training->id,
                'player_user_id'    => $member->user_id,
                'marked_by_user_id' => $training->coach_id,
                'attendance_status' => 'pending',
            ]);
        }
    }
}
