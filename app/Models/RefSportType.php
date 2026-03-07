<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RefSportType extends Model
{
    protected $table = 'ref_sport_types';

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    // ── Связи ────────────────────────────────────────────────────────────

    public function positions(): HasMany
    {
        return $this->hasMany(RefPosition::class, 'sport_type_id');
    }

    public function clubs(): HasMany
    {
        return $this->hasMany(Club::class, 'sport_type_id');
    }

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class, 'sport_type_id');
    }
}
