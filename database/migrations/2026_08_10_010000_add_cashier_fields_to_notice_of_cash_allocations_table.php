<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notice_of_cash_allocations', function (Blueprint $table) {
            $table->foreignId('bank_account_id')
                ->nullable()
                ->after('remaining_amount')
                ->constrained('bank_accounts')
                ->nullOnDelete();
            $table->foreignId('fund_cluster_group_id')
                ->nullable()
                ->after('bank_account_id')
                ->constrained('fund_cluster_groups')
                ->nullOnDelete();
            $table->string('status')->default('active')->after('fund_cluster_group_id');
            $table->date('expiry_date')->nullable()->after('allocated_at');
            $table->timestamp('posted_at')->nullable()->after('expiry_date');
            $table->foreignId('posted_by_id')
                ->nullable()
                ->after('posted_at')
                ->constrained('users')
                ->nullOnDelete();

            $table->index(['bank_account_id', 'expiry_date'], 'nca_bank_account_expiry_index');
            $table->index(['bank_account_id', 'status'], 'nca_bank_account_status_index');
            $table->index(['fund_cluster_group_id', 'status'], 'nca_fund_cluster_group_status_index');
        });
    }

    public function down(): void
    {
        Schema::table('notice_of_cash_allocations', function (Blueprint $table) {
            $table->dropIndex('nca_bank_account_expiry_index');
            $table->dropIndex('nca_bank_account_status_index');
            $table->dropIndex('nca_fund_cluster_group_status_index');
            $table->dropConstrainedForeignId('posted_by_id');
            $table->dropColumn(['posted_at', 'expiry_date', 'status']);
            $table->dropConstrainedForeignId('fund_cluster_group_id');
            $table->dropConstrainedForeignId('bank_account_id');
        });
    }
};
