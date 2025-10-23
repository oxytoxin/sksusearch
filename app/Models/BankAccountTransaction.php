<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;

    class BankAccountTransaction extends Model
    {
        public function bankAccount(): BelongsTo
        {
            return $this->belongsTo(BankAccount::class);
        }
    }
