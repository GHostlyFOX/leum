<?php

namespace App\Services;

use App\Models\GameMatch;
use App\Models\MatchCoach;
use App\Models\MatchEvent;
use App\Models\MatchPlayer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class MatchService
{
    /**
     * Список матчей с фильтрами.
     */
    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = GameMatch::query()
            ->with(['team', 'opponent', 'opponentTeam', 'venue', 'tournament']);

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

    /**
     * Получить матч по ID.
     */
    public function find(int $id): GameMatch
    {
        return GameMatch::with([
            'team', 'club', 'opponent', 'opponentTeam',
            'venue', 'tournament', 'sportType',
            'coaches.coach', 'players.player', 'players.position',
            'events.eventType', 'events.player',
        ])->findOrFail($id);
    }

    /**
     * Создать матч.
     */
    public function create(array $data): GameMatch
    {
        return GameMatch::create($data);
    }

    /**
     * Обновить матч.
     */
    public function update(GameMatch $match, array $data): GameMatch
    {
        $match->update($data);
        return $match->fresh();
    }

    /**
     * Начать матч (зафиксировать фактическое время начала).
     */
    public function startMatch(GameMatch $match): GameMatch
    {
        $match->update(['actual_start_at' => now()]);
        return $match->fresh();
    }

    /**
     * Завершить матч.
     */
    public function endMatch(GameMatch $match): GameMatch
    {
        $match->update(['actual_end_at' => now()]);
        return $match->fresh();
    }

    /**
     * Добавить событие матча (гол, карточка и т.д.).
     */
    public function addEvent(int $matchId, array $data): MatchEvent
    {
        $data['match_id'] = $matchId;
        return MatchEvent::create($data);
    }

    /**
     * Установить состав на матч.
     */
    public function setLineup(int $matchId, array $players): void
    {
        DB::transaction(function () use ($matchId, $players) {
            MatchPlayer::where('match_id', $matchId)->delete();

            foreach ($players as $playerData) {
                $playerData['match_id'] = $matchId;
                MatchPlayer::create($playerData);
            }
        });
    }

    /**
     * Назначить тренеров на матч.
     */
    public function setCoaches(int $matchId, array $coaches): void
    {
        DB::transaction(function () use ($matchId, $coaches) {
            MatchCoach::where('match_id', $matchId)->delete();

            foreach ($coaches as $coachData) {
                $coachData['match_id'] = $matchId;
                MatchCoach::create($coachData);
            }
        });
    }
}
