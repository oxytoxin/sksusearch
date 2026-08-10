<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\BelongsToMany;
    use Illuminate\Database\Eloquent\Relations\HasMany;

    class BankAccount extends Model
    {
        protected $guarded = [];

        protected function casts(): array
        {
            return [
                'balance' => 'decimal:2',
            ];
        }

        public function bank(): BelongsTo
        {
            return $this->belongsTo(Bank::class);
        }

        public function fund_cluster_groups(): BelongsToMany
        {
            return $this->belongsToMany(FundClusterGroup::class, 'bank_account_fund_cluster_group')
                ->withTimestamps();
        }


        public function transactions(): HasMany
        {
            return $this->hasMany(BankAccountTransaction::class);
        }

        public function notice_of_cash_allocations(): HasMany
        {
            return $this->hasMany(NoticeOfCashAllocation::class);
        }

        public function check_stubs(): HasMany
        {
            return $this->hasMany(CheckStub::class);
        }

        public function issued_payments(): HasMany
        {
            return $this->hasMany(IssuedPayment::class);
        }
    }
