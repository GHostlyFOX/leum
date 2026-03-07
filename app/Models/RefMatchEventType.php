<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefMatchEventType extends Model
{
    protected $table = 'ref_match_event_types';

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];
}
