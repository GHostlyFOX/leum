<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingAttendance extends Model
{
    protected $table = 'training_attendance';

    protected $fillable = [
        'training_id',
        'player_user_id',
        'marked_by_user_id',
        'attendance_status',
        'confirmed_at',
        'absence_reason',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    // ── Связи ────────────────────────────────────────────────────────────

    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class, 'training_id');
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(User::class, 'player_user_id');
    }

    public function markedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'marked_by_user_id');
    }
}
