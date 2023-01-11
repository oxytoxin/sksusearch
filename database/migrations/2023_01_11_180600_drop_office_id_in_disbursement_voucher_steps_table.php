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
        // 44,47,51,19,64,65
        Schema::table('disbursement_voucher_steps', function (Blueprint $table) {
            $table->dropColumn('office_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('disbursement_voucher_steps', function (Blueprint $table) {
            //
        });
    }
};
