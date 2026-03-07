<?php

namespace Modules\Team\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Club\Models\Club;
use Modules\Reference\Models\RefUserRole;
use Modules\User\Models\User;

class TeamMember extends Model
{
    protected $table = 'team_members';
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'club_id', 'team_id',
        'role_id', 'joined_at', 'is_active',
    ];

    protected $casts = [
        'joined_at' => 'date',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function club()
    {
        return $this->belongsTo(Club::class, 'club_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function role()
    {
        return $this->belongsTo(RefUserRole::class, 'role_id');
    }
}
