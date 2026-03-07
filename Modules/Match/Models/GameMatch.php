<?php

namespace Modules\Match\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Club\Models\Club;
use Modules\Reference\Models\RefSportType;
use Modules\Team\Models\Team;
use Modules\Tournament\Models\Tournament;
use Modules\Training\Models\Venue;

class GameMatch extends Model
{
    protected $table = 'matches';

    protected $fillable = [
        'match_type', 'tournament_id', 'sport_type_id', 'venue_id',
        'name', 'description', 'club_id', 'team_id',
        'opponent_team_id', 'opponent_id', 'scheduled_at',
        'half_duration_minutes', 'halves_count', 'is_away',
        'actual_start_at', 'actual_end_at',
    ];

    protected $casts = [
        'scheduled_at'         => 'datetime',
        'actual_start_at'      => 'datetime',
        'actual_end_at'        => 'datetime',
        'half_duration_minutes'=> 'integer',
        'halves_count'         => 'integer',
        'is_away'              => 'boolean',
        'created_at'           => 'datetime',
        'updated_at'           => 'datetime',
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class, 'tournament_id');
    }

    public function sportType()
    {
        return $this->belongsTo(RefSportType::class, 'sport_type_id');
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_id');
    }

    public function club()
    {
        return $this->belongsTo(Club::class, 'club_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function opponentTeam()
    {
        return $this->belongsTo(Team::class, 'opponent_team_id');
    }

    public function opponent()
    {
        return $this->belongsTo(Opponent::class, 'opponent_id');
    }

    public function coaches()
    {
        return $this->hasMany(MatchCoach::class, 'match_id');
    }

    public function players()
    {
        return $this->hasMany(MatchPlayer::class, 'match_id');
    }

    public function events()
    {
        return $this->hasMany(MatchEvent::class, 'match_id');
    }
}
