<?php

use App\Models\DisbursementVoucher;
use App\Models\PettyCashFundRecord;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('petty_cash_fund_records')
            ->where('recordable_type', DisbursementVoucher::class)
            ->where('type', PettyCashFundRecord::REPLENISHMENT)
            ->update(['recordable_type' => 'dv']);
    }
};
