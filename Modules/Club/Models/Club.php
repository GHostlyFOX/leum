<?php

namespace Modules\Club\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\File\Models\File;
use Modules\Reference\Models\RefClubType;
use Modules\Reference\Models\RefSportType;
use Modules\Reference\Models\Country;
use Modules\Reference\Models\City;
use Modules\Team\Models\Team;
use Modules\Team\Models\TeamMember;
use Modules\Training\Models\Venue;
use Modules\Training\Models\RecurringTraining;
use Modules\Training\Models\Training;
use Modules\Training\Models\RefTrainingType;
use Modules\Match\Models\GameMatch;
use Modules\Tournament\Models\TournamentTeam;

class Club extends Model
{
    protected $table = 'clubs';

    protected $fillable = [
        'name', 'logo_file_id', 'description', 'club_type_id',
        'sport_type_id', 'country_id', 'city_id', 'address',
        'email', 'phones',
    ];

    protected $casts = [
        'phones'     => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function logoFile()
    {
        return $this->belongsTo(File::class, 'logo_file_id');
    }

    public function clubType()
    {
        return $this->belongsTo(RefClubType::class, 'club_type_id');
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

    public function teams()
    {
        return $this->hasMany(Team::class, 'club_id');
    }

    public function teamMembers()
    {
        return $this->hasMany(TeamMember::class, 'club_id');
    }

    public function trainingTypes()
    {
        return $this->hasMany(RefTrainingType::class, 'club_id');
    }

    public function venues()
    {
        return $this->hasMany(Venue::class, 'club_id');
    }

    public function recurringTrainings()
    {
        return $this->hasMany(RecurringTraining::class, 'club_id');
    }

    public function trainings()
    {
        return $this->hasMany(Training::class, 'club_id');
    }

    public function matches()
    {
        return $this->hasMany(GameMatch::class, 'club_id');
    }

    public function tournamentTeams()
    {
        return $this->hasMany(TournamentTeam::class, 'club_id');
    }
}
