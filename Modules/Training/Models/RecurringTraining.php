<?php

namespace Modules\Training\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Club\Models\Club;
use Modules\Team\Models\Team;

class RecurringTraining extends Model
{
    protected $table = 'recurring_trainings';

    protected $fillable = [
        'club_id', 'team_id', 'schedule', 'auto_create',
        'notify_parents', 'require_rsvp', 'is_active',
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

    public function club()
    {
        return $this->belongsTo(Club::class, 'club_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function trainings()
    {
        return $this->hasMany(Training::class, 'recurring_id');
    }
}
