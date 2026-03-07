<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Club extends Model
{
    protected $table = 'clubs';

    protected $fillable = [
        'name',
        'logo_file_id',
        'description',
        'club_type_id',
        'sport_type_id',
        'country_id',
        'city_id',
        'address',
        'email',
        'phones',
    ];

    protected $casts = [
        'phones'     => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ── Связи ────────────────────────────────────────────────────────────

    public function logoFile(): BelongsTo
    {
        return $this->belongsTo(File::class, 'logo_file_id');
    }

    public function clubType(): BelongsTo
    {
        return $this->belongsTo(RefClubType::class, 'club_type_id');
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

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class, 'club_id');
    }

    public function teamMembers(): HasMany
    {
        return $this->hasMany(TeamMember::class, 'club_id');
    }

    public function trainingTypes(): HasMany
    {
        return $this->hasMany(RefTrainingType::class, 'club_id');
    }

    public function venues(): HasMany
    {
        return $this->hasMany(Venue::class, 'club_id');
    }

    public function recurringTrainings(): HasMany
    {
        return $this->hasMany(RecurringTraining::class, 'club_id');
    }

    public function trainings(): HasMany
    {
        return $this->hasMany(Training::class, 'club_id');
    }
}
