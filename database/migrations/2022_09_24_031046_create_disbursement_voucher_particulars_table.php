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
        Schema::create('disbursement_voucher_particulars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disbursement_voucher_id')->index();
            $table->string('purpose');
            $table->integer('amount');
            $table->string('responsibility_center');
            $table->string('mfo_pap');
            $table->integer('suggested_amount')->default(0);
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
        Schema::dropIfExists('disbursement_voucher_particulars');
    }
};
