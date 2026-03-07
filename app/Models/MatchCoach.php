<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchCoach extends Model
{
    protected $table = 'match_coaches';

    public $timestamps = false;

    protected $fillable = [
        'match_id',
        'club_id',
        'team_id',
        'coach_user_id',
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

    public function coach(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coach_user_id');
    }
}
