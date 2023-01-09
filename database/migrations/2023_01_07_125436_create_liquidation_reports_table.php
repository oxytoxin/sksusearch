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
        Schema::create('liquidation_reports', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number');
            $table->foreignId('disbursement_voucher_id');
            $table->json('particulars');
            $table->foreignId('user_id');
            $table->foreignId('signatory_id');
            $table->boolean('certified_by_accountant')->default(false);
            $table->boolean('reimbursement_waived')->default(false);
            $table->json('refund_particulars')->nullable();
            $table->string('lr_number')->nullable();
            $table->date('report_date')->nullable();
            $table->date('signatory_date')->nullable();
            $table->date('journal_date')->nullable();
            $table->boolean('for_cancellation')->default(false);
            $table->dateTime('cancelled_at')->nullable();
            $table->foreignId('current_step_id')->default(3000);
            $table->foreignId('previous_step_id')->default(3000);
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
        Schema::dropIfExists('liquidation_reports');
    }
};
