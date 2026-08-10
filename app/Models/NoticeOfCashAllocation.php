<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NoticeOfCashAllocation extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'allocated_at' => 'date',
            'expiry_date' => 'date',
            'posted_at' => 'immutable_datetime',
            'allocated_amount' => 'decimal:2',
            'remaining_amount' => 'decimal:2',
        ];
    }

    public function bank_account(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function bankAccount(): BelongsTo
    {
        return $this->bank_account();
    }

    public function fund_cluster_group(): BelongsTo
    {
        return $this->belongsTo(FundClusterGroup::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(NoticeOfCashAllocationTransaction::class);
    }

    public function issued_payments(): HasMany
    {
        return $this->hasMany(IssuedPayment::class);
    }

    public function posted_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'posted_by_id');
    }
}
