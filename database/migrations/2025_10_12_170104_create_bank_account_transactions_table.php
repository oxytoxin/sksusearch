<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void
        {
            Schema::create('bank_account_transactions', function (Blueprint $table) {
                $table->id();
                $table->decimal('amount', 16, 2);
                $table->tinyInteger('operation');
                $table->tinyInteger('operator');
                $table->decimal('balance_change', 16, 2)->generatedAs('amount * operator');
                $table->foreignId('bank_account_id')->constrained('bank_accounts');
                $table->timestamps();
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('bank_account_transactions');
        }
    };
