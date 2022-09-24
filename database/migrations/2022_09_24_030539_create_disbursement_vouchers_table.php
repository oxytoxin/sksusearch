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
            $table->foreignId('current_step_id')->index();
            $table->foreignId('previous_step_id')->index();
            $table->string('tracking_number');
            $table->string('cheque_number')->nullable();
            $table->date('closed_date')->nullable();
            $table->date('submitted_date')->nullable();
            $table->json('draft')->nullable();
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
