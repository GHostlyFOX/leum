<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RefPosition extends Model
{
    protected $table = 'ref_positions';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'sport_type_id',
    ];

    // ── Связи ────────────────────────────────────────────────────────────

    public function sportType(): BelongsTo
    {
        return $this->belongsTo(RefSportType::class, 'sport_type_id');
    }
}
