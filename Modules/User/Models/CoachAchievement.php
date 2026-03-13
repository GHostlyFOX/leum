<?php

declare(strict_types=1);

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Model;

class CoachAchievement extends Model
{
    protected $table = 'coach_achievements';
    
    protected $fillable = [
        'coach_profile_id',
        'title',
        'description',
        'year',
        'category',
    ];

    protected $casts = [
        'year' => 'integer',
    ];

    public function coachProfile()
    {
        return $this->belongsTo(CoachProfile::class, 'coach_profile_id');
    }
}
