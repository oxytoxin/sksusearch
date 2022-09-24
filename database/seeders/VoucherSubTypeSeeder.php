<?php

namespace Database\Seeders;

use App\Models\VoucherSubType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VoucherSubTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VoucherSubType::create([
            'name' => 'Local Travel',
            'voucher_type_id' => '1',
        ]);

        VoucherSubType::create([
            'name' => 'Foreign Travel',
            'voucher_type_id' => '1',
        ]);

        VoucherSubType::create([
            'name' => 'Activity, Program, Project, ETC.',
            'voucher_type_id' => '1',
        ]);

        VoucherSubType::create([
            'name' => 'Payroll',
            'voucher_type_id' => '1',
        ]);

        VoucherSubType::create([
            'name' => 'Special Disbursing Officer',
            'voucher_type_id' => '1',
        ]);

        VoucherSubType::create([
            'name' => 'Local Travel',
            'voucher_type_id' => '2',
        ]);

        VoucherSubType::create([
            'name' => 'Foreign Travel',
            'voucher_type_id' => '2',
        ]);

        VoucherSubType::create([
            'name' => 'Activity, Program, Project, ETC.',
            'voucher_type_id' => '2',
        ]);

        VoucherSubType::create([
            'name' => 'Supplies/Materials',
            'voucher_type_id' => '2',
        ]);

        VoucherSubType::create([
            'name' => 'Individual Compensation for Salary/Wage (COS/JO)',
            'voucher_type_id' => '3',
        ]);

        VoucherSubType::create([
            'name' => 'Payroll Compensation for Salaries/Wages (COS/JO)',
            'voucher_type_id' => '4',
        ]);

        VoucherSubType::create([
            'name' => 'Utilities, Fuel, Internet, Telephone, Etc.',
            'voucher_type_id' => '5',
        ]);

        VoucherSubType::create([
            'name' => 'Payment to Contractors of Infrastructure Projects',
            'voucher_type_id' => '6',
        ]);

        VoucherSubType::create([
            'name' => 'Individual Compensation for PS (Overload/Overtime/Honorarium/Requested Subject/Others)',
            'voucher_type_id' => '7',
        ]);

        VoucherSubType::create([
            'name' => 'Payroll Compensation for PS (Overload/Overtime/Honorarium/Requested Subject/Others)',
            'voucher_type_id' => '8',
        ]);

        VoucherSubType::create([
            'name' => 'Individual Compensation for Salary/Wage (Permanent/Temporary/Casual)',
            'voucher_type_id' => '9',
        ]);

        VoucherSubType::create([
            'name' => 'Regular Payroll for Salaries/Wages (Permanent/Temporary/Casual)',
            'voucher_type_id' => '10',
        ]);

        VoucherSubType::create([
            'name' => 'Individual Compensation for Part-Time Services',
            'voucher_type_id' => '11',
        ]);

        VoucherSubType::create([
            'name' => 'Payroll Compensation for Part-Time Services',
            'voucher_type_id' => '12',
        ]);

        VoucherSubType::create([
            'name' => 'Individual Salary/Wage (COS/JO/Laborer)',
            'voucher_type_id' => '13',
        ]);

        VoucherSubType::create([
            'name' => 'Regular Payroll for Salaries/Wages (COS/JO/Laborer)',
            'voucher_type_id' => '14',
        ]);

        VoucherSubType::create([
            'name' => 'Individual Pay/Honorarium for External Experts/Professionals (Activity/Project/Program-Based)',
            'voucher_type_id' => '15',
        ]);

        VoucherSubType::create([
            'name' => 'Payroll Pay/Honorarium for External Experts/Professionals (Activity/Project/Program-Based)',
            'voucher_type_id' => '16',
        ]);

        VoucherSubType::create([
            'name' => 'Individual Compensation for Special Allowances and Bonuses',
            'voucher_type_id' => '17',
        ]);

        VoucherSubType::create([
            'name' => 'Payroll Compensation for Special Allowances and Bonuses',
            'voucher_type_id' => '18',
        ]);

        VoucherSubType::create([
            'name' => 'Individual Compensation for Laborers, Student Assistants, Etc.',
            'voucher_type_id' => '19',
        ]);

        VoucherSubType::create([
            'name' => 'Payroll Compensation for Laborers, Student Assistants, Etc.',
            'voucher_type_id' => '20',
        ]);

        VoucherSubType::create([
            'name' => 'Remittance of Payroll Deductions',
            'voucher_type_id' => '21',
        ]);

        VoucherSubType::create([
            'name' => 'Remittance of Taxes Withheld',
            'voucher_type_id' => '22',
        ]);

    }
}
