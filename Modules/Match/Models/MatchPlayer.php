<?php

namespace Modules\Match\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Club\Models\Club;
use Modules\Reference\Models\RefPosition;
use Modules\Team\Models\Team;
use Modules\User\Models\User;

class MatchPlayer extends Model
{
    protected $table = 'match_players';
    public $timestamps = false;

    protected $fillable = [
        'match_id', 'club_id', 'team_id', 'player_user_id',
        'position_id', 'is_starter', 'absence_reason', 'parent_user_id',
    ];

    protected $casts = [
        'is_starter' => 'boolean',
    ];

    public function match()
    {
        return $this->belongsTo(GameMatch::class, 'match_id');
    }

    public function club()
    {
        return $this->belongsTo(Club::class, 'club_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function player()
    {
        return $this->belongsTo(User::class, 'player_user_id');
    }

    public function position()
    {
        return $this->belongsTo(RefPosition::class, 'position_id');
    }

    public function parentUser()
    {
        return $this->belongsTo(User::class, 'parent_user_id');
    }
}
