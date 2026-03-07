<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingMedia extends Model
{
    protected $table = 'training_media';

    public $timestamps = false;

    protected $fillable = [
        'training_id',
        'file_id',
        'sort_order',
    ];

    protected $casts = [
        'sort_order'  => 'integer',
        'created_at'  => 'datetime',
    ];

    // ── Связи ────────────────────────────────────────────────────────────

    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class, 'training_id');
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class, 'file_id');
    }
}
