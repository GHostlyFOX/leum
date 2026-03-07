<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RefTournamentType extends Model
{
    protected $table = 'ref_tournament_types';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'sport_type_id',
    ];

    public function sportType(): BelongsTo
    {
        return $this->belongsTo(RefSportType::class, 'sport_type_id');
    }
}
