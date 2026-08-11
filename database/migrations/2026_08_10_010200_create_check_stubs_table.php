<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('check_stubs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_account_id')->constrained('bank_accounts')->restrictOnDelete();
            $table->string('prefix')->nullable();
            $table->unsignedBigInteger('start_number');
            $table->unsignedBigInteger('end_number');
            $table->unsignedBigInteger('next_number');
            $table->unsignedSmallInteger('number_padding')->default(0);
            $table->unsignedInteger('consumed_count')->default(0);
            $table->string('status')->default('active');
            $table->timestamp('exhausted_at')->nullable();
            $table->foreignId('registered_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['bank_account_id', 'status'], 'check_stubs_account_status_index');
            $table->index(['bank_account_id', 'start_number', 'end_number'], 'check_stubs_account_range_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('check_stubs');
    }
};
