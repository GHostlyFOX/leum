<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    protected $table = 'documents';

    protected $fillable = [
        'ref_doc_type',
        'user',
        'organization_name',
        'date_begin',
        'date_end',
        'seria_number',
        'citizenship',
        'photo_docs',
        'is_allowed',
    ];

    protected $casts = [
        'date_begin' => 'date',
        'date_end' => 'date',
        'is_allowed' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user');
    }

    public function citizenshipRegion(): BelongsTo
    {
        return $this->belongsTo(RefRegion::class, 'citizenship');
    }

    // Можно добавить связь с типом документа, если есть соответствующая модель
    public function docType(): BelongsTo
    {
        return $this->belongsTo(RefDocType::class, 'ref_doc_type');
    }
}
