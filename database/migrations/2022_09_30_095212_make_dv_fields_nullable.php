<?php

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
        Schema::table('disbursement_vouchers', function (Blueprint $table) {
            $table->foreignId('mop_id')->nullable()->after('certified_by_accountant')->change();
        });
        Schema::table('disbursement_voucher_particulars', function (Blueprint $table) {
            $table->string('responsibility_center', 255)->nullable()->after('amount')->change();
            $table->string('mfo_pap', 255)->nullable()->after('responsibility_center')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
