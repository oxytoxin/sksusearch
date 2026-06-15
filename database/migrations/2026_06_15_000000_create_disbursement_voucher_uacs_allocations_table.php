<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('disbursement_voucher_uacs_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disbursement_voucher_id');
            $table->foreignId('category_item_budget_id');
            $table->decimal('amount', 18, 2);
            $table->timestamps();

            $table->index(['disbursement_voucher_id', 'category_item_budget_id'], 'dv_uacs_allocations_dv_budget_index');
            $table->foreign('disbursement_voucher_id', 'dv_uacs_allocations_dv_id_foreign')
                ->references('id')
                ->on('disbursement_vouchers')
                ->cascadeOnDelete();
            $table->foreign('category_item_budget_id', 'dv_uacs_allocations_budget_id_foreign')
                ->references('id')
                ->on('category_item_budgets');
        });

        DB::table('disbursement_vouchers')
            ->whereNotNull('category_item_budget_id')
            ->orderBy('id')
            ->select(['id', 'category_item_budget_id'])
            ->chunkById(100, function ($vouchers) {
                $now = now();

                foreach ($vouchers as $voucher) {
                    $amount = DB::table('disbursement_voucher_particulars')
                        ->where('disbursement_voucher_id', $voucher->id)
                        ->sum('final_amount');

                    DB::table('disbursement_voucher_uacs_allocations')->insert([
                        'disbursement_voucher_id' => $voucher->id,
                        'category_item_budget_id' => $voucher->category_item_budget_id,
                        'amount' => $amount,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            });

        Schema::table('disbursement_vouchers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_item_budget_id');
        });
    }
};
