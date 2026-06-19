<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('batch_transmittal_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_transmittal_id')->constrained('batch_transmittals')->cascadeOnDelete();
            $table->foreignId('disbursement_voucher_id')->constrained('disbursement_vouchers');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('batch_transmittal_items');
    }
};
