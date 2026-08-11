<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notice_of_cash_allocations', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->text('description')->nullable();
            $table->decimal('allocated_amount', 18, 2);
            $table->decimal('remaining_amount', 18, 2);
            $table->date('allocated_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notice_of_cash_allocations');
    }
};
