<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dv_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disbursement_voucher_id')->constrained()->cascadeOnDelete();
            $table->string('field');
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->foreignId('adjusted_by')->constrained('users');
            $table->string('batch_id', 36)->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dv_adjustments');
    }
};
