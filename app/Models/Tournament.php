<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tournament extends Model
{
    protected $table = 'tournaments';

    protected $fillable = [
        'tournament_type_id',
        'name',
        'logo_file_id',
        'starts_at',
        'ends_at',
        'half_duration_minutes',
        'halves_count',
        'organizer',
    ];

    protected $casts = [
        'starts_at'             => 'date',
        'ends_at'               => 'date',
        'half_duration_minutes' => 'integer',
        'halves_count'          => 'integer',
        'created_at'            => 'datetime',
        'updated_at'            => 'datetime',
    ];

    // ── Связи ────────────────────────────────────────────────────────────

    public function tournamentType(): BelongsTo
    {
        return $this->belongsTo(RefTournamentType::class, 'tournament_type_id');
    }

    public function logoFile(): BelongsTo
    {
        return $this->belongsTo(File::class, 'logo_file_id');
    }

    public function tournamentTeams(): HasMany
    {
        return $this->hasMany(TournamentTeam::class, 'tournament_id');
    }

    public function matches(): HasMany
    {
        return $this->hasMany(GameMatch::class, 'tournament_id');
    }
}
