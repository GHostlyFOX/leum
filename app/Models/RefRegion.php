<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefRegion extends Model
{
    protected $table = 'ref_regions';

    protected $fillable = [
        'name',
        'type',
    ];

    public $timestamps = false;

    /**
     * Типы регионов:
     * 1 - Страна
     * 2 - Город
     */
    public const TYPE_COUNTRY = 1;
    public const TYPE_CITY = 2;
}
