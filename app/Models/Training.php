<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Training extends Model
{
    protected $table = 'trainings';

    protected $fillable = [
        'coach_id',
        'club_id',
        'team_id',
        'training_date',
        'start_time',
        'duration_minutes',
        'venue_id',
        'training_type_id',
        'status',
        'notify_parents',
        'require_rsvp',
        'comment',
        'recurring_id',
    ];

    protected $casts = [
        'training_date'    => 'date',
        'duration_minutes' => 'integer',
        'notify_parents'   => 'boolean',
        'require_rsvp'     => 'boolean',
        'created_at'       => 'datetime',
        'updated_at'       => 'datetime',
    ];

    // ── Связи ────────────────────────────────────────────────────────────

    public function coach(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class, 'club_id');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class, 'venue_id');
    }

    public function trainingType(): BelongsTo
    {
        return $this->belongsTo(RefTrainingType::class, 'training_type_id');
    }

    public function recurring(): BelongsTo
    {
        return $this->belongsTo(RecurringTraining::class, 'recurring_id');
    }

    public function attendance(): HasMany
    {
        return $this->hasMany(TrainingAttendance::class, 'training_id');
    }

    public function media(): HasMany
    {
        return $this->hasMany(TrainingMedia::class, 'training_id');
    }
}
