<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CheckStub extends Model
{
    public const STATUS_ACTIVE = 'active';

    public const STATUS_EXHAUSTED = 'exhausted';

    public const STATUS_VOID = 'void';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'exhausted_at' => 'immutable_datetime',
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

    public function registered_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by_id');
    }

    public function issued_payments(): HasMany
    {
        return $this->hasMany(IssuedPayment::class);
    }

    public function hasBeenConsumed(): bool
    {
        return (int) $this->consumed_count > 0;
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeForBankAccount(Builder $query, BankAccount|int $bankAccount): Builder
    {
        return $query->where('bank_account_id', $bankAccount instanceof BankAccount ? $bankAccount->getKey() : $bankAccount);
    }

    public function hasAvailableChecks(): bool
    {
        return $this->status === self::STATUS_ACTIVE
            && (int) $this->next_number <= (int) $this->end_number;
    }

    public function nextSerialNumber(): string
    {
        $number = (string) $this->next_number;

        if ((int) $this->number_padding > 0) {
            $number = str_pad($number, (int) $this->number_padding, '0', STR_PAD_LEFT);
        }

        return ($this->prefix ?? '').$number;
    }

    public function consumeNextNumber(): string
    {
        $serialNumber = $this->nextSerialNumber();

        $this->next_number = (int) $this->next_number + 1;
        $this->consumed_count = (int) $this->consumed_count + 1;

        if ((int) $this->next_number > (int) $this->end_number) {
            $this->status = self::STATUS_EXHAUSTED;
            $this->exhausted_at ??= now();
        }

        return $serialNumber;
    }
}
