<?php

namespace Database\Seeders;

use App\Models\Mop;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Mop::create([
            'name' => 'MDS Check',
        ]);

        Mop::create([
            'name' => 'Commercial Check',
        ]);

        Mop::create([
            'name' => 'ADA',
        ]);

        Mop::create([
            'name' => 'Others',
        ]);
    }
}
