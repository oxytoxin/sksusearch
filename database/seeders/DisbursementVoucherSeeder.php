<?php

namespace Database\Seeders;

use App\Models\DisbursementVoucher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DisbursementVoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DisbursementVoucher::create([
            'voucher_subtype_id' => 69,
            'user_id' => 19,
            'signatory_id' => 46,
            'certified_by_accountant' => 1,
            'mop_id' => 1,
            'travel_order_id' => null,
            'tracking_number' => "DV_2022-09-739",
            'payee' => "INNOVE COMMUNICATIONS, INC.",
            'cheque_number' => '1231941294',
            'ors_burs' => "202209-101-1957",
            'dv_number' => "101-2022-10-2593",
            'due_date' => null,
            'closed_at' => null,
            'journal_date' => "2022-10-03",
            'submitted_at' => "2022-09-30",
            'draft' => null,
            'related_documents' => null,
            'fund_cluster_id' => 4,
            'current_step_id' => 17000,
            'previous_step_id' => 14000,
        ]);
    }
}
