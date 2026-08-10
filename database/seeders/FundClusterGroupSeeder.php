<?php

namespace Database\Seeders;

use App\Models\FundClusterGroup;
use Illuminate\Database\Seeder;

class FundClusterGroupSeeder extends Seeder
{
    public function run()
    {
        foreach (['101', '161', '163', '164'] as $name) {
            FundClusterGroup::firstOrCreate(['name' => $name]);
        }
    }
}
