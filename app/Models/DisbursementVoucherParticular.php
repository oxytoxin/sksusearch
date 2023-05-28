<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperDisbursementVoucherParticular
 */
class DisbursementVoucherParticular extends Model
{
    use HasFactory;

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }

    protected function suggestedAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }

    protected function finalAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
        );
    }

    public function disbursement_voucher()
    {
        return $this->belongsTo(DisbursementVoucher::class);
    }
}
