<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Модель матча.
 *
 * Используем имя GameMatch вместо Match, т.к. `match` — зарезервированное
 * слово в PHP 8.0+.
 */
class GameMatch extends Model
{
    protected $table = 'matches';

    protected $fillable = [
        'match_type',
        'tournament_id',
        'sport_type_id',
        'venue_id',
        'name',
        'description',
        'club_id',
        'team_id',
        'opponent_team_id',
        'opponent_id',
        'scheduled_at',
        'half_duration_minutes',
        'halves_count',
        'is_away',
        'actual_start_at',
        'actual_end_at',
    ];

    protected $casts = [
        'scheduled_at'          => 'datetime',
        'actual_start_at'       => 'datetime',
        'actual_end_at'         => 'datetime',
        'half_duration_minutes' => 'integer',
        'halves_count'          => 'integer',
        'is_away'               => 'boolean',
        'created_at'            => 'datetime',
        'updated_at'            => 'datetime',
    ];

    // ── Связи ────────────────────────────────────────────────────────────

    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class, 'tournament_id');
    }

    public function sportType(): BelongsTo
    {
        return $this->belongsTo(RefSportType::class, 'sport_type_id');
    }

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class, 'venue_id');
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class, 'club_id');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    /**
     * Команда-соперник из системы.
     */
    public function opponentTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'opponent_team_id');
    }

    /**
     * Внешний соперник (не в системе).
     */
    public function opponent(): BelongsTo
    {
        return $this->belongsTo(Opponent::class, 'opponent_id');
    }

    public function coaches(): HasMany
    {
        return $this->hasMany(MatchCoach::class, 'match_id');
    }

    public function players(): HasMany
    {
        return $this->hasMany(MatchPlayer::class, 'match_id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(MatchEvent::class, 'match_id');
    }
}
