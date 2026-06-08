<?php

namespace Database\Seeders;

use App\Models\FundCluster;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FundClusterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FundCluster::create([
            'name' => '161',
        ]);

        FundCluster::create([
            'name' => '163',
        ]);

        FundCluster::create([
            'name' => '164',
        ]);
    }
}
