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
        Schema::create('petty_cash_vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number');
            $table->string('entity_name')->nullable();
            $table->foreignId('fund_cluster_id')->index();
            $table->foreignId('petty_cash_fund_id')->index();
            $table->string('pcv_number')->nullable();
            $table->dateTime('pcv_date');
            $table->string('payee')->nullable();
            $table->foreignId('custodian_id')->index();
            $table->foreignId('requisitioner_id')->index();
            $table->foreignId('signatory_id')->index();
            $table->string('responsibility_center')->nullable();
            $table->json('particulars')->nullable();
            $table->integer('amount_granted')->default(0);
            $table->integer('amount_paid')->default(0);
            $table->boolean('is_liquidated')->default(false);
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
        Schema::dropIfExists('petty_cash_vouchers');
    }
};
