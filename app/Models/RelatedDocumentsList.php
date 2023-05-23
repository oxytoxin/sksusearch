<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperRelatedDocumentsList
 */
class RelatedDocumentsList extends Model
{
    use HasFactory;

    protected $casts = [
        'documents' => 'array',
        'liquidation_report_documents' => 'array',
    ];

    public function voucher_sub_type()
    {
        return $this->belongsTo(VoucherSubType::class);
    }
}
