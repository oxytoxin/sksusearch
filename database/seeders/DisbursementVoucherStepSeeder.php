<?php

namespace Database\Seeders;

use App\Models\DisbursementVoucherStep;
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
            'sender' => 'by Requisitioner',
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
            'sender' => 'by Signatory',
            'office_id' => 5,
        ]);

        DisbursementVoucherStep::create([
            'id' => 4000,
            'process' => 'Received in',
            'recipient' => 'ICU',
            'sender' => 'by ICU Staff (Pre-Audit)',
            'office_id' => 5,
        ]);

        DisbursementVoucherStep::create([
            'id' => 5000,
            'process' => 'Forwarded to',
            'recipient' => 'Budget Office',
            'sender' => 'by ICU Staff',
            'office_id' => 2,
        ]);

        DisbursementVoucherStep::create([
            'id' => 6000,
            'process' => 'Received in',
            'recipient' => 'Budget Office',
            'sender' => 'by Budget Office Staff',
            'office_id' => 2,
        ]);

        DisbursementVoucherStep::create([
            'id' => 7000,
            'process' => 'Forwarded to',
            'recipient' => 'Accounting Office',
            'sender' => 'by Budget Office Staff',
            'office_id' => 3,
        ]);

        DisbursementVoucherStep::create([
            'id' => 8000,
            'process' => 'Received in',
            'recipient' => 'Accounting Office',
            'sender' => 'by Accounting Office Staff',
            'office_id' => 3,
        ]);

        DisbursementVoucherStep::create([
            'id' => 9000,
            'process' => 'For preparation of ORS/BURS',
            'recipient' => 'Accounting Office',
            'sender' => 'by Accounting Office Staff',
            'office_id' => 3,
        ]);

        DisbursementVoucherStep::create([
            'id' => 10000,
            'process' => 'For verification, classification and recording',
            'recipient' => 'Accounting Office',
            'sender' => 'by Accounting Office Staff',
            'office_id' => 3,
        ]);

        DisbursementVoucherStep::create([
            'id' => 11000,
            'process' => 'For certification by',
            'recipient' => 'Chief Accountant',
            'sender' => 'by Accounting Office Staff',
            'office_id' => 3,
        ]);

        DisbursementVoucherStep::create([
            'id' => 12000,
            'process' => 'Forwarded to',
            'recipient' => 'President\'s Office',
            'sender' => 'by Accounting Officer Staff',
            'office_id' => 51,
        ]);

        DisbursementVoucherStep::create([
            'id' => 13000,
            'process' => 'Received in',
            'recipient' => 'President\'s Office',
            'sender' => 'by Office of the President Staff',
            'office_id' => 51,
        ]);

        DisbursementVoucherStep::create([
            'id' => 14000,
            'process' => 'Forwarded to',
            'recipient' => 'Cashier',
            'sender' => 'by Office of the President Staff',
            'office_id' => 52,
        ]);

        DisbursementVoucherStep::create([
            'id' => 15000,
            'process' => 'Received in',
            'recipient' => 'Cashier',
            'sender' => 'Cashier',
            'office_id' => 52,
        ]);

        DisbursementVoucherStep::create([
            'id' => 16000,
            'process' => 'Cheque/ADA made for',
            'recipient' => 'Requisitioner',
            'sender' => 'Cashier',
        ]);

        DisbursementVoucherStep::create([
            'id' => 17000,
            'process' => 'Forwarded to',
            'recipient' => 'ICU',
            'sender' => 'Cashier',
            'office_id' => 5,
        ]);

        DisbursementVoucherStep::create([
            'id' => 18000,
            'process' => 'Received in',
            'recipient' => 'ICU',
            'sender' => 'by ICU Staff (Post-Audit)',
            'office_id' => 5,
        ]);

        DisbursementVoucherStep::create([
            'id' => 19000,
            'process' => 'Forwarded to',
            'recipient' => 'Archiver',
            'sender' => 'by ICU Staff (Post-Audit)',

        ]);

        DisbursementVoucherStep::create([
            'id' => 20000,
            'process' => 'Received in',
            'recipient' => 'Archiving Unit',
            'sender' => 'by Archiver',
        ]);

        DisbursementVoucherStep::create([
            'id' => 21000,
            'process' => 'Document Archived in',
            'recipient' => 'SEARCH',
            'sender' => 'by Archiver',
        ]);

        DisbursementVoucherStep::create([
            'id' => 22000,
            'process' => 'Forwarded to',
            'recipient' => 'COA',
            'sender' => 'Archiver',
        ]);

        DisbursementVoucherStep::create([
            'id' => 23000,
            'process' => 'Received in',
            'recipient' => 'COA',
            'sender' => 'by COA Staff',
        ]);
    }
}
