<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RefClubType extends Model
{
    protected $table = 'ref_club_types';

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    public function clubs(): HasMany
    {
        return $this->hasMany(Club::class, 'club_type_id');
    }
}
