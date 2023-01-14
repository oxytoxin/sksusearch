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
        Schema::create('disbursement_voucher_steps', function (Blueprint $table) {
            $table->id();
            $table->string('process');
            $table->string('recipient');
            $table->string('sender')->nullable();
            $table->foreignId('office_id')->nullable();
            $table->integer('return_step_id');
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
        Schema::dropIfExists('disbursement_voucher_steps');
    }
};
