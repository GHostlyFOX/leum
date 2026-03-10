<?php

namespace Modules\Training\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Club\Models\Club;
use Modules\Team\Models\Team;
use Modules\User\Models\User;

/**
 * Модель объявления
 * 
 * @property int $id
 * @property string $title
 * @property string $message
 * @property string $priority
 * @property int|null $team_id
 * @property int $club_id
 * @property int $author_id
 * @property \Carbon\Carbon|null $published_at
 * @property \Carbon\Carbon|null $expires_at
 * @property bool $is_draft
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'priority',
        'team_id',
        'club_id',
        'author_id',
        'published_at',
        'expires_at',
        'is_draft',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_draft' => 'boolean',
    ];

    /**
     * Команда-адресат (null = для всего клуба)
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Клуб-владелец
     */
    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    /**
     * Автор объявления
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Проверка, опубликовано ли объявление
     */
    public function isPublished(): bool
    {
        return !$this->is_draft && $this->published_at !== null;
    }

    /**
     * Проверка, не истекло ли объявление
     */
    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    /**
     * Проверка активности объявления
     */
    public function isActive(): bool
    {
        return $this->isPublished() && !$this->isExpired();
    }

    /**
     * Опубликовать объявление
     */
    public function publish(): void
    {
        $this->update([
            'is_draft' => false,
            'published_at' => now(),
        ]);
    }

    /**
     * Scope для фильтрации по клубу
     */
    public function scopeByClub($query, int $clubId)
    {
        return $query->where('club_id', $clubId);
    }

    /**
     * Scope для фильтрации по команде
     */
    public function scopeByTeam($query, ?int $teamId)
    {
        if ($teamId === null) {
            return $query->whereNull('team_id');
        }
        return $query->where('team_id', $teamId);
    }

    /**
     * Scope для черновиков
     */
    public function scopeDrafts($query)
    {
        return $query->where('is_draft', true);
    }

    /**
     * Scope для опубликованных
     */
    public function scopePublished($query)
    {
        return $query->where('is_draft', false)
            ->whereNotNull('published_at');
    }

    /**
     * Scope для активных (опубликованных и не истекших)
     */
    public function scopeActive($query)
    {
        return $query->published()
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Scope для фильтрации по приоритету
     */
    public function scopeByPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }
}
