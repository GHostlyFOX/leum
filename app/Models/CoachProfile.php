<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoachProfile extends Model
{
    protected $table = 'coach_profiles';

    protected $fillable = [
        'user',
        'ref_position',
        'date_begin',
        'license',
        'date_end_license',
        'awards',
        'ref_type_sport',
    ];

    protected $casts = [
        'date_begin' => 'date',
        'date_end_license' => 'date',
        'awards' => 'array',
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
