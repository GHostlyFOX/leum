<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RefPosition extends Model
{
    protected $table = 'ref_position';

    protected $fillable = [
        'name',
        'ref_type_sport',
        'club',
    ];

    public function sport(): BelongsTo
    {
        return $this->belongsTo(RefTypeSport::class, 'ref_type_sport');
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class, 'club');
    }
}
