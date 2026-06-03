<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Redefine the final_amount generated column as decimal(18,2).
     *
     * It was left as an integer when amount/suggested_amount were converted
     * to decimal(18,2), which truncated the centavos from the computed value.
     */
    public function up(): void
    {
        Schema::table('disbursement_voucher_particulars', function (Blueprint $table) {
            $table->dropColumn('final_amount');
        });

        Schema::table('disbursement_voucher_particulars', function (Blueprint $table) {
            $table->decimal('final_amount', 18, 2)
                ->virtualAs('IF(ISNULL(suggested_amount), amount, suggested_amount)')
                ->after('suggested_amount');
        });
    }

    public function down(): void
    {
        Schema::table('disbursement_voucher_particulars', function (Blueprint $table) {
            $table->dropColumn('final_amount');
        });

        Schema::table('disbursement_voucher_particulars', function (Blueprint $table) {
            $table->integer('final_amount')
                ->virtualAs('IF(ISNULL(suggested_amount), amount, suggested_amount)')
                ->after('suggested_amount');
        });
    }
};
