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
        Schema::table('fund_allocation_batches', function (Blueprint $table) {
            $table->integer('prev_total_balance')->default(0);
            $table->integer('prev_total_programmed')->default(0);
            $table->integer('current_total_programmed')->default(0);
            $table->integer('current_total_allocation')->default(0);
            $table->integer('overall_total_allocation')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fund_allocation_batches', function (Blueprint $table) {
            //
        });
    }
};
