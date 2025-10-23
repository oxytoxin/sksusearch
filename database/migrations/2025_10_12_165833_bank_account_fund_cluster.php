<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void
        {
            Schema::create('bank_account_fund_cluster', function (Blueprint $table) {
                $table->id();
                $table->foreignId('bank_account_id')->constrained();
                $table->foreignId('fund_cluster_id')->constrained();
                $table->timestamps();
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('bank_account_fund_cluster');
        }
    };
