<?php

namespace App\Services;

use App\Models\Tournament;
use App\Models\TournamentTeam;
use Illuminate\Pagination\LengthAwarePaginator;

class TournamentService
{
    /**
     * Список турниров с фильтрами.
     */
    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Tournament::query()
            ->with(['tournamentType', 'logoFile']);

        if (! empty($filters['sport_type_id'])) {
            $query->whereHas('tournamentType', fn ($q) =>
                $q->where('sport_type_id', $filters['sport_type_id'])
            );
        }
        if (! empty($filters['year'])) {
            $query->whereYear('starts_at', $filters['year']);
        }

        return $query->orderByDesc('starts_at')->paginate($perPage);
    }

    /**
     * Получить турнир по ID.
     */
    public function find(int $id): Tournament
    {
        return Tournament::with([
            'tournamentType', 'logoFile',
            'tournamentTeams.team', 'tournamentTeams.club',
            'matches',
        ])->findOrFail($id);
    }

    /**
     * Создать турнир.
     */
    public function create(array $data): Tournament
    {
        return Tournament::create($data);
    }

    /**
     * Обновить турнир.
     */
    public function update(Tournament $tournament, array $data): Tournament
    {
        $tournament->update($data);
        return $tournament->fresh();
    }

    /**
     * Зарегистрировать команду на турнир.
     */
    public function registerTeam(int $tournamentId, array $data): TournamentTeam
    {
        $data['tournament_id'] = $tournamentId;
        return TournamentTeam::create($data);
    }

    /**
     * Дисквалифицировать команду.
     */
    public function disqualifyTeam(TournamentTeam $entry): TournamentTeam
    {
        $entry->update(['status' => 'disqualified']);
        return $entry->fresh();
    }
}
