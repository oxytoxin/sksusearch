<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\HasMany;

    class Bank extends Model
    {
        public function bank_accounts(): HasMany
        {
            return $this->hasMany(BankAccount::class);
        }
    }
