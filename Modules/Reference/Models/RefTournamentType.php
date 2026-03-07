<?php

namespace Modules\Reference\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Tournament\Models\Tournament;

class RefTournamentType extends Model
{
    protected $table = 'ref_tournament_types';
    public $timestamps = false;

    protected $fillable = ['name', 'sport_type_id'];

    public function sportType()
    {
        return $this->belongsTo(RefSportType::class, 'sport_type_id');
    }

    public function tournaments()
    {
        return $this->hasMany(Tournament::class, 'tournament_type_id');
    }
}
