<?php

namespace Database\Seeders;

use App\Models\MFO;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MFOSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MFO::create([
            'code' => 'GASS',
            'name' => 'General Admission and Support Services',
        ]);

        MFO::create([
            'code' => 'HES',
            'name' => 'Higher Education Services',
        ]);

        MFO::create([
            'code' => 'AES',
            'name' => 'Advanced Education Services',
        ]);

        MFO::create([
            'code' => 'RD',
            'name' => 'Research and Development',
        ]);

        MFO::create([
            'code' => 'ES',
            'name' => 'Extension Services',
        ]);

        MFO::create([
            'code' => 'LFP',
            'name' => 'Local Fund Projects',
        ]);
    }
}
