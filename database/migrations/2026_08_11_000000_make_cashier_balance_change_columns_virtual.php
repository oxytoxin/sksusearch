<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            return;
        }

        if (Schema::hasColumn('bank_account_transactions', 'balance_change')) {
            DB::statement('ALTER TABLE bank_account_transactions DROP COLUMN balance_change, ADD COLUMN balance_change DECIMAL(16, 2) GENERATED ALWAYS AS (amount * operator) VIRTUAL AFTER operator');
        }

        if (Schema::hasColumn('notice_of_cash_allocation_transactions', 'balance_change')) {
            DB::statement('ALTER TABLE notice_of_cash_allocation_transactions DROP COLUMN balance_change, ADD COLUMN balance_change DECIMAL(18, 2) GENERATED ALWAYS AS (amount * operator) VIRTUAL AFTER operator');
        }
    }

    public function down(): void
    {
        // Reverting generated columns would discard their values and is intentionally a no-op.
    }
};
