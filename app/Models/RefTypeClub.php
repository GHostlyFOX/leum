<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefTypeClub extends Model
{
    protected $table = 'ref_type_club';

    protected $fillable = [
        'name',
    ];

    public $timestamps = false;
}
