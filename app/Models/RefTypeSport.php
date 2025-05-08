<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefTypeSport extends Model
{
    protected $table = 'ref_type_sport';

    protected $fillable = [
        'name',
    ];

    public $timestamps = false;
}
