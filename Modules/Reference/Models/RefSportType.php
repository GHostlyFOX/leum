<?php

namespace Modules\Reference\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Club\Models\Club;
use Modules\Team\Models\Team;
use Modules\User\Models\PlayerProfile;
use Modules\User\Models\CoachProfile;
use Modules\Match\Models\GameMatch;

class RefSportType extends Model
{
    protected $table = 'ref_sport_types';
    public $timestamps = false;

    protected $fillable = ['name'];

    public function positions()
    {
        return $this->hasMany(RefPosition::class, 'sport_type_id');
    }

    public function clubs()
    {
        return $this->hasMany(Club::class, 'sport_type_id');
    }

    public function teams()
    {
        return $this->hasMany(Team::class, 'sport_type_id');
    }

    public function playerProfiles()
    {
        return $this->hasMany(PlayerProfile::class, 'sport_type_id');
    }

    public function coachProfiles()
    {
        return $this->hasMany(CoachProfile::class, 'sport_type_id');
    }

    public function matches()
    {
        return $this->hasMany(GameMatch::class, 'sport_type_id');
    }

    public function tournamentTypes()
    {
        return $this->hasMany(RefTournamentType::class, 'sport_type_id');
    }
}
