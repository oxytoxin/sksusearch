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


        protected function finalAmount(): Attribute
        {
            return Attribute::make(
                get: fn($value) => $value,
            );
        }

        public function disbursement_voucher()
        {
            return $this->belongsTo(DisbursementVoucher::class);
        }
    }
