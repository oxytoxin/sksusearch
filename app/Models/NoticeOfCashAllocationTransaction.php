<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NoticeOfCashAllocationTransaction extends Model
{
    public const TYPE_ALLOCATION = 'allocation';

    public const TYPE_BEGINNING_BALANCE = 'beginning_balance';

    public const TYPE_ISSUED_PAYMENT = 'issued_payment';

    public const TYPE_ERROR_CORRECTION = 'error_correction';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'transacted_at' => 'immutable_date',
            'amount' => 'decimal:2',
            'balance_change' => 'decimal:2',
            'resulting_balance' => 'decimal:2',
        ];
    }

    public function notice_of_cash_allocation(): BelongsTo
    {
        return $this->belongsTo(NoticeOfCashAllocation::class);
    }

    public function noticeOfCashAllocation(): BelongsTo
    {
        return $this->notice_of_cash_allocation();
    }

    public function bank_account_transaction(): BelongsTo
    {
        return $this->belongsTo(BankAccountTransaction::class);
    }

    public function issued_payment(): BelongsTo
    {
        return $this->belongsTo(IssuedPayment::class);
    }

    public function posted_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'posted_by_id');
    }
}
