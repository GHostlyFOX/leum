<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchEvent extends Model
{
    protected $table = 'match_events';

    public $timestamps = false;

    protected $fillable = [
        'match_id',
        'event_type_id',
        'match_minute',
        'player_user_id',
        'assistant_user_id',
        'event_at',
    ];

    protected $casts = [
        'match_minute' => 'integer',
        'event_at'     => 'datetime',
        'created_at'   => 'datetime',
    ];

    // ── Связи ────────────────────────────────────────────────────────────

    public function match(): BelongsTo
    {
        return $this->belongsTo(GameMatch::class, 'match_id');
    }

    public function eventType(): BelongsTo
    {
        return $this->belongsTo(RefMatchEventType::class, 'event_type_id');
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(User::class, 'player_user_id');
    }

    public function assistant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assistant_user_id');
    }
}
