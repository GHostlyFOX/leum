<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamMember extends Model
{
    protected $table = 'team_members';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'club_id',
        'team_id',
        'role_id',
        'joined_at',
        'is_active',
    ];

    protected $casts = [
        'joined_at' => 'date',
        'is_active' => 'boolean',
    ];

    // ── Связи ────────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class, 'club_id');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(RefUserRole::class, 'role_id');
    }
}
