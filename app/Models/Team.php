<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RefSex;
use App\Models\RefTypeSport;
use App\Models\RefRegion;
use App\Models\Club;

class Team extends Model
{
    protected $table = 'teams';

    protected $fillable = [
        'name',
        'description',
        'ref_sex',
        'logo',
        'kids_year',
        'club',
        'ref_type_sport',
        'country',
        'sity',
    ];

    public $timestamps = false;

    public function sex()
    {
        return $this->belongsTo(RefSex::class, 'ref_sex');
    }

    public function typeSport()
    {
        return $this->belongsTo(RefTypeSport::class, 'ref_type_sport');
    }

    public function countryRegion()
    {
        return $this->belongsTo(RefRegion::class, 'country');
    }

    public function cityRegion()
    {
        return $this->belongsTo(RefRegion::class, 'sity');
    }

    public function club()
    {
        return $this->belongsTo(Club::class, 'club');
    }
}
