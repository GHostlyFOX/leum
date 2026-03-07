<?php

namespace Modules\Reference\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Club\Models\Club;
use Modules\Team\Models\Team;
use Modules\Training\Models\Venue;
use Modules\Match\Models\Opponent;

class Country extends Model
{
    protected $table = 'countries';
    public $timestamps = false;

    protected $fillable = ['name'];

    public function cities()
    {
        return $this->hasMany(City::class, 'country_id');
    }

    public function clubs()
    {
        return $this->hasMany(Club::class, 'country_id');
    }

    public function teams()
    {
        return $this->hasMany(Team::class, 'country_id');
    }

    public function venues()
    {
        return $this->hasMany(Venue::class, 'country_id');
    }

    public function opponents()
    {
        return $this->hasMany(Opponent::class, 'country_id');
    }
}
