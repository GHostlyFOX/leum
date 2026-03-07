<?php

namespace Modules\Team\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Club\Models\Club;
use Modules\File\Models\File;
use Modules\Match\Models\GameMatch;
use Modules\Reference\Models\RefSportType;
use Modules\Reference\Models\Country;
use Modules\Reference\Models\City;
use Modules\Training\Models\Training;
use Modules\Training\Models\RecurringTraining;
use Modules\Match\Models\MatchCoach;
use Modules\Match\Models\MatchPlayer;
use Modules\Tournament\Models\TournamentTeam;

class Team extends Model
{
    protected $table = 'teams';

    protected $fillable = [
        'name', 'description', 'gender', 'logo_file_id',
        'birth_year', 'club_id', 'sport_type_id',
        'country_id', 'city_id',
    ];

    protected $casts = [
        'birth_year' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class, 'club_id');
    }

    public function logoFile()
    {
        return $this->belongsTo(File::class, 'logo_file_id');
    }

    public function sportType()
    {
        return $this->belongsTo(RefSportType::class, 'sport_type_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function members()
    {
        return $this->hasMany(TeamMember::class, 'team_id');
    }

    public function trainings()
    {
        return $this->hasMany(Training::class, 'team_id');
    }

    public function recurringTrainings()
    {
        return $this->hasMany(RecurringTraining::class, 'team_id');
    }

    public function matches()
    {
        return $this->hasMany(GameMatch::class, 'team_id');
    }

    public function opponentMatches()
    {
        return $this->hasMany(GameMatch::class, 'opponent_team_id');
    }

    public function matchCoaches()
    {
        return $this->hasMany(MatchCoach::class, 'team_id');
    }

    public function matchPlayers()
    {
        return $this->hasMany(MatchPlayer::class, 'team_id');
    }

    public function tournamentTeams()
    {
        return $this->hasMany(TournamentTeam::class, 'team_id');
    }
}
