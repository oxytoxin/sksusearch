<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bank_account_transactions', function (Blueprint $table) {
            $table->date('transacted_at')->nullable()->after('id');
            $table->string('category')->default('others')->after('operator');
            $table->text('remarks')->nullable()->after('category');
            $table->nullableMorphs('source');
            $table->decimal('resulting_balance', 16, 2)->nullable()->after('balance_change');
            $table->foreignId('posted_by_id')
                ->nullable()
                ->after('bank_account_id')
                ->constrained('users')
                ->nullOnDelete();

            $table->index(['bank_account_id', 'transacted_at'], 'bank_account_transactions_account_date_index');
            $table->index(['category', 'transacted_at'], 'bank_account_transactions_category_date_index');
        });
    }

    public function down(): void
    {
        Schema::table('bank_account_transactions', function (Blueprint $table) {
            $table->dropIndex('bank_account_transactions_account_date_index');
            $table->dropIndex('bank_account_transactions_category_date_index');
            $table->dropConstrainedForeignId('posted_by_id');
            $table->dropColumn('resulting_balance');
            $table->dropMorphs('source');
            $table->dropColumn(['remarks', 'category', 'transacted_at']);
        });
    }
};
