<?php

namespace Modules\Reference\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Match\Models\MatchEvent;

/**
 * Модель типов событий матча
 * 
 * @property int $id
 * @property string $name
 * @property string $code
 * @property int $group_id
 * @property int|null $sport_type_id
 * @property int $sort_order
 * @property bool $is_statistical
 * @property bool $affects_score
 * @property string|null $icon
 * @property string|null $color
 * @property-read RefMatchEventGroup $group
 * @property-read RefSportType|null $sportType
 * @property-read \Illuminate\Database\Eloquent\Collection|MatchEvent[] $matchEvents
 */
class RefMatchEventType extends Model
{
    protected $table = 'ref_match_event_types';
    
    public $timestamps = false;

    protected $fillable = [
        'name',
        'code',
        'group_id',
        'sport_type_id',
        'sort_order',
        'is_statistical',
        'affects_score',
        'icon',
        'color',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_statistical' => 'boolean',
        'affects_score' => 'boolean',
    ];

    /**
     * Группа события
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(RefMatchEventGroup::class, 'group_id');
    }

    /**
     * Вид спорта, к которому привязан тип события
     */
    public function sportType(): BelongsTo
    {
        return $this->belongsTo(RefSportType::class, 'sport_type_id');
    }

    /**
     * События матча данного типа
     */
    public function matchEvents(): HasMany
    {
        return $this->hasMany(MatchEvent::class, 'event_type_id');
    }

    /**
     * Scope для фильтрации по виду спорта
     */
    public function scopeForSport($query, ?int $sportTypeId)
    {
        return $query->where(function ($q) use ($sportTypeId) {
            $q->whereNull('sport_type_id')
              ->orWhere('sport_type_id', $sportTypeId);
        });
    }

    /**
     * Scope для событий, влияющих на счёт
     */
    public function scopeAffectsScore($query)
    {
        return $query->where('affects_score', true);
    }

    /**
     * Scope для статистических событий
     */
    public function scopeStatistical($query)
    {
        return $query->where('is_statistical', true);
    }

    /**
     * Scope для фильтрации по группе
     */
    public function scopeByGroup($query, string $groupCode)
    {
        return $query->whereHas('group', function ($q) use ($groupCode) {
            $q->where('code', $groupCode);
        });
    }
}
