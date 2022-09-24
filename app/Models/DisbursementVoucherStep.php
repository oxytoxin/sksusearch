<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisbursementVoucherStep extends Model
{
    use HasFactory;

    public function current_disbursement_vouchers()
    {
        return $this->hasMany(DisbursementVoucher::class, 'current_step_id');
    }

    public function previous_disbursement_vouchers()
    {
        return $this->hasMany(DisbursementVoucher::class, 'previous_step_id');
    }
}
