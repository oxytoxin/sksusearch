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
        Schema::create('petty_cash_fund_records', function (Blueprint $table) {
            $table->id();
            $table->morphs('recordable');
            $table->tinyInteger('type')->default(1);
            $table->integer('running_balance');
            $table->foreignId('petty_cash_fund_id')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('petty_cash_fund_records');
    }
};
