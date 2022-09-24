<?php

namespace Database\Seeders;

use App\Models\Mot;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Mot::create([
            'name' => 'Tricycle',
        ]);

        Mot::create([
            'name' => 'Bus',
        ]);

        Mot::create([
            'name' => 'Taxi',
        ]);

        Mot::create([
            'name' => 'Habal-habal',
        ]);

        Mot::create([
            'name' => 'Sikad',
        ]);

        Mot::create([
            'name' => 'Multicab',
        ]);

        Mot::create([
            'name' => 'Jeepney',
        ]);

        Mot::create([
            'name' => 'Airplane',
        ]);

        Mot::create([
            'name' => 'Van',
        ]);

        Mot::create([
            'name' => 'Train',
        ]);

        Mot::create([
            'name' => 'Grab',
        ]);
    }
}
