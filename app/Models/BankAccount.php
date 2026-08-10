<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\BelongsToMany;

    class BankAccount extends Model
    {
        public function bank(): BelongsTo
        {
            return $this->belongsTo(Bank::class);
        }

        public function fund_cluster_groups(): BelongsToMany
        {
            return $this->belongsToMany(FundClusterGroup::class, 'bank_account_fund_cluster_group')
                ->withTimestamps();
        }
    }
