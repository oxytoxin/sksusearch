<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegacyDocument extends Model
{
    use HasFactory;
    protected $casts = [
        'journal_date' => 'immutable_date',
        'upload_date' => 'immutable_date',
        'particulars' => 'array',
        'other_details' => 'array',
    ];
    public function scanned_documents()
    {
        return $this->morphMany(ScannedDocument::class, 'documentable');
    }
}
