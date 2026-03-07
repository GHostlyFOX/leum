<?php

namespace Modules\Match\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Reference\Models\RefMatchEventType;
use Modules\User\Models\User;

class MatchEvent extends Model
{
    protected $table = 'match_events';
    public $timestamps = false;

    protected $fillable = [
        'match_id', 'event_type_id', 'match_minute',
        'player_user_id', 'assistant_user_id', 'event_at',
    ];

    protected $casts = [
        'match_minute' => 'integer',
        'event_at'     => 'datetime',
        'created_at'   => 'datetime',
    ];

    public function match()
    {
        return $this->belongsTo(GameMatch::class, 'match_id');
    }

    public function eventType()
    {
        return $this->belongsTo(RefMatchEventType::class, 'event_type_id');
    }

    public function player()
    {
        return $this->belongsTo(User::class, 'player_user_id');
    }

    public function assistant()
    {
        return $this->belongsTo(User::class, 'assistant_user_id');
    }
}
