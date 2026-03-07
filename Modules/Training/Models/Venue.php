<?php

namespace Modules\Training\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Club\Models\Club;
use Modules\Match\Models\GameMatch;
use Modules\Reference\Models\Country;
use Modules\Reference\Models\City;

class Venue extends Model
{
    protected $table = 'venues';
    public $timestamps = false;

    protected $fillable = [
        'name', 'country_id', 'city_id', 'address', 'club_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function club()
    {
        return $this->belongsTo(Club::class, 'club_id');
    }

    public function trainings()
    {
        return $this->hasMany(Training::class, 'venue_id');
    }

    public function matches()
    {
        return $this->hasMany(GameMatch::class, 'venue_id');
    }
}
