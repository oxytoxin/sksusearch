<?php

namespace Database\Seeders;

use App\Models\Dte;
use Illuminate\Database\Seeder;

class DteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Dte::create([
            'amount' => 1500,
            'philippine_region_id' => 1,
        ]);

        Dte::create([
            'amount' => 1500,
            'philippine_region_id' => 2,
        ]);

        Dte::create([
            'amount' => 1500,
            'philippine_region_id' => 3,
        ]);

        Dte::create([
            'amount' => 2200,
            'philippine_region_id' => 4,
        ]);

        Dte::create([
            'amount' => 2200,
            'philippine_region_id' => 5,
        ]);

        Dte::create([
            'amount' => 1500,
            'philippine_region_id' => 6,
        ]);

        Dte::create([
            'amount' => 1800,
            'philippine_region_id' => 7,
        ]);

        Dte::create([
            'amount' => 1800,
            'philippine_region_id' => 8,
        ]);

        Dte::create([
            'amount' => 1500,
            'philippine_region_id' => 9,
        ]);

        Dte::create([
            'amount' => 1500,
            'philippine_region_id' => 10,
        ]);

        Dte::create([
            'amount' => 1800,
            'philippine_region_id' => 11,
        ]);

        Dte::create([
            'amount' => 1800,
            'philippine_region_id' => 12,
        ]);

        Dte::create([
            'amount' => 1500,
            'philippine_region_id' => 13,
        ]);

        Dte::create([
            'amount' => 2200,
            'philippine_region_id' => 14,
        ]);

        Dte::create([
            'amount' => 1800,
            'philippine_region_id' => 15,
        ]);

        Dte::create([
            'amount' => 1500,
            'philippine_region_id' => 16,
        ]);

        Dte::create([
            'amount' => 1500,
            'philippine_region_id' => 17,
        ]);
    }
}
