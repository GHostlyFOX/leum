<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Venue extends Model
{
    protected $table = 'venues';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'country_id',
        'city_id',
        'address',
        'club_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // ── Связи ────────────────────────────────────────────────────────────

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class, 'club_id');
    }
}
