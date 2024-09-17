<?php

namespace Database\Seeders;

use App\Models\FundClusterWFP;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FundWFPSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FundClusterWFP::create([
            'name' => '101',
        ]);

        FundClusterWFP::create([
            'name' => '161',
        ]);

        FundClusterWFP::create([
            'name' => '163',
        ]);

        FundClusterWFP::create([
            'name' => '164TF',
        ]);

        FundClusterWFP::create([
            'name' => '164FF/OSF',
        ]);
    }
}
