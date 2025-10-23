<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void
        {
            Schema::table('disbursement_vouchers', function (Blueprint $table) {
                $table->foreignId('category_item_budget_id')->nullable()->after('ors_burs')->constrained();
                $table->foreignId('bank_account_id')->nullable()->after('category_item_budget_id')->constrained();
            });
        }
    };
