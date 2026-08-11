<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('issued_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disbursement_voucher_id')
                ->nullable()
                ->constrained('disbursement_vouchers')
                ->nullOnDelete();
            $table->foreignId('mop_id')->nullable()->constrained('mops')->nullOnDelete();
            $table->foreignId('bank_account_id')->constrained('bank_accounts')->restrictOnDelete();
            $table->foreignId('notice_of_cash_allocation_id')
                ->nullable()
                ->constrained('notice_of_cash_allocations')
                ->nullOnDelete();
            $table->foreignId('check_stub_id')->nullable()->constrained('check_stubs')->nullOnDelete();
            $table->foreignId('fund_cluster_id')->nullable()->constrained('fund_clusters')->nullOnDelete();
            $table->foreignId('bank_account_transaction_id')
                ->nullable()
                ->constrained('bank_account_transactions')
                ->nullOnDelete();
            $table->string('serial_number');
            $table->date('issued_at');
            $table->decimal('amount', 18, 2);
            $table->string('payee')->nullable();
            $table->string('dv_number')->nullable();
            $table->string('ors_burs')->nullable();
            $table->string('responsibility_center')->nullable();
            $table->string('status')->default('issued');
            $table->string('source')->default('cashier');
            $table->boolean('affects_balance')->default(true);
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_remarks')->nullable();
            $table->foreignId('posted_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('issued_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('cancelled_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['bank_account_id', 'issued_at'], 'issued_payments_account_date_index');
            $table->index(['mop_id', 'issued_at'], 'issued_payments_mop_date_index');
            $table->index(['fund_cluster_id', 'issued_at'], 'issued_payments_fund_date_index');
            $table->index(['serial_number', 'bank_account_id'], 'issued_payments_serial_account_index');
            $table->index(['bank_account_id', 'status'], 'issued_payments_account_status_index');
            $table->index(['notice_of_cash_allocation_id', 'status'], 'issued_payments_nca_status_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('issued_payments');
    }
};
