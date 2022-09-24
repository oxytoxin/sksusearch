<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashAdvance extends Model
{
    use HasFactory;

    protected $casts = [
        'due_date' => 'immutable_date',
    ];

    public function travel_order()
    {
        return $this->belongsTo(TravelOrder::class);
    }

    public function disbursement_voucher()
    {
        return $this->belongsTo(DisbursementVoucher::class);
    }
}
