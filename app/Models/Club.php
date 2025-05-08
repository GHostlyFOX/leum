<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Club extends Model
{
    protected $table = 'clubs';

    protected $fillable = [
        'name',
        'logo',
        'description',
        'ref_type_sport',
        'coutry',
        'sity',
        'address',
        'email',
        'phones',
        'ref_type_club',
    ];

    protected $casts = [
        'phones' => 'array',
    ];

    public function sport(): BelongsTo
    {
        return $this->belongsTo(RefTypeSport::class, 'ref_type_sport');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(RefRegion::class, 'coutry');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(RefRegion::class, 'sity');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(RefTypeClub::class, 'ref_type_club');
    }
}
