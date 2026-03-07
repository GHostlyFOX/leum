<?php

namespace Modules\File\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Club\Models\Club;
use Modules\Team\Models\Team;
use Modules\Tournament\Models\Tournament;
use Modules\Training\Models\TrainingMedia;
use Modules\User\Models\User;

class File extends Model
{
    protected $table = 'files';
    public $timestamps = false;

    protected $fillable = [
        'path',
        'mime_type',
        'size_bytes',
    ];

    protected $casts = [
        'size_bytes' => 'integer',
        'created_at' => 'datetime',
    ];

    // ── Inverse relationships ────────────────────────────────────

    public function userPhotos()
    {
        return $this->hasMany(User::class, 'photo_file_id');
    }

    public function clubLogos()
    {
        return $this->hasMany(Club::class, 'logo_file_id');
    }

    public function teamLogos()
    {
        return $this->hasMany(Team::class, 'logo_file_id');
    }

    public function tournamentLogos()
    {
        return $this->hasMany(Tournament::class, 'logo_file_id');
    }

    public function trainingMedia()
    {
        return $this->hasMany(TrainingMedia::class, 'file_id');
    }
}
