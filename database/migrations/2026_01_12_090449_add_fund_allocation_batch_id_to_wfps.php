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
        Schema::table('wfps', function (Blueprint $table) {
            $table->unsignedBigInteger('fund_allocation_batch_id')->nullable();
            $table->index('fund_allocation_batch_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wfps', function (Blueprint $table) {
            //
        });
    }
};
