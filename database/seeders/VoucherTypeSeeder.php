<?php

namespace Database\Seeders;

use App\Models\VoucherType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VoucherTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VoucherType::create([
            'name' => 'Cash Advances',
        ]);

        VoucherType::create([
            'name' => 'Reimbursements',
        ]);

        VoucherType::create([
            'name' => 'Individual Compensation for Salary/Wage (COS/JO)',
        ]);

        VoucherType::create([
            'name' => 'Payroll Compensation for Salaries/Wages (COS/JO)',
        ]);

        VoucherType::create([
            'name' => 'Utilities, Fuel, Internet, Telephone, Etc.',
        ]);

        VoucherType::create([
            'name' => 'Payment to Contractors of Infrastructure Projects',
        ]);

        VoucherType::create([
            'name' => 'Individual Compensation for PS (Overload/Overtime/Honorarium/Requested Subject/Others)',
        ]);

        VoucherType::create([
            'name' => 'Payroll Compensation for PS (Overload/Overtime/Honorarium/Requested Subject/Others)',
        ]);

        VoucherType::create([
            'name' => 'Individual Compensation for Salary/Wage (Permanent/Temporary/Casual)',
        ]);

        VoucherType::create([
            'name' => 'Regular Payroll for Salaries/Wages (Permanent/Temporary/Casual)',
        ]);

        VoucherType::create([
            'name' => 'Individual Compensation for Part-Time Services',
        ]);

        VoucherType::create([
            'name' => 'Payroll Compensation for Part-Time Services',
        ]);

        VoucherType::create([
            'name' => 'Individual Salary/Wage (COS/JO/Laborer)',
        ]);

        VoucherType::create([
            'name' => 'Regular Payroll for Salaries/Wages (COS/JO/Laborer)',
        ]);

        VoucherType::create([
            'name' => 'Individual Pay/Honorarium for External Experts/Professionals (Activity/Project/Program-Based)',
        ]);

        VoucherType::create([
            'name' => 'Payroll Pay/Honorarium for External Experts/Professionals (Activity/Project/Program-Based)',
        ]);

        VoucherType::create([
            'name' => 'Individual Compensation for Special Allowances and Bonuses',
        ]);

        VoucherType::create([
            'name' => 'Payroll Compensation for Special Allowances and Bonuses',
        ]);

        VoucherType::create([
            'name' => 'Individual Compensation for Laborers, Student Assistants, Etc.',
        ]);

        VoucherType::create([
            'name' => 'Payroll Compensation for Laborers, Student Assistants, Etc.',
        ]);

        VoucherType::create([
            'name' => 'Remittance of Payroll Deductions',
        ]);

        VoucherType::create([
            'name' => 'Remittance of Taxes Withheld',
        ]);

    }
}
