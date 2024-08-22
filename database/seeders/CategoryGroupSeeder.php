<?php

namespace Database\Seeders;

use App\Models\CategoryGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groups = [
            ['name' => 'Office Supplies Inventory'],
            ['name' => 'Other Supplies'],
            ['name' => 'Machinery and Equipment'],
            ['name' => 'Furniture and Fixtures'],
            ['name' => 'Professional Services'],
            ['name' => 'Representation'],
            ['name' => 'Utility Expenses'],
            ['name' => 'Traveling Expenses-Local'],
            ['name' => 'Traveling Expenses-Foreign'],
        ];

        foreach ($groups as $item) {
            CategoryGroup::create([
                'name' => $item['name'],
            ]);
        }
    }
}
