<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsToMany;
    use Illuminate\Database\Eloquent\Relations\HasMany;

    class FundClusterGroup extends Model
    {
        protected $guarded = [];

        public function fund_clusters(): HasMany
        {
            return $this->hasMany(FundCluster::class);
        }

        public function bank_accounts(): BelongsToMany
        {
            return $this->belongsToMany(BankAccount::class, 'bank_account_fund_cluster_group')
                ->withTimestamps();
        }
    }
