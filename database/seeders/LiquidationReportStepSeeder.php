<?php

namespace Database\Seeders;

use App\Models\LiquidationReportStep;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LiquidationReportStepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LiquidationReportStep::create([
            "id" => 1000,
            "office_group_id" => null,
            "process" => "Forwarded to",
            "recipient" => "Requisitioner",
            "sender" => "by Previous Office",
            "office_id" => null,
            "return_step_id" => 1000,
        ]);
        LiquidationReportStep::create([
            "id" => 2000,
            "office_group_id" => null,
            "process" => "Received by",
            "recipient" => "Requisitioner",
            "sender" => null,
            "office_id" => null,
            "return_step_id" => 1000,
        ]);
        LiquidationReportStep::create([
            "id" => 3000,
            "office_group_id" => null,
            "process" => "Forwarded to",
            "recipient" => "Signatory",
            "sender" => "by Requisitioner",
            "office_id" => null,
            "return_step_id" => 3000,
        ]);
        LiquidationReportStep::create([
            "id" => 4000,
            "office_group_id" => null,
            "process" => "Received by",
            "recipient" => "Signatory",
            "sender" => null,
            "office_id" => null,
            "return_step_id" => 3000,
        ]);

        LiquidationReportStep::create([
            "id" => 5000,
            "office_group_id" => 2,
            "process" => "Forwarded to",
            "recipient" => "Accounting Office",
            "sender" => "by Signatory",
            "office_id" => 3,
            "return_step_id" => 5000,
        ]);
        LiquidationReportStep::create([
            "id" => 6000,
            "office_group_id" => 2,
            "process" => "Received in",
            "recipient" => "Accounting Office",
            "sender" => "by Accounting Office Staff",
            "office_id" => 3,
            "return_step_id" => 5000,
        ]);
        LiquidationReportStep::create([
            "id" => 7000,
            "office_group_id" => 2,
            "process" => "For verification, classification and recording",
            "recipient" => "-",
            "sender" => "by Accounting Office Staff",
            "office_id" => 3,
            "return_step_id" => 5000,
        ]);
        LiquidationReportStep::create([
            "id" => 8000,
            "office_group_id" => 2,
            "process" => "For certification by",
            "recipient" => "Chief Accountant",
            "sender" => null,
            "office_id" => 3,
            "return_step_id" => 5000,
        ]);
        // LiquidationReportStep::create([
        //     "id" => 9000,
        //     "office_group_id" => 3,
        //     "process" => "Forwarded to",
        //     "recipient" => "ICU",
        //     "sender" => "by Accounting Office Staff",
        //     "office_id" => 5,
        //     "return_step_id" => 9000,
        // ]);
        // LiquidationReportStep::create([
        //     "id" => 10000,
        //     "office_group_id" => 3,
        //     "process" => "Received in",
        //     "recipient" => "ICU",
        //     "sender" => "by ICU Staff (Post-Audit)",
        //     "office_id" => 5,
        //     "return_step_id" => 9000,
        // ]);
        LiquidationReportStep::create([
            "id" => 9000,
            "office_group_id" => null,
            "process" => "Forwarded to",
            "recipient" => "Archiver",
            "sender" => "by Accounting Office Staff",
            "office_id" => null,
            "return_step_id" => 9000,
        ]);
        LiquidationReportStep::create([
            "id" => 10000,
            "office_group_id" => null,
            "process" => "Received in",
            "recipient" => "Archiving Unit",
            "sender" => "by Archiver",
            "office_id" => null,
            "return_step_id" => 10000,
        ]);
        LiquidationReportStep::create([
            "id" => 11000,
            "office_group_id" => null,
            "process" => "Document Archived in",
            "recipient" => "SEARCH",
            "sender" => "by Archiver",
            "office_id" => null,
            "return_step_id" => 9000,
        ]);
        LiquidationReportStep::create([
            "id" => 12000,
            "office_group_id" => null,
            "process" => "Forwarded to",
            "recipient" => "COA",
            "sender" => "by Archiver",
            "office_id" => null,
            "return_step_id" => 12000,
        ]);
        LiquidationReportStep::create([
            "id" => 13000,
            "office_group_id" => null,
            "process" => "Received in",
            "recipient" => "COA",
            "sender" => "by COA Staff",
            "office_id" => null,
            "return_step_id" => 12000,
        ]);
    }
}
