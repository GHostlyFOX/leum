<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompositionTeam extends Model
{
    protected $table = 'composition_teams';

    protected $fillable = [
        'user',
        'club',
        'teams',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user');
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class, 'club');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'teams');
    }
}
