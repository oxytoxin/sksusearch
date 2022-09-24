<?php

namespace Database\Seeders;

use App\Models\VoucherType;
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
            'voucher_category_id' => 1,
            'name' => 'Cash Advances',
        ]);

        VoucherType::create([
            'voucher_category_id' => 1,
            'name' => 'Reimbursements',
        ]);

        VoucherType::create([
            'voucher_category_id' => 1,
            'name' => 'Individual Compensation for Salary/Wage (COS/JO)',
        ]);

        VoucherType::create([
            'voucher_category_id' => 1,
            'name' => 'Payroll Compensation for Salaries/Wages (COS/JO)',
        ]);

        VoucherType::create([
            'voucher_category_id' => 1,
            'name' => 'Utilities, Fuel, Internet, Telephone, Etc.',
        ]);

        VoucherType::create([
            'voucher_category_id' => 1,
            'name' => 'Payment to Contractors of Infrastructure Projects',
        ]);

        VoucherType::create([
            'voucher_category_id' => 1,
            'name' => 'Individual Compensation for PS (Overload/Overtime/Honorarium/Requested Subject/Others)',
        ]);

        VoucherType::create([
            'voucher_category_id' => 1,
            'name' => 'Payroll Compensation for PS (Overload/Overtime/Honorarium/Requested Subject/Others)',
        ]);

        VoucherType::create([
            'voucher_category_id' => 1,
            'name' => 'Individual Compensation for Salary/Wage (Permanent/Temporary/Casual)',
        ]);

        VoucherType::create([
            'voucher_category_id' => 1,
            'name' => 'Regular Payroll for Salaries/Wages (Permanent/Temporary/Casual)',
        ]);

        VoucherType::create([
            'voucher_category_id' => 1,
            'name' => 'Individual Compensation for Part-Time Services',
        ]);

        VoucherType::create([
            'voucher_category_id' => 1,
            'name' => 'Payroll Compensation for Part-Time Services',
        ]);

        VoucherType::create([
            'voucher_category_id' => 1,
            'name' => 'Individual Salary/Wage (COS/JO/Laborer)',
        ]);

        VoucherType::create([
            'voucher_category_id' => 1,
            'name' => 'Regular Payroll for Salaries/Wages (COS/JO/Laborer)',
        ]);

        VoucherType::create([
            'voucher_category_id' => 1,
            'name' => 'Individual Pay/Honorarium for External Experts/Professionals (Activity/Project/Program-Based)',
        ]);

        VoucherType::create([
            'voucher_category_id' => 1,
            'name' => 'Payroll Pay/Honorarium for External Experts/Professionals (Activity/Project/Program-Based)',
        ]);

        VoucherType::create([
            'voucher_category_id' => 1,
            'name' => 'Individual Compensation for Special Allowances and Bonuses',
        ]);

        VoucherType::create([
            'voucher_category_id' => 1,
            'name' => 'Payroll Compensation for Special Allowances and Bonuses',
        ]);

        VoucherType::create([
            'voucher_category_id' => 1,
            'name' => 'Individual Compensation for Laborers, Student Assistants, Etc.',
        ]);

        VoucherType::create([
            'voucher_category_id' => 1,
            'name' => 'Payroll Compensation for Laborers, Student Assistants, Etc.',
        ]);

        VoucherType::create([
            'voucher_category_id' => 1,
            'name' => 'Remittance of Payroll Deductions',
        ]);

        VoucherType::create([
            'voucher_category_id' => 1,
            'name' => 'Remittance of Taxes Withheld',
        ]);
    }
}
