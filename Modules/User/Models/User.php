<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Auth\Models\Permission;
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
        'global_role',
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

    // ── RBAC ─────────────────────────────────────────────────────

    /**
     * Проверка глобальной роли.
     * Принимает одну роль или массив: $user->hasRole('admin')
     * или $user->hasRole(['admin', 'super_admin']).
     */
    public function hasRole(string|array $roles): bool
    {
        $roles = (array) $roles;
        return in_array($this->global_role, $roles, true);
    }

    public function isSuperAdmin(): bool
    {
        return $this->global_role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return in_array($this->global_role, ['super_admin', 'admin'], true);
    }

    /**
     * Проверка гранулярного разрешения.
     * super_admin имеет все разрешения автоматически.
     */
    public function hasPermission(string $permissionSlug): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return Permission::forRole($this->global_role)->contains($permissionSlug);
    }

    /**
     * Проверка роли пользователя внутри команды.
     * Ищет в team_members по user_id + team_id + role.name.
     */
    public function hasTeamRole(int $teamId, string|array $roleNames): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        $roleNames = (array) $roleNames;

        return $this->teamMemberships()
            ->where('team_id', $teamId)
            ->where('is_active', true)
            ->whereHas('role', fn ($q) => $q->whereIn('name', $roleNames))
            ->exists();
    }

    /**
     * Проверка роли пользователя внутри клуба (любая команда клуба).
     */
    public function hasClubRole(int $clubId, string|array $roleNames): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        $roleNames = (array) $roleNames;

        return $this->teamMemberships()
            ->where('club_id', $clubId)
            ->where('is_active', true)
            ->whereHas('role', fn ($q) => $q->whereIn('name', $roleNames))
            ->exists();
    }

    /**
     * Все разрешения текущего пользователя (для включения в ответ API /me).
     */
    public function getAllPermissions(): \Illuminate\Support\Collection
    {
        if ($this->isSuperAdmin()) {
            return Permission::pluck('slug');
        }

        return Permission::forRole($this->global_role);
    }
}
