<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefDominantFoot extends Model
{
    protected $table = 'ref_dominant_feet';

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];
}
