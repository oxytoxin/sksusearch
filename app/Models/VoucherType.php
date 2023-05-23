<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperVoucherType
 */
class VoucherType extends Model
{
    use HasFactory;

    public function voucher_category()
    {
        return $this->belongsTo(VoucherCategory::class);
    }

    public function voucher_subtypes()
    {
        return $this->hasMany(VoucherSubType::class);
    }
}
