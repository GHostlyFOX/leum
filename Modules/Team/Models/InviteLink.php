<?php

namespace Modules\Team\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Models\User;

/**
 * Модель пригласительной ссылки
 * 
 * @property int $id
 * @property string $token
 * @property int $team_id
 * @property string $role
 * @property int $created_by_id
 * @property int|null $max_uses
 * @property int $used_count
 * @property \Carbon\Carbon $expires_at
 * @property \Carbon\Carbon $created_at
 */
class InviteLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'team_id',
        'role',
        'created_by_id',
        'max_uses',
        'used_count',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'max_uses' => 'integer',
        'used_count' => 'integer',
    ];

    /**
     * Отключить автообновление updated_at
     */
    public $timestamps = false;

    /**
     * Команда, в которую приглашают
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Пользователь, создавший приглашение
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    /**
     * Проверка, не истекла ли ссылка
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Проверка, достигнуто ли максимальное число использований
     */
    public function isLimitReached(): bool
    {
        if ($this->max_uses === null) {
            return false;
        }
        return $this->used_count >= $this->max_uses;
    }

    /**
     * Проверка валидности ссылки
     */
    public function isValid(): bool
    {
        return !$this->isExpired() && !$this->isLimitReached();
    }

    /**
     * Увеличить счётчик использований
     */
    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }

    /**
     * Scope для фильтрации по команде
     */
    public function scopeByTeam($query, int $teamId)
    {
        return $query->where('team_id', $teamId);
    }

    /**
     * Scope для фильтрации по клубу через команду
     */
    public function scopeByClub($query, int $clubId)
    {
        return $query->whereHas('team', function ($q) use ($clubId) {
            $q->where('club_id', $clubId);
        });
    }

    /**
     * Scope для валидных ссылок
     */
    public function scopeValid($query)
    {
        return $query->where('expires_at', '>', now())
            ->where(function ($q) {
                $q->whereNull('max_uses')
                  ->orWhereRaw('used_count < max_uses');
            });
    }
}
