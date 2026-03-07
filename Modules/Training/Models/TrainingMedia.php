<?php

namespace Modules\Training\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\File\Models\File;

class TrainingMedia extends Model
{
    protected $table = 'training_media';
    public $timestamps = false;

    protected $fillable = [
        'training_id', 'file_id', 'sort_order',
    ];

    protected $casts = [
        'sort_order'  => 'integer',
        'created_at'  => 'datetime',
    ];

    public function training()
    {
        return $this->belongsTo(Training::class, 'training_id');
    }

    public function file()
    {
        return $this->belongsTo(File::class, 'file_id');
    }
}
