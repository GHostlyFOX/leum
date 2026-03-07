<?php

namespace Modules\Reference\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Club\Models\Club;
use Modules\Team\Models\Team;
use Modules\Training\Models\Venue;
use Modules\Match\Models\Opponent;

class City extends Model
{
    protected $table = 'cities';
    public $timestamps = false;

    protected $fillable = ['name', 'country_id'];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function clubs()
    {
        return $this->hasMany(Club::class, 'city_id');
    }

    public function teams()
    {
        return $this->hasMany(Team::class, 'city_id');
    }

    public function venues()
    {
        return $this->hasMany(Venue::class, 'city_id');
    }

    public function opponents()
    {
        return $this->hasMany(Opponent::class, 'city_id');
    }
}
