<?php

namespace Modules\Reference\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Team\Models\TeamMember;

class RefUserRole extends Model
{
    protected $table = 'ref_user_roles';
    public $timestamps = false;

    protected $fillable = ['name'];

    public function teamMembers()
    {
        return $this->hasMany(TeamMember::class, 'role_id');
    }
}
