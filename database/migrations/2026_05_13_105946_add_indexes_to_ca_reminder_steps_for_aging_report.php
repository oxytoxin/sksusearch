<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ca_reminder_steps', function (Blueprint $table) {
            if (! $this->indexExists('ca_reminder_steps', 'ca_reminder_steps_liquidation_period_end_date_index')) {
                $table->index('liquidation_period_end_date');
            }

            if (! $this->indexExists('ca_reminder_steps', 'ca_reminder_steps_dv_id_liq_end_idx')) {
                $table->index(
                    ['disbursement_voucher_id', 'liquidation_period_end_date'],
                    'ca_reminder_steps_dv_id_liq_end_idx',
                );
            }
        });

        Schema::table('disbursement_voucher_particulars', function (Blueprint $table) {
            if (! $this->indexExists('disbursement_voucher_particulars', 'dvp_dv_id_index')) {
                $table->index('disbursement_voucher_id', 'dvp_dv_id_index');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ca_reminder_steps', function (Blueprint $table) {
            if ($this->indexExists('ca_reminder_steps', 'ca_reminder_steps_liquidation_period_end_date_index')) {
                $table->dropIndex('ca_reminder_steps_liquidation_period_end_date_index');
            }

            if ($this->indexExists('ca_reminder_steps', 'ca_reminder_steps_dv_id_liq_end_idx')) {
                $table->dropIndex('ca_reminder_steps_dv_id_liq_end_idx');
            }
        });

        Schema::table('disbursement_voucher_particulars', function (Blueprint $table) {
            if ($this->indexExists('disbursement_voucher_particulars', 'dvp_dv_id_index')) {
                $table->dropIndex('dvp_dv_id_index');
            }
        });
    }

    private function indexExists(string $table, string $indexName): bool
    {
        $rows = DB::select(
            'SHOW INDEX FROM `'.$table.'` WHERE Key_name = ?',
            [$indexName],
        );

        return count($rows) > 0;
    }
};
