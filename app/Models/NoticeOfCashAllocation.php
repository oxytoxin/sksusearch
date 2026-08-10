<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NoticeOfCashAllocation extends Model
{
    public const STATUS_ACTIVE = 'active';

    public const STATUS_EXHAUSTED = 'exhausted';

    public const STATUS_CANCELLED = 'cancelled';

    public const STATUS_EXPIRED = 'expired';

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

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeForBankAccount(Builder $query, BankAccount|int $bankAccount): Builder
    {
        return $query->where('bank_account_id', $bankAccount instanceof BankAccount ? $bankAccount->getKey() : $bankAccount);
    }

    public function scopeWithAvailableBalance(Builder $query): Builder
    {
        return $query->where('remaining_amount', '>', 0);
    }

    public function scopeEligibleForBankAccount(Builder $query, BankAccount|int $bankAccount): Builder
    {
        return $query
            ->forBankAccount($bankAccount)
            ->active()
            ->withAvailableBalance();
    }

    public function hasAvailableBalance(float|string $amount): bool
    {
        return $this->status === self::STATUS_ACTIVE
            && round((float) $this->remaining_amount, 2) >= round((float) $amount, 2);
    }
}
