<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\RefRole;
use App\Models\Club;
use App\Models\Team;

class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = [
        'user',
        'ref_roles',
        'club',
        'teams',
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user');
    }

    public function refRole()
    {
        return $this->belongsTo(RefRole::class, 'ref_roles');
    }

    public function club()
    {
        return $this->belongsTo(Club::class, 'club');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'teams');
    }
}
