<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerProfile extends Model
{
    protected $table = 'player_profiles';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'dominant_foot_id',
        'position_id',
        'sport_type_id',
    ];

    // ── Связи ────────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function dominantFoot(): BelongsTo
    {
        return $this->belongsTo(RefDominantFoot::class, 'dominant_foot_id');
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(RefPosition::class, 'position_id');
    }

    public function sportType(): BelongsTo
    {
        return $this->belongsTo(RefSportType::class, 'sport_type_id');
    }
}
