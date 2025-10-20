<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Casts\Attribute;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    /**
     * @mixin IdeHelperPettyCashFundRecord
     */
    class PettyCashFundRecord extends Model
    {
        use HasFactory;

        const REPLENISHMENT = 1;
        const DISBURSEMENT = 2;
        const REFUND = 3;
        const REIMBURSEMENT = 4;

        public function recordable()
        {
            return $this->morphTo();
        }

        public function petty_cash_fund()
        {
            return $this->belongsTo(PettyCashFund::class);
        }

    }
