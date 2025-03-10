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
        Schema::create('ca_reminder_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disbursement_voucher_id')->onDelete('cascade');
            $table->enum('status', ['On-Going', 'Pending', 'Completed'])->default('On-Going');
            $table->date('voucher_end_date')->nullable();
            $table->date('liquidation_period_end_date')->nullable();
            $table->integer('step')->default(1);
            $table->boolean('is_sent')->default(false);
            $table->string('title')->nullable();
            $table->text('message')->nullable();
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
        Schema::dropIfExists('ca_reminder_steps');
    }
};
