<?php

namespace Database\Seeders;

use App\Models\BudgetCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BudgetCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BudgetCategory::create([
            'name' => 'Supplies & Semi-Expendables',
        ]);

        BudgetCategory::create([
            'name' => 'MOOE',
        ]);

        BudgetCategory::create([
            'name' => 'Trainings',
        ]);

        BudgetCategory::create([
            'name' => 'Machine & Equipment, Furniture & Fixtures, Bio, Vehicles',
        ]);

        BudgetCategory::create([
            'name' => 'Buildings & Infrastructure',
        ]);

    }
}
