<?php

namespace Modules\Training\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Club\Models\Club;

class RefTrainingType extends Model
{
    protected $table = 'ref_training_types';
    public $timestamps = false;

    protected $fillable = ['club_id', 'name', 'description'];

    public function club()
    {
        return $this->belongsTo(Club::class, 'club_id');
    }

    public function trainings()
    {
        return $this->hasMany(Training::class, 'training_type_id');
    }
}
