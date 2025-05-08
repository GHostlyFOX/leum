<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefSex extends Model
{
    protected $table = 'ref_sex';

    protected $fillable = [
        'name',
    ];

    public $timestamps = false;
}
