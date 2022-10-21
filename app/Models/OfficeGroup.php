<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeGroup extends Model
{
    use HasFactory;

    public function disbursement_voucher_starting_step()
    {
        return $this->hasOne(DisbursementVoucherStep::class)->ofMany('id', 'MIN');
    }

    public function disbursement_voucher_final_step()
    {
        return $this->hasOne(DisbursementVoucherStep::class)->ofMany('id', 'MAX');
    }
}
