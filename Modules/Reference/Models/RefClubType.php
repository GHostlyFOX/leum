<?php

namespace Modules\Reference\Models;

use Illuminate\Database\Eloquent\Model;

class RefClubType extends Model
{
    protected $table = 'ref_club_types';
    public $timestamps = false;

    protected $fillable = ['name'];
}
