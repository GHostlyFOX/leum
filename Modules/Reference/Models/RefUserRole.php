<?php

namespace Modules\Reference\Models;

use Illuminate\Database\Eloquent\Model;

class RefUserRole extends Model
{
    protected $table = 'ref_user_roles';
    public $timestamps = false;

    protected $fillable = ['name'];
}
