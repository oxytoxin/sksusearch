<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Move pre-audit steps from ICU (office_group_id=3) to Accounting Office (office_group_id=2)
        DB::table('disbursement_voucher_steps')->where('id', 5000)->update([
            'office_group_id' => 2,
            'recipient' => 'Accounting Office (Pre-Audit)',
        ]);

        DB::table('disbursement_voucher_steps')->where('id', 6000)->update([
            'office_group_id' => 2,
            'recipient' => 'Accounting Office (Pre-Audit)',
            'sender' => 'by Accounting Office Staff (Pre-Audit)',
        ]);

        DB::table('disbursement_voucher_steps')->where('id', 7000)->update([
            'sender' => 'by Accounting Office Staff (Pre-Audit)',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('disbursement_voucher_steps')->where('id', 5000)->update([
            'office_group_id' => 3,
            'recipient' => 'ICU',
        ]);

        DB::table('disbursement_voucher_steps')->where('id', 6000)->update([
            'office_group_id' => 3,
            'recipient' => 'ICU',
            'sender' => 'by ICU Staff (Pre-Audit)',
        ]);

        DB::table('disbursement_voucher_steps')->where('id', 7000)->update([
            'sender' => 'by ICU Staff',
        ]);
    }
};
