<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Opponent extends Model
{
    protected $table = 'opponents';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'city_id',
        'country_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
