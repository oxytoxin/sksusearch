<?php

namespace App\Models;

use Str;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @mixin IdeHelperDisbursementVoucher
 */
class DisbursementVoucher extends Model
{
    use HasFactory;

    protected $casts = [
        'closed_at' => 'immutable_date',
        'submitted_at' => 'immutable_date',
        'documents_verified_at' => 'immutable_datetime',
        'due_date' => 'immutable_date',
        'journal_date' => 'immutable_date',
        'certified_by_accountant' => 'boolean',
        'for_cancellation' => 'boolean',
        'cancelled_at' => 'immutable_datetime',
        'draft' => 'array',
        'related_documents' => 'array',
        'other_details' => 'array',
    ];

    protected function totalAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->disbursement_voucher_particulars->sum('amount'),
        );
    }

    protected function totalSum(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->disbursement_voucher_particulars->sum('final_amount') / 100,
            set: fn ($value) => $value * 100,
        );
    }

    public function petty_cash_fund_records()
    {
        return $this->morphMany(PettyCashFundRecord::class, 'recordable');
    }

    public static function generateTrackingNumber()
    {
        return 'dv-' . today()->format('y') . '-' . Str::random(8);
    }

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

    public function travel_order()
    {
        return $this->belongsTo(TravelOrder::class);
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

    public function disbursement_voucher_particulars()
    {
        return $this->hasMany(DisbursementVoucherParticular::class);
    }

    public function fund_cluster()
    {
        return $this->belongsTo(FundCluster::class);
    }

    public function activity_logs()
    {
        return $this->morphMany(ActivityLog::class, 'loggable');
    }

    public function scanned_documents()
    {
        return $this->morphMany(ScannedDocument::class, 'documentable');
    }

    public function liquidation_report()
    {
        return $this->hasOne(LiquidationReport::class);
    }

    public function travel_completed_certificate()
    {
        return $this->hasOne(TravelCompletedCertificate::class);
    }

    public function cash_advance_reminder()
    {
        return $this->hasOne(CaReminderStep::class);
    }

    public function totalSumDisbursementVoucherParticular()
    {
        return $this->disbursement_voucher_particulars->sum('final_amount');
    }

    public function daysOutstanding()
    {
        $cashAdvanceReminder = $this->cash_advance_reminder;
        if (!$cashAdvanceReminder) {
            return null;
        }
        $endDate = $cashAdvanceReminder->voucher_end_date;
        return $endDate ? Carbon::now()->diffInDays(Carbon::parse($endDate)) : null;
    }
}
