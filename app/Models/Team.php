<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    protected $table = 'teams';

    protected $fillable = [
        'name',
        'description',
        'gender',
        'logo_file_id',
        'birth_year',
        'club_id',
        'sport_type_id',
        'country_id',
        'city_id',
    ];

    protected $casts = [
        'birth_year' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ── Связи ────────────────────────────────────────────────────────────

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class, 'club_id');
    }

    public function logoFile(): BelongsTo
    {
        return $this->belongsTo(File::class, 'logo_file_id');
    }

    public function sportType(): BelongsTo
    {
        return $this->belongsTo(RefSportType::class, 'sport_type_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(TeamMember::class, 'team_id');
    }

    public function trainings(): HasMany
    {
        return $this->hasMany(Training::class, 'team_id');
    }

    public function recurringTrainings(): HasMany
    {
        return $this->hasMany(RecurringTraining::class, 'team_id');
    }

    public function matches(): HasMany
    {
        return $this->hasMany(GameMatch::class, 'team_id');
    }
}
