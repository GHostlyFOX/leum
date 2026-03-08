<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Modules\User\Models\User;

class RefreshToken extends Model
{
    protected $table = 'refresh_tokens';
    public $timestamps = false;

    protected $fillable = ['user_id', 'token', 'expires_at', 'created_at'];

    protected $casts = [
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    /** Срок жизни refresh-токена: 30 дней */
    public const LIFETIME_DAYS = 30;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Создать новый refresh-токен для пользователя.
     */
    public static function createForUser(int $userId): self
    {
        return static::create([
            'user_id'    => $userId,
            'token'      => Str::random(64),
            'expires_at' => now()->addDays(self::LIFETIME_DAYS),
            'created_at' => now(),
        ]);
    }

    /**
     * Удалить все токены пользователя (полный выход).
     */
    public static function revokeAll(int $userId): void
    {
        static::where('user_id', $userId)->delete();
    }
}
