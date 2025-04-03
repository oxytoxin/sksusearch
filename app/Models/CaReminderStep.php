<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaReminderStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'disbursement_voucher_id',
        'status',
        'voucher_end_date',
        'liquidation_period_end_date',
        'step',
        'is_sent',
        'title',
        'message',
    ];

    public function caReminderStepHistories()
    {
        return $this->hasMany(CaReminderStepHistory::class);
    }

    public function disbursementVoucher()
    {
        return $this->belongsTo(DisbursementVoucher::class);
    }
    public function disbursement_voucher()
    {
        return $this->belongsTo(DisbursementVoucher::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
