<?php

namespace Modules\Training\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Club\Models\Club;
use Modules\Team\Models\Team;
use Modules\User\Models\User;

class Training extends Model
{
    protected $table = 'trainings';

    protected $fillable = [
        'coach_id', 'club_id', 'team_id', 'training_date',
        'start_time', 'duration_minutes', 'venue_id',
        'training_type_id', 'status', 'notify_parents',
        'require_rsvp', 'comment', 'recurring_id',
    ];

    protected $casts = [
        'training_date'   => 'date',
        'duration_minutes'=> 'integer',
        'notify_parents'  => 'boolean',
        'require_rsvp'    => 'boolean',
        'created_at'      => 'datetime',
        'updated_at'      => 'datetime',
    ];

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function club()
    {
        return $this->belongsTo(Club::class, 'club_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_id');
    }

    public function trainingType()
    {
        return $this->belongsTo(RefTrainingType::class, 'training_type_id');
    }

    public function recurring()
    {
        return $this->belongsTo(RecurringTraining::class, 'recurring_id');
    }

    public function attendance()
    {
        return $this->hasMany(TrainingAttendance::class, 'training_id');
    }

    public function media()
    {
        return $this->hasMany(TrainingMedia::class, 'training_id');
    }
}
