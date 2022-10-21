<?php

namespace Database\Seeders;

use App\Models\OfficeGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OfficeGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OfficeGroup::create([
            'name' => 'Budget Offices',
        ]);
        OfficeGroup::create([
            'name' => 'Accounting Offices',
        ]);
        OfficeGroup::create([
            'name' => 'Internal Control Unit Offices',
        ]);
        OfficeGroup::create([
            'name' => "Cashier's Offices",
        ]);
        OfficeGroup::create([
            'name' => "President's Offices",
        ]);
    }
}
