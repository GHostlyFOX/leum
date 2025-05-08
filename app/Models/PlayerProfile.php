<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerProfile extends Model
{
    protected $table = 'player_profiles';

    protected $fillable = [
        'user',
        'leg',
        'ref_position',
        'ref_type_sport',
        'address',
    ];

    protected $casts = [
        'leg' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user');
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(RefPosition::class, 'ref_position');
    }

    public function sport(): BelongsTo
    {
        return $this->belongsTo(RefTypeSport::class, 'ref_type_sport');
    }
}
