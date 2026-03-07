<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchPlayer extends Model
{
    protected $table = 'match_players';

    public $timestamps = false;

    protected $fillable = [
        'match_id',
        'club_id',
        'team_id',
        'player_user_id',
        'position_id',
        'is_starter',
        'absence_reason',
        'parent_user_id',
    ];

    protected $casts = [
        'is_starter' => 'boolean',
    ];

    // ── Связи ────────────────────────────────────────────────────────────

    public function match(): BelongsTo
    {
        return $this->belongsTo(GameMatch::class, 'match_id');
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class, 'club_id');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(User::class, 'player_user_id');
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(RefPosition::class, 'position_id');
    }

    public function parentUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_user_id');
    }
}
