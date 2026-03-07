<?php

namespace Modules\Tournament\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Club\Models\Club;
use Modules\Team\Models\Team;

class TournamentTeam extends Model
{
    protected $table = 'tournament_teams';
    public $timestamps = false;

    protected $fillable = [
        'tournament_id', 'club_id', 'team_id', 'status',
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class, 'tournament_id');
    }

    public function club()
    {
        return $this->belongsTo(Club::class, 'club_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
}
