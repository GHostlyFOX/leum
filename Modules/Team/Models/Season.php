<?php

namespace Modules\Team\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Club\Models\Club;
use Modules\Reference\Models\RefSportType;

/**
 * Модель спортивного сезона
 * 
 * @property int $id
 * @property string $name
 * @property int $club_id
 * @property int $sport_type_id
 * @property string $status
 * @property string $start_date
 * @property string $end_date
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Season extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'club_id',
        'sport_type_id',
        'status',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Клуб, которому принадлежит сезон
     */
    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    /**
     * Вид спорта сезона
     */
    public function sportType(): BelongsTo
    {
        return $this->belongsTo(RefSportType::class, 'sport_type_id');
    }

    /**
     * Команды, участвующие в сезоне
     */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'season_teams', 'season_id', 'team_id');
    }

    /**
     * Scope для фильтрации по клубу
     */
    public function scopeByClub($query, int $clubId)
    {
        return $query->where('club_id', $clubId);
    }

    /**
     * Scope для фильтрации по статусу
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope для фильтрации по году
     */
    public function scopeByYear($query, int $year)
    {
        return $query->whereYear('start_date', $year);
    }
}
