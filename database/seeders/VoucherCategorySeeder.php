<?php

namespace Database\Seeders;

use App\Models\VoucherCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VoucherCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VoucherCategory::create([
            'name' => 'Disbursements',
        ]);

        VoucherCategory::create([
            'name' => 'Liquidation',
        ]);

        VoucherCategory::create([
            'name' => 'Communication',
        ]);
    }
}
