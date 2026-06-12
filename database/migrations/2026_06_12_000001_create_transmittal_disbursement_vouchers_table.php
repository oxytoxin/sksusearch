<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transmittal_disbursement_vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transmittal_id');
            $table->foreignId('disbursement_voucher_id');
            $table->timestamps();

            // Explicit short constraint names — the auto-generated ones exceed
            // MySQL's 64-char identifier limit for this long pivot table name.
            $table->foreign('transmittal_id', 'tdv_transmittal_fk')
                ->references('id')->on('transmittals')->cascadeOnDelete();
            $table->foreign('disbursement_voucher_id', 'tdv_dv_fk')
                ->references('id')->on('disbursement_vouchers')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transmittal_disbursement_vouchers');
    }
};
