<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\User\Models\User;

class ActivityLog extends Model
{
    protected $table = 'activity_log';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action',
        'entity_type',
        'entity_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Логирование действия
     */
    public static function log(
        string $action,
        ?Model $entity = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): void {
        $user = auth()->user();

        self::create([
            'user_id' => $user?->id,
            'action' => $action,
            'entity_type' => $entity ? get_class($entity) : null,
            'entity_id' => $entity?->id,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }

    /**
     * Получить описание действия на русском
     */
    public function getActionLabel(): string
    {
        return match ($this->action) {
            'create' => 'Создание',
            'update' => 'Изменение',
            'delete' => 'Удаление',
            'login' => 'Вход',
            'logout' => 'Выход',
            'export' => 'Экспорт',
            'import' => 'Импорт',
            default => $this->action,
        };
    }

    /**
     * Получить название сущности
     */
    public function getEntityLabel(): string
    {
        return match (class_basename($this->entity_type)) {
            'User' => 'Пользователь',
            'Team' => 'Команда',
            'Club' => 'Клуб',
            'Training' => 'Тренировка',
            'GameMatch' => 'Матч',
            'Tournament' => 'Турнир',
            default => class_basename($this->entity_type),
        };
    }
}
