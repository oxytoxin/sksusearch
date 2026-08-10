<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IssuedPayment extends Model
{
    public const STATUS_ISSUED = 'issued';

    public const STATUS_CANCELLED = 'cancelled';

    public const SOURCE_CASHIER = 'cashier';

    public const SOURCE_LEGACY_DV = 'legacy_dv';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'issued_at' => 'immutable_date',
            'amount' => 'decimal:2',
            'affects_balance' => 'boolean',
            'cancelled_at' => 'immutable_datetime',
        ];
    }

    public function disbursement_voucher(): BelongsTo
    {
        return $this->belongsTo(DisbursementVoucher::class);
    }

    public function disbursementVoucher(): BelongsTo
    {
        return $this->disbursement_voucher();
    }

    public function mop(): BelongsTo
    {
        return $this->belongsTo(Mop::class);
    }

    public function bank_account(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function bankAccount(): BelongsTo
    {
        return $this->bank_account();
    }

    public function notice_of_cash_allocation(): BelongsTo
    {
        return $this->belongsTo(NoticeOfCashAllocation::class);
    }

    public function noticeOfCashAllocation(): BelongsTo
    {
        return $this->notice_of_cash_allocation();
    }

    public function check_stub(): BelongsTo
    {
        return $this->belongsTo(CheckStub::class);
    }

    public function checkStub(): BelongsTo
    {
        return $this->check_stub();
    }

    public function fund_cluster(): BelongsTo
    {
        return $this->belongsTo(FundCluster::class);
    }

    public function bank_account_transaction(): BelongsTo
    {
        return $this->belongsTo(BankAccountTransaction::class);
    }

    protected function chequeNumber(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->serial_number,
        );
    }

    protected function chequeNumberAddedAt(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->issued_at,
        );
    }

    protected function disbursementVoucherParticularsSumFinalAmount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->attributes['amount'] ?? null,
        );
    }

    protected function categoryItemBudget(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->disbursement_voucher?->category_item_budget,
        );
    }

    protected function voucherSubtype(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->disbursement_voucher?->voucher_subtype,
        );
    }
}
