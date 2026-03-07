<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserParentPlayer extends Model
{
    protected $table = 'user_parent_player';

    public $timestamps = false;

    protected $fillable = [
        'parent_user_id',
        'player_user_id',
        'kinship_type_id',
    ];

    // ── Связи ────────────────────────────────────────────────────────────

    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_user_id');
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(User::class, 'player_user_id');
    }

    public function kinshipType(): BelongsTo
    {
        return $this->belongsTo(RefKinshipType::class, 'kinship_type_id');
    }
}
