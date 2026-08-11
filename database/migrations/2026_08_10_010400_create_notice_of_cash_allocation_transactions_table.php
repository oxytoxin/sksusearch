<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notice_of_cash_allocation_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notice_of_cash_allocation_id');
            $table->foreign('notice_of_cash_allocation_id', 'nca_transactions_nca_id_fk')
                ->references('id')
                ->on('notice_of_cash_allocations')
                ->cascadeOnDelete();
            $table->foreignId('bank_account_transaction_id')->nullable();
            $table->foreign('bank_account_transaction_id', 'nca_transactions_bank_tx_id_fk')
                ->references('id')
                ->on('bank_account_transactions')
                ->nullOnDelete();
            $table->foreignId('issued_payment_id')->nullable();
            $table->foreign('issued_payment_id', 'nca_transactions_issued_payment_id_fk')
                ->references('id')
                ->on('issued_payments')
                ->nullOnDelete();
            $table->date('transacted_at');
            $table->string('type');
            $table->decimal('amount', 18, 2);
            $table->tinyInteger('operator');
            $table->decimal('balance_change', 18, 2)->virtualAs('amount * operator');
            $table->decimal('resulting_balance', 18, 2);
            $table->text('remarks')->nullable();
            $table->foreignId('posted_by_id')->nullable();
            $table->foreign('posted_by_id', 'nca_transactions_posted_by_id_fk')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
            $table->timestamps();

            $table->index(
                ['notice_of_cash_allocation_id', 'transacted_at'],
                'nca_transactions_nca_date_index',
            );
            $table->index(['type', 'transacted_at'], 'nca_transactions_type_date_index');
            $table->index(['notice_of_cash_allocation_id', 'type'], 'nca_transactions_nca_type_index');
            $table->index(['issued_payment_id', 'type'], 'nca_transactions_payment_type_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notice_of_cash_allocation_transactions');
    }
};
