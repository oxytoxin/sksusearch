<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void
        {
            Schema::table('archived_cheques', function (Blueprint $table) {
                $table->decimal('cheque_amount', 18, 2)->change();
            });
            DB::table('archived_cheques')->update([
                'cheque_amount' => DB::raw('cheque_amount / 100'),
            ]);

            Schema::table('bonds', function (Blueprint $table) {
                $table->decimal('amount', 18, 2)->change();
            });
            DB::table('bonds')->update([
                'amount' => DB::raw('amount / 100'),
            ]);

            Schema::table('disbursement_voucher_particulars', function (Blueprint $table) {
                $table->decimal('amount', 18, 2)->change();
                $table->decimal('suggested_amount', 18, 2)->change();
            });
            DB::table('disbursement_voucher_particulars')->update([
                'amount' => DB::raw('amount / 100'),
                'suggested_amount' => DB::raw('suggested_amount / 100'),
            ]);

            Schema::table('dtes', function (Blueprint $table) {
                $table->decimal('amount', 18, 2)->change();
            });
            DB::table('dtes')->update([
                'amount' => DB::raw('amount / 100'),
            ]);

            Schema::table('itinerary_entries', function (Blueprint $table) {
                $table->decimal('transportation_expenses', 18, 2)->change();
                $table->decimal('other_expenses', 18, 2)->change();
            });
            DB::table('itinerary_entries')->update([
                'transportation_expenses' => DB::raw('transportation_expenses / 100'),
                'other_expenses' => DB::raw('other_expenses / 100'),
            ]);

            Schema::table('legacy_documents', function (Blueprint $table) {
                $table->decimal('cheque_amount', 18, 2)->change();
            });
            DB::table('legacy_documents')->update([
                'cheque_amount' => DB::raw('cheque_amount / 100'),
            ]);

            Schema::table('petty_cash_funds', function (Blueprint $table) {
                $table->decimal('voucher_limit', 18, 2)->change();
            });
            DB::table('petty_cash_funds')->update([
                'voucher_limit' => DB::raw('voucher_limit / 100'),
            ]);

            Schema::table('petty_cash_fund_records', function (Blueprint $table) {
                $table->decimal('amount', 18, 2)->change();
                $table->decimal('running_balance', 18, 2)->change();
            });
            DB::table('petty_cash_fund_records')->update([
                'amount' => DB::raw('amount / 100'),
                'running_balance' => DB::raw('running_balance / 100'),
            ]);

            Schema::table('petty_cash_vouchers', function (Blueprint $table) {
                $table->decimal('amount_granted', 18, 2)->change();
                $table->decimal('amount_paid', 18, 2)->change();
            });
            DB::table('petty_cash_vouchers')->update([
                'amount_granted' => DB::raw('amount_granted / 100'),
                'amount_paid' => DB::raw('amount_paid / 100'),
            ]);

            Schema::table('travel_orders', function (Blueprint $table) {
                $table->decimal('registration_amount', 18, 2)->change();
            });
            DB::table('travel_orders')->update([
                'registration_amount' => DB::raw('registration_amount / 100'),
            ]);
        }

    };
