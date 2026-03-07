<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RefUserRole extends Model
{
    protected $table = 'ref_user_roles';

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    public function teamMembers(): HasMany
    {
        return $this->hasMany(TeamMember::class, 'role_id');
    }
}
