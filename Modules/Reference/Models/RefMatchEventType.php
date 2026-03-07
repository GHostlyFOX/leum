<?php

namespace Modules\Reference\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Match\Models\MatchEvent;

class RefMatchEventType extends Model
{
    protected $table = 'ref_match_event_types';
    public $timestamps = false;

    protected $fillable = ['name'];

    public function matchEvents()
    {
        return $this->hasMany(MatchEvent::class, 'event_type_id');
    }
}
