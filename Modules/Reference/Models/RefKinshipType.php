<?php

namespace Modules\Reference\Models;

use Illuminate\Database\Eloquent\Model;

class RefKinshipType extends Model
{
    protected $table = 'ref_kinship_types';
    public $timestamps = false;

    protected $fillable = ['name'];
}
