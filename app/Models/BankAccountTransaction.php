<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\MorphTo;

    class BankAccountTransaction extends Model
    {
        public const CATEGORY_NCA_ALLOCATION = 'nca_allocation';

        public const CATEGORY_BEGINNING_BALANCE = 'beginning_balance';

        public const CATEGORY_DEPOSIT = 'deposit';

        public const CATEGORY_FUND_TRANSFER = 'fund_transfer';

        public const CATEGORY_ERROR_CORRECTION = 'error_correction';

        public const CATEGORY_OTHERS = 'others';

        public const CATEGORY_REVERSION = 'reversion';

        public const CATEGORY_ISSUED_PAYMENT = 'issued_payment';

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

        public function bankAccount(): BelongsTo
        {
            return $this->belongsTo(BankAccount::class);
        }


        public function bank_account(): BelongsTo
        {
            return $this->bankAccount();
        }

        public function source(): MorphTo
        {
            return $this->morphTo();
        }

        public function posted_by(): BelongsTo
        {
            return $this->belongsTo(User::class, 'posted_by_id');
        }
    }
