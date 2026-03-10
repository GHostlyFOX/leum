<?php

namespace Modules\Training\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Models\User;

/**
 * Модель отклика на событие (RSVP)
 * 
 * Полиморфная модель для тренировок, матчей и турниров
 * 
 * @property int $id
 * @property string $event_type
 * @property int $event_id
 * @property int $user_id
 * @property string $status
 * @property string|null $comment
 * @property \Carbon\Carbon|null $responded_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class EventResponse extends Model
{
    use HasFactory;

    protected $table = 'event_responses';

    protected $fillable = [
        'event_type',
        'event_id',
        'user_id',
        'status',
        'comment',
        'responded_at',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
    ];

    /**
     * Пользователь, давший ответ
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Полиморфное отношение к событию
     */
    public function event()
    {
        // Динамическое определение модели на основе event_type
        $modelClass = match ($this->event_type) {
            'training' => Training::class,
            'match' => \Modules\Match\Models\GameMatch::class,
            'tournament' => \Modules\Tournament\Models\Tournament::class,
            default => null,
        };

        if ($modelClass === null) {
            return null;
        }

        return $this->belongsTo($modelClass, 'event_id');
    }

    /**
     * Обновить время ответа
     */
    public function touchRespondedAt(): void
    {
        $this->update(['responded_at' => now()]);
    }

    /**
     * Scope для фильтрации по типу события
     */
    public function scopeByEventType($query, string $eventType)
    {
        return $query->where('event_type', $eventType);
    }

    /**
     * Scope для фильтрации по ID события
     */
    public function scopeByEventId($query, int $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    /**
     * Scope для фильтрации по событию (тип + ID)
     */
    public function scopeForEvent($query, string $eventType, int $eventId)
    {
        return $query->where('event_type', $eventType)
            ->where('event_id', $eventId);
    }

    /**
     * Scope для фильтрации по пользователю
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope для фильтрации по статусу
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope для подтверждённых (yes)
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'yes');
    }

    /**
     * Получить или создать отклик
     */
    public static function getOrCreate(string $eventType, int $eventId, int $userId): self
    {
        return static::firstOrCreate(
            [
                'event_type' => $eventType,
                'event_id' => $eventId,
                'user_id' => $userId,
            ],
            [
                'status' => 'pending',
            ]
        );
    }

    /**
     * Обновить статус отклика
     */
    public function updateStatus(string $status, ?string $comment = null): void
    {
        $data = ['status' => $status, 'responded_at' => now()];
        
        if ($comment !== null) {
            $data['comment'] = $comment;
        }

        $this->update($data);
    }

    /**
     * Получить сводку по откликам
     */
    public static function getSummary(string $eventType, int $eventId): array
    {
        $counts = static::forEvent($eventType, $eventId)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return [
            'yes' => $counts['yes'] ?? 0,
            'no' => $counts['no'] ?? 0,
            'maybe' => $counts['maybe'] ?? 0,
            'pending' => $counts['pending'] ?? 0,
            'total' => array_sum($counts),
        ];
    }
}
