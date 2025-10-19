<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Casts\Attribute;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    /**
     * @mixin IdeHelperPettyCashFund
     */
    class PettyCashFund extends Model
    {
        use HasFactory;

        public function campus()
        {
            return $this->belongsTo(Campus::class);
        }

        public function petty_cash_fund_records()
        {
            return $this->hasMany(PettyCashFundRecord::class);
        }

        public function latest_petty_cash_fund_record()
        {
            return $this->hasOne(PettyCashFundRecord::class)->latestOfMany();
        }

        public function custodian()
        {
            return $this->belongsTo(User::class, 'custodian_id');
        }
    }
