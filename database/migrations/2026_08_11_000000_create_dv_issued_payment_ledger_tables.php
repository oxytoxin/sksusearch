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
            $table->string('category')->default('others')->after('bank_account_id');
            $table->decimal('resulting_balance', 16, 2)->nullable()->after('balance_change');
            $table->nullableMorphs('source');
            $table->foreignId('posted_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('remarks')->nullable();
        });

        Schema::create('check_stubs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_account_id')->constrained();
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

            $table->index(['bank_account_id', 'status']);
        });

        Schema::create('notice_of_cash_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_account_id')->constrained();
            $table->foreignId('fund_cluster_group_id')->constrained();
            $table->string('number')->unique();
            $table->string('status')->default('active');
            $table->date('allocated_at');
            $table->date('expiry_date')->nullable();
            $table->timestamp('posted_at')->nullable();
            $table->decimal('allocated_amount', 16, 2);
            $table->decimal('remaining_amount', 16, 2);
            $table->foreignId('posted_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index(['bank_account_id', 'status']);
            $table->index(['fund_cluster_group_id', 'status']);
        });

        Schema::create('issued_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disbursement_voucher_id')->constrained();
            $table->foreignId('mop_id')->constrained();
            $table->foreignId('fund_cluster_id')->nullable()->constrained();
            $table->foreignId('bank_account_id')->constrained();
            $table->foreignId('notice_of_cash_allocation_id')->nullable()->constrained();
            $table->foreignId('check_stub_id')->constrained();
            $table->foreignId('bank_account_transaction_id')->nullable()->constrained();
            $table->string('source')->default('cashier');
            $table->string('status')->default('issued');
            $table->string('serial_number');
            $table->date('issued_at');
            $table->decimal('amount', 16, 2);
            $table->boolean('affects_balance')->default(true);
            $table->foreignId('issued_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('cancelled_at')->nullable();
            $table->foreignId('cancelled_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();

            $table->unique('disbursement_voucher_id');
            $table->unique(['bank_account_id', 'serial_number']);
            $table->index(['bank_account_id', 'status']);
            $table->index(['notice_of_cash_allocation_id', 'status'], 'issued_payments_nca_status_index');
        });

        Schema::create('notice_of_cash_allocation_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notice_of_cash_allocation_id')->constrained();
            $table->foreignId('bank_account_transaction_id')->nullable()->constrained();
            $table->foreignId('issued_payment_id')->nullable()->constrained();
            $table->string('type');
            $table->date('transacted_at');
            $table->decimal('amount', 16, 2);
            $table->tinyInteger('operation');
            $table->tinyInteger('operator');
            $table->decimal('balance_change', 16, 2)->generatedAs('amount * operator');
            $table->decimal('resulting_balance', 16, 2);
            $table->foreignId('posted_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index(['notice_of_cash_allocation_id', 'type'], 'nca_transactions_nca_type_index');
            $table->index(['issued_payment_id', 'type'], 'nca_transactions_payment_type_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notice_of_cash_allocation_transactions');
        Schema::dropIfExists('issued_payments');
        Schema::dropIfExists('notice_of_cash_allocations');
        Schema::dropIfExists('check_stubs');

        Schema::table('bank_account_transactions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('posted_by_id');
            $table->dropMorphs('source');
            $table->dropColumn([
                'transacted_at',
                'category',
                'resulting_balance',
                'remarks',
            ]);
        });
    }
};
