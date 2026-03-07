<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Reference\Models\RefDominantFoot;
use Modules\Reference\Models\RefPosition;
use Modules\Reference\Models\RefSportType;

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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function dominantFoot()
    {
        return $this->belongsTo(RefDominantFoot::class, 'dominant_foot_id');
    }

    public function position()
    {
        return $this->belongsTo(RefPosition::class, 'position_id');
    }

    public function sportType()
    {
        return $this->belongsTo(RefSportType::class, 'sport_type_id');
    }
}
