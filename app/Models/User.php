<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

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
        'birth_date'       => 'date',
        'notifications_on' => 'boolean',
        'created_at'       => 'datetime',
        'updated_at'       => 'datetime',
    ];

    /**
     * Указываем Laravel, что колонка пароля называется password_hash.
     */
    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }

    // ── Связи ────────────────────────────────────────────────────────────

    public function photoFile(): BelongsTo
    {
        return $this->belongsTo(File::class, 'photo_file_id');
    }

    public function playerProfile(): HasOne
    {
        return $this->hasOne(PlayerProfile::class, 'user_id');
    }

    public function coachProfile(): HasOne
    {
        return $this->hasOne(CoachProfile::class, 'user_id');
    }

    public function teamMemberships(): HasMany
    {
        return $this->hasMany(TeamMember::class, 'user_id');
    }

    /**
     * Дети (если пользователь — родитель).
     */
    public function children(): HasMany
    {
        return $this->hasMany(UserParentPlayer::class, 'parent_user_id');
    }

    /**
     * Родители (если пользователь — игрок).
     */
    public function parents(): HasMany
    {
        return $this->hasMany(UserParentPlayer::class, 'player_user_id');
    }

    // ── Вспомогательные методы ───────────────────────────────────────────

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
