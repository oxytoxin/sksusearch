<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperOfficeGroup
 */
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

    public function disbursement_voucher_steps()
    {
        return $this->hasMany(DisbursementVoucherStep::class);
    }

    public function liquidation_report_starting_step()
    {
        return $this->hasOne(LiquidationReportStep::class)->ofMany('id', 'MIN');
    }

    public function liquidation_report_final_step()
    {
        return $this->hasOne(LiquidationReportStep::class)->ofMany('id', 'MAX');
    }

    public function liquidation_report_steps()
    {
        return $this->hasMany(LiquidationReportStep::class);
    }
}
