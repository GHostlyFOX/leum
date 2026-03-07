<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Club\Models\Club;
use Modules\File\Models\File;
use Modules\Team\Models\TeamMember;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'email',
        'phone',
        'password_hash',
        'photo_file_id',
        'notifications_on',
        'birth_date',
        'gender',
    ];

    protected $hidden = [
        'password_hash',
    ];

    protected $casts = [
        'notifications_on' => 'boolean',
        'birth_date'       => 'date',
        'created_at'       => 'datetime',
        'updated_at'       => 'datetime',
    ];

    // Laravel auth: колонка пароля — password_hash
    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }

    // ── Relationships ──────────────────────────────────────────

    public function photoFile()
    {
        return $this->belongsTo(File::class, 'photo_file_id');
    }

    public function playerProfile()
    {
        return $this->hasOne(PlayerProfile::class, 'user_id');
    }

    public function coachProfile()
    {
        return $this->hasOne(CoachProfile::class, 'user_id');
    }

    public function teamMemberships()
    {
        return $this->hasMany(TeamMember::class, 'user_id');
    }

    public function children()
    {
        return $this->hasMany(UserParentPlayer::class, 'parent_user_id');
    }

    public function parents()
    {
        return $this->hasMany(UserParentPlayer::class, 'player_user_id');
    }

    // ── Accessors ──────────────────────────────────────────────

    public function getFullNameAttribute(): string
    {
        return trim("{$this->last_name} {$this->first_name} {$this->middle_name}");
    }

    public function getShortNameAttribute(): string
    {
        $initials = mb_substr($this->first_name, 0, 1) . '.';
        if ($this->middle_name) {
            $initials .= ' ' . mb_substr($this->middle_name, 0, 1) . '.';
        }
        return "{$this->last_name} {$initials}";
    }
}
