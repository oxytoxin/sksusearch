<?php

namespace Database\Seeders;

use App\Models\PettyCashVoucher;
use DB;
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
        $pcvs = collect();
        foreach (range(0, 100) as $key => $value) {
            $pcvs->push(PettyCashVoucher::factory()->create());
        }
    }
}
