<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Reference\Models\RefPosition;
use Modules\Reference\Models\RefSportType;

class CoachProfile extends Model
{
    protected $table = 'coach_profiles';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'sport_type_id',
        'specialty_id',
        'career_start',
        'license_number',
        'license_expires',
        'achievements',
    ];

    protected $casts = [
        'career_start'    => 'date',
        'license_expires' => 'date',
        'achievements'    => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sportType()
    {
        return $this->belongsTo(RefSportType::class, 'sport_type_id');
    }

    public function specialty()
    {
        return $this->belongsTo(RefPosition::class, 'specialty_id');
    }

    public function achievementsList()
    {
        return $this->hasMany(CoachAchievement::class, 'coach_profile_id')->orderBy('year', 'desc');
    }
}
