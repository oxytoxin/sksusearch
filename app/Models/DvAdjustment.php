<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DvAdjustment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function disbursement_voucher()
    {
        return $this->belongsTo(DisbursementVoucher::class);
    }

    public function adjusted_by_user()
    {
        return $this->belongsTo(User::class, 'adjusted_by');
    }
}
