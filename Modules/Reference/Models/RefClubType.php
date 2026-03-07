<?php

namespace Modules\Reference\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Club\Models\Club;

class RefClubType extends Model
{
    protected $table = 'ref_club_types';
    public $timestamps = false;

    protected $fillable = ['name'];

    public function clubs()
    {
        return $this->hasMany(Club::class, 'club_type_id');
    }
}
