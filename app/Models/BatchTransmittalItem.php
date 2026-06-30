<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchTransmittalItem extends Model
{
    protected $guarded = [];

    public function batch_transmittal()
    {
        return $this->belongsTo(BatchTransmittal::class);
    }

    public function disbursement_voucher()
    {
        return $this->belongsTo(DisbursementVoucher::class);
    }

    public function liquidation_report()
    {
        return $this->belongsTo(LiquidationReport::class);
    }
}
