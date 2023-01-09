<?php

use App\Models\DisbursementVoucherParticular;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('disbursement_voucher_particulars', function (Blueprint $table) {
            $table->integer('suggested_amount')->nullable()->default(null)->after('amount')->change();
            $table->integer('final_amount')->virtualAs('IF(ISNULL(suggested_amount), amount, suggested_amount)')->after('suggested_amount');
        });
        DisbursementVoucherParticular::whereSuggestedAmount(0)->update(['suggested_amount' => null]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('disbursement_voucher_particulars', function (Blueprint $table) {
            $table->dropColumn('final_amount');
        });
    }
};
