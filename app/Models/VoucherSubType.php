<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperVoucherSubType
 */
class VoucherSubType extends Model
{
    use HasFactory;

    const TRAVELS = [1, 2, 6, 7];

    public function voucher_type()
    {
        return $this->belongsTo(VoucherType::class);
    }

    public function related_documents_list()
    {
        return $this->hasOne(RelatedDocumentsList::class);
    }
}
