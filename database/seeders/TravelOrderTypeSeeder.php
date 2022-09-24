<?php

namespace Database\Seeders;

use App\Models\TravelOrderType;
use Illuminate\Database\Seeder;

class TravelOrderTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TravelOrderType::create([
            'name' => 'Official Business',
        ]);
        TravelOrderType::create([
            'name' => 'Official Time',
        ]);
    }
}
