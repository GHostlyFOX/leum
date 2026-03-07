<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Reference\Models\RefKinshipType;

class UserParentPlayer extends Model
{
    protected $table = 'user_parent_player';
    public $timestamps = false;

    protected $fillable = [
        'parent_user_id',
        'player_user_id',
        'kinship_type_id',
    ];

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_user_id');
    }

    public function player()
    {
        return $this->belongsTo(User::class, 'player_user_id');
    }

    public function kinshipType()
    {
        return $this->belongsTo(RefKinshipType::class, 'kinship_type_id');
    }
}
