<?php

namespace Modules\Match\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Reference\Models\City;
use Modules\Reference\Models\Country;

class Opponent extends Model
{
    protected $table = 'opponents';
    public $timestamps = false;

    protected $fillable = ['name', 'city_id', 'country_id'];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function matches()
    {
        return $this->hasMany(GameMatch::class, 'opponent_id');
    }
}
