<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefRole extends Model
{
    protected $table = 'ref_roles';

    protected $fillable = [
        'name',
    ];

    public $timestamps = false;
}
