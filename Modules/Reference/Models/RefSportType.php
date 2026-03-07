<?php

namespace Modules\Reference\Models;

use Illuminate\Database\Eloquent\Model;

class RefSportType extends Model
{
    protected $table = 'ref_sport_types';
    public $timestamps = false;

    protected $fillable = ['name'];

    public function positions()
    {
        return $this->hasMany(RefPosition::class, 'sport_type_id');
    }
}
