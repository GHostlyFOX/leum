<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RefTrainingType extends Model
{
    protected $table = 'ref_training_types';

    public $timestamps = false;

    protected $fillable = [
        'club_id',
        'name',
        'description',
    ];

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class, 'club_id');
    }
}
