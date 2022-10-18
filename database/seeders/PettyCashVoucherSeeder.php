<?php

namespace Database\Seeders;

use App\Models\PettyCashVoucher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PettyCashVoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PettyCashVoucher::factory()->count(100)->create();
    }
}
