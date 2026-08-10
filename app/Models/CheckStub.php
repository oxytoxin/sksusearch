<?php

namespace App\Models;

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
}
