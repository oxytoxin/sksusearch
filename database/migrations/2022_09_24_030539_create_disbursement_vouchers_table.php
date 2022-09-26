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
        Schema::create('disbursement_vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_subtype_id')->index();
            $table->foreignId('user_id')->index();
            $table->foreignId('signatory_id')->index();
            $table->foreignId('mop_id')->index();
            $table->foreignId('travel_order_id')->index()->nullable();
            $table->string('tracking_number');
            $table->string('payee');
            $table->string('cheque_number')->nullable();
            $table->string('dv_number')->nullable();
            $table->date('due_date')->nullable();
            $table->date('closed_at')->nullable();
            $table->date('submitted_at')->nullable();
            $table->json('draft')->nullable();
            $table->foreignId('fund_cluster_id')->nullable()->index();
            $table->foreignId('current_step_id')->index();
            $table->foreignId('previous_step_id')->index();
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
        Schema::dropIfExists('disbursement_vouchers');
    }
};
