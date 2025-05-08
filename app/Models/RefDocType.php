<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefDocType extends Model
{
    protected $table = 'ref_doc_type';

    protected $fillable = [
        'name',
    ];

    public $timestamps = true; // Включено, так как есть timestamps в миграции
}
