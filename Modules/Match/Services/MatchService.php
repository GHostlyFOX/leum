<?php

namespace Modules\Match\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Modules\Match\Models\GameMatch;
use Modules\Match\Models\MatchCoach;
use Modules\Match\Models\MatchEvent;
use Modules\Match\Models\MatchPlayer;

class MatchService
{
    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = GameMatch::query()
            ->with(['sportType', 'venue', 'club', 'team']);

        if (! empty($filters['club_id'])) {
            $query->where('club_id', $filters['club_id']);
        }
        if (! empty($filters['team_id'])) {
            $query->where('team_id', $filters['team_id']);
        }
        if (! empty($filters['tournament_id'])) {
            $query->where('tournament_id', $filters['tournament_id']);
        }
        if (! empty($filters['match_type'])) {
            $query->where('match_type', $filters['match_type']);
        }
        if (! empty($filters['date_from'])) {
            $query->where('scheduled_at', '>=', $filters['date_from']);
        }
        if (! empty($filters['date_to'])) {
            $query->where('scheduled_at', '<=', $filters['date_to']);
        }

        return $query->orderByDesc('scheduled_at')->paginate($perPage);
    }

    public function find(int $id): GameMatch
    {
        return GameMatch::with([
            'sportType', 'venue', 'club', 'team', 'tournament',
            'opponentTeam', 'opponent',
            'coaches.coach', 'players.player', 'players.position',
            'events.eventType', 'events.player', 'events.assistant',
        ])->findOrFail($id);
    }

    public function create(array $data): GameMatch
    {
        return GameMatch::create($data);
    }

    public function update(GameMatch $match, array $data): GameMatch
    {
        $match->update($data);
        return $match->fresh();
    }

    public function startMatch(GameMatch $match): GameMatch
    {
        $match->update(['actual_start_at' => now()]);
        return $match->fresh();
    }

    public function endMatch(GameMatch $match): GameMatch
    {
        $match->update(['actual_end_at' => now()]);
        return $match->fresh();
    }

    public function addEvent(int $matchId, array $data): MatchEvent
    {
        $data['match_id'] = $matchId;
        $data['event_at'] = now();

        return MatchEvent::create($data);
    }

    public function setLineup(int $matchId, array $players): void
    {
        DB::transaction(function () use ($matchId, $players) {
            MatchPlayer::where('match_id', $matchId)->delete();

            $match = GameMatch::findOrFail($matchId);

            foreach ($players as $player) {
                MatchPlayer::create([
                    'match_id'        => $matchId,
                    'club_id'         => $match->club_id,
                    'team_id'         => $match->team_id,
                    'player_user_id'  => $player['player_user_id'],
                    'position_id'     => $player['position_id'],
                    'is_starter'      => $player['is_starter'] ?? true,
                    'absence_reason'  => $player['absence_reason'] ?? null,
                    'parent_user_id'  => $player['parent_user_id'] ?? null,
                ]);
            }
        });
    }

    public function setCoaches(int $matchId, array $coaches): void
    {
        DB::transaction(function () use ($matchId, $coaches) {
            MatchCoach::where('match_id', $matchId)->delete();

            $match = GameMatch::findOrFail($matchId);

            foreach ($coaches as $coach) {
                MatchCoach::create([
                    'match_id'      => $matchId,
                    'club_id'       => $match->club_id,
                    'team_id'       => $match->team_id,
                    'coach_user_id' => $coach['coach_user_id'],
                ]);
            }
        });
    }
}
