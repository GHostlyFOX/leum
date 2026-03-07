<?php

namespace Modules\Tournament\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Tournament\Models\Tournament;
use Modules\Tournament\Models\TournamentTeam;

class TournamentService
{
    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Tournament::query()
            ->with(['tournamentType']);

        if (! empty($filters['sport_type_id'])) {
            $query->whereHas('tournamentType', function ($q) use ($filters) {
                $q->where('sport_type_id', $filters['sport_type_id']);
            });
        }
        if (! empty($filters['year'])) {
            $query->whereYear('starts_at', $filters['year']);
        }

        return $query->orderByDesc('starts_at')->paginate($perPage);
    }

    public function find(int $id): Tournament
    {
        return Tournament::with([
            'tournamentType', 'logoFile',
            'tournamentTeams.club', 'tournamentTeams.team',
            'matches',
        ])->findOrFail($id);
    }

    public function create(array $data): Tournament
    {
        return Tournament::create($data);
    }

    public function update(Tournament $tournament, array $data): Tournament
    {
        $tournament->update($data);
        return $tournament->fresh();
    }

    public function registerTeam(int $tournamentId, array $data): TournamentTeam
    {
        return TournamentTeam::create([
            'tournament_id' => $tournamentId,
            'club_id'       => $data['club_id'],
            'team_id'       => $data['team_id'],
            'status'        => 'participating',
        ]);
    }

    public function disqualifyTeam(TournamentTeam $entry): TournamentTeam
    {
        $entry->update(['status' => 'disqualified']);
        return $entry->fresh();
    }
}
