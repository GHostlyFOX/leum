<?php

namespace Modules\Reference\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Модель групп типов событий матча
 * 
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string|null $icon
 * @property string|null $color
 * @property int $sort_order
 * @property int|null $sport_type_id
 * @property-read RefSportType|null $sportType
 * @property-read \Illuminate\Database\Eloquent\Collection|RefMatchEventType[] $eventTypes
 */
class RefMatchEventGroup extends Model
{
    protected $table = 'ref_match_event_groups';
    
    public $timestamps = false;

    protected $fillable = [
        'name',
        'code',
        'icon',
        'color',
        'sort_order',
        'sport_type_id',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    /**
     * Вид спорта, к которому привязана группа
     */
    public function sportType(): BelongsTo
    {
        return $this->belongsTo(RefSportType::class, 'sport_type_id');
    }

    /**
     * Типы событий в группе
     */
    public function eventTypes(): HasMany
    {
        return $this->hasMany(RefMatchEventType::class, 'group_id');
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
     * Scope для общих групп (не привязанных к виду спорта)
     */
    public function scopeCommon($query)
    {
        return $query->whereNull('sport_type_id');
    }

    /**
     * Scope для специфичных групп (привязанных к виду спорта)
     */
    public function scopeSpecific($query)
    {
        return $query->whereNotNull('sport_type_id');
    }
}
