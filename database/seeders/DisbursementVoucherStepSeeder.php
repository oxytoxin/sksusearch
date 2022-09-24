<?php

namespace Database\Seeders;

use App\Models\DisbursementVoucherStep;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DisbursementVoucherStepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DisbursementVoucherStep::create([
            'id' => 1000,
            'process' => 'Forwarded to',
            'recipient' => 'Signatory',
        ]);

        DisbursementVoucherStep::create([
            'id' => 2000,
            'process' => 'Received by',
            'recipient' => 'Signatory',
        ]);

        DisbursementVoucherStep::create([
            'id' => 3000,
            'process' => 'Forwarded to',
            'recipient' => 'ICU',
        ]);

        DisbursementVoucherStep::create([
            'id' => 4000,
            'process' => 'Received in',
            'recipient' => 'ICU',
        ]);

        DisbursementVoucherStep::create([
            'id' => 5000,
            'process' => 'Forwarded to',
            'recipient' => 'Budget Office',
        ]);

        DisbursementVoucherStep::create([
            'id' => 6000,
            'process' => 'Received in',
            'recipient' => 'Budget Office',
        ]);

        DisbursementVoucherStep::create([
            'id' => 7000,
            'process' => 'Forwarded to',
            'recipient' => 'Accounting Office',
        ]);

        DisbursementVoucherStep::create([
            'id' => 8000,
            'process' => 'For preparation of ORS/BURS',
            'recipient' => 'Accounting Office',
        ]);

        DisbursementVoucherStep::create([
            'id' => 9000,
            'process' => 'Received in',
            'recipient' => 'Accounting Office',
        ]);

        DisbursementVoucherStep::create([
            'id' => 10000,
            'process' => 'For verification, classification and recording',
            'recipient' => 'Accounting Office',
        ]);

        DisbursementVoucherStep::create([
            'id' => 11000,
            'process' => 'For certification by',
            'recipient' => 'Chief Accountant',
        ]);

        DisbursementVoucherStep::create([
            'id' => 12000,
            'process' => 'Forwarded to',
            'recipient' => 'President\'s Office',
        ]);

        DisbursementVoucherStep::create([
            'id' => 13000,
            'process' => 'Received in',
            'recipient' => 'President\'s Office',
        ]);

        DisbursementVoucherStep::create([
            'id' => 14000,
            'process' => 'Received in',
            'recipient' => 'President\'s Office',
        ]);

        DisbursementVoucherStep::create([
            'id' => 15000,
            'process' => 'Forwarded to',
            'recipient' => 'Cashier',
        ]);

        DisbursementVoucherStep::create([
            'id' => 16000,
            'process' => 'Received in',
            'recipient' => 'Cashier',
        ]);

        DisbursementVoucherStep::create([
            'id' => 17000,
            'process' => 'Cheque/ADA made for',
            'recipient' => 'Requisitioner',
        ]);

        DisbursementVoucherStep::create([
            'id' => 18000,
            'process' => 'Forwarded to',
            'recipient' => 'ICU',
        ]);

        DisbursementVoucherStep::create([
            'id' => 19000,
            'process' => 'Received in',
            'recipient' => 'ICU',
        ]);

        DisbursementVoucherStep::create([
            'id' => 20000,
            'process' => 'Forwarded to',
            'recipient' => 'Archiver',
        ]);

        DisbursementVoucherStep::create([
            'id' => 21000,
            'process' => 'Received in',
            'recipient' => 'Archiving Unit',
        ]);

        DisbursementVoucherStep::create([
            'id' => 22000,
            'process' => 'Document Archived in',
            'recipient' => 'SEARCH',
        ]);

        DisbursementVoucherStep::create([
            'id' => 23000,
            'process' => 'Forwarded to',
            'recipient' => 'COA',
        ]);

        DisbursementVoucherStep::create([
            'id' => 24000,
            'process' => 'Received in',
            'recipient' => 'COA',
        ]);
    }
}
