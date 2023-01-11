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
        Schema::table('liquidation_report_steps', function (Blueprint $table) {
            $table->boolean('enabled')->default(true)->after('office_group_id');
        });

        Schema::table('disbursement_voucher_steps', function (Blueprint $table) {
            $table->boolean('enabled')->default(true)->after('office_group_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('steps_tables', function (Blueprint $table) {
            //
        });
    }
};
