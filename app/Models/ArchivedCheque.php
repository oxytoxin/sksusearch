<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperArchivedCheque
 */
class ArchivedCheque extends Model
{
    
    use HasFactory;
    protected $casts = [
        'journal_date' => 'immutable_date',
        'upload_date' => 'immutable_date',
        'cheque_date' => 'immutable_date',
        'particulars' => 'array',
        'other_details' => 'array',
    ];
    protected function chequeAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }
    public function scanned_documents()
    {
        return $this->morphMany(ScannedDocument::class, 'documentable');
    }
}
