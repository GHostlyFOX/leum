<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    // ── Связи ────────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sportType(): BelongsTo
    {
        return $this->belongsTo(RefSportType::class, 'sport_type_id');
    }

    public function specialty(): BelongsTo
    {
        return $this->belongsTo(RefPosition::class, 'specialty_id');
    }
}
