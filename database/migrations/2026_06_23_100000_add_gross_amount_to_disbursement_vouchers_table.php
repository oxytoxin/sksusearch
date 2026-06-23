<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('disbursement_vouchers', function (Blueprint $table) {
            $table->decimal('gross_amount', 18, 2)->nullable()->after('payee');
        });

        // Backfill existing DVs with the current sum of their particulars
        DB::statement('
            UPDATE disbursement_vouchers dv
            SET dv.gross_amount = (
                SELECT COALESCE(SUM(dvp.amount), 0)
                FROM disbursement_voucher_particulars dvp
                WHERE dvp.disbursement_voucher_id = dv.id
            )
        ');
    }

    public function down()
    {
        Schema::table('disbursement_vouchers', function (Blueprint $table) {
            $table->dropColumn('gross_amount');
        });
    }
};
