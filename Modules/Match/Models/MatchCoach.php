<?php

namespace Modules\Match\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Club\Models\Club;
use Modules\Team\Models\Team;
use Modules\User\Models\User;

class MatchCoach extends Model
{
    protected $table = 'match_coaches';
    public $timestamps = false;

    protected $fillable = [
        'match_id', 'club_id', 'team_id', 'coach_user_id',
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

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_user_id');
    }
}
