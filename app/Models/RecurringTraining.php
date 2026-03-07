<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RecurringTraining extends Model
{
    protected $table = 'recurring_trainings';

    protected $fillable = [
        'club_id',
        'team_id',
        'schedule',
        'auto_create',
        'notify_parents',
        'require_rsvp',
        'is_active',
    ];

    protected $casts = [
        'schedule'       => 'array',
        'auto_create'    => 'array',
        'notify_parents' => 'boolean',
        'require_rsvp'   => 'boolean',
        'is_active'      => 'boolean',
        'created_at'     => 'datetime',
        'updated_at'     => 'datetime',
    ];

    // ── Связи ────────────────────────────────────────────────────────────

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class, 'club_id');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function trainings(): HasMany
    {
        return $this->hasMany(Training::class, 'recurring_id');
    }
}
