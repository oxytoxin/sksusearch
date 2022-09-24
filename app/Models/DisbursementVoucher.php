<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisbursementVoucher extends Model
{
    use HasFactory;

    protected $casts = [
        'closed_date' => 'immutable_date',
        'submitted_date' => 'immutable_date',
        'draft' => 'array',
    ];

    public function voucher_subtype()
    {
        return $this->belongsTo(VoucherSubType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function signatory()
    {
        return $this->belongsTo(User::class, 'signatory_id');
    }

    public function mop()
    {
        return $this->belongsTo(Mop::class);
    }

    public function current_step()
    {
        return $this->belongsTo(DisbursementVoucherStep::class, 'current_step_id');
    }

    public function previous_step()
    {
        return $this->belongsTo(DisbursementVoucherStep::class, 'previous_step_id');
    }

    public function cash_advance()
    {
        return $this->hasOne(CashAdvance::class);
    }

    public function disbursement_voucher_particulars()
    {
        return $this->hasMany(DisbursementVoucherParticular::class);
    }
}
