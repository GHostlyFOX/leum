<?php

namespace Modules\Training\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\User\Models\User;

class TrainingAttendance extends Model
{
    protected $table = 'training_attendance';

    protected $fillable = [
        'training_id', 'player_user_id', 'marked_by_user_id',
        'attendance_status', 'confirmed_at', 'absence_reason',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    public function training()
    {
        return $this->belongsTo(Training::class, 'training_id');
    }

    public function player()
    {
        return $this->belongsTo(User::class, 'player_user_id');
    }

    public function markedBy()
    {
        return $this->belongsTo(User::class, 'marked_by_user_id');
    }
}
