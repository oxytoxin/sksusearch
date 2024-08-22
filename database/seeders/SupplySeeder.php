<?php

namespace Database\Seeders;

use App\Models\Supply;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $supplies = [
            ['category_item_id' => 1, 'category_group_id' => 1,'supply_code' => 'OF-089', 'particulars' => 'Pencil', 'unit_cost' => 7, 'is_ppmp' => true],
            ['category_item_id' => 1, 'category_group_id' => 1,'supply_code' => 'OF-031', 'particulars' => 'Pen (black)', 'unit_cost' => 11, 'is_ppmp' => true],
            ['category_item_id' => 1, 'category_group_id' => 1,'supply_code' => 'OF-032', 'particulars' => 'Pen (blue)', 'unit_cost' => 11, 'is_ppmp' => true],
            ['category_item_id' => 1, 'category_group_id' => 1,'supply_code' => 'OF-006', 'particulars' => 'Printing paper, A4', 'unit_cost' => 540, 'is_ppmp' => true],
            ['category_item_id' => 1, 'category_group_id' => 1,'supply_code' => 'OF-007', 'particulars' => 'Printing paper, Long (8.5x13)', 'unit_cost' => 560, 'is_ppmp' => true],
            ['category_item_id' => 6, 'category_group_id' => 2,'supply_code' => 'MD-034', 'particulars' => 'Alaxan FR', 'unit_cost' => 17, 'is_ppmp' => true],
            ['category_item_id' => 6, 'category_group_id' => 2,'supply_code' => 'OF-007', 'particulars' => 'Biogesic', 'unit_cost' => 8, 'is_ppmp' => true],
            ['category_item_id' => 14, 'category_group_id' => 2,'supply_code' => 'OS-289', 'particulars' => 'Feather duster', 'unit_cost' => 22, 'is_ppmp' => true],
            ['category_item_id' => 14, 'category_group_id' => 2,'supply_code' => 'OS-565', 'particulars' => 'Water bucket', 'unit_cost' => 45, 'is_ppmp' => true],
            ['category_item_id' => 16, 'category_group_id' => 2,'supply_code' => 'SME-003', 'particulars' => 'Water dispenser', 'unit_cost' => 4200, 'is_ppmp' => true],
            ['category_item_id' => 16, 'category_group_id' => 2,'supply_code' => 'SME-001', 'particulars' => 'Air conditioner, split type', 'unit_cost' => 34000, 'is_ppmp' => true],
            ['category_item_id' => 17, 'category_group_id' => 2,'supply_code' => 'SME-007', 'particulars' => 'Laptop computer, RX-FH849J0KL', 'unit_cost' => 48000, 'is_ppmp' => true],
            ['category_item_id' => 17, 'category_group_id' => 2,'supply_code' => 'SME-008', 'particulars' => 'Desktop computer', 'unit_cost' => 45, 'is_ppmp' => true],
            ['category_item_id' => 29, 'category_group_id' => 2,'supply_code' => 'SME-028', 'particulars' => 'Office chair, high back', 'unit_cost' => 6500, 'is_ppmp' => true],
            ['category_item_id' => 29, 'category_group_id' => 2,'supply_code' => 'SME-037', 'particulars' => 'Office table, 5ft. X 3ft.', 'unit_cost' => 11280, 'is_ppmp' => true],
            ['category_item_id' => 30, 'category_group_id' => 2,'supply_code' => 'LIB-1464', 'particulars' => 'Magellan\'s Adventures', 'unit_cost' => 748, 'is_ppmp' => true],
            ['category_item_id' => 121, 'category_group_id' => 3,'supply_code' => 'ME-002', 'particulars' => 'Photocopier', 'unit_cost' => 64750, 'is_ppmp' => true],
            ['category_item_id' => 122, 'category_group_id' => 3,'supply_code' => 'ME-003', 'particulars' => 'Laptop computer, CLRN908JFLM', 'unit_cost' => 67000, 'is_ppmp' => true],
            ['category_item_id' => 122, 'category_group_id' => 3,'supply_code' => 'ME-004', 'particulars' => 'Desktop all-in-one PC', 'unit_cost' => 72000, 'is_ppmp' => true],
            ['category_item_id' => 135, 'category_group_id' => 3,'supply_code' => 'VEH-014', 'particulars' => 'Bongo', 'unit_cost' => 840000, 'is_ppmp' => true],
            ['category_item_id' => 135, 'category_group_id' => 3,'supply_code' => 'VEH-018', 'particulars' => 'Minibus', 'unit_cost' => 4320000, 'is_ppmp' => true],
            ['category_item_id' => 137, 'category_group_id' => 4,'supply_code' => 'FF-006', 'particulars' => 'Compactor (mobile cabinet)', 'unit_cost' => 300000, 'is_ppmp' => true],
            ['category_item_id' => 82, 'category_group_id' => 5,'supply_code' => 'OTH-049', 'particulars' => 'ISO 9001:2015 consultant', 'unit_cost' => 500000, 'is_ppmp' => true],
            ['category_item_id' => 83, 'category_group_id' => 5,'supply_code' => null, 'particulars' => 'Other Professional Services', 'unit_cost' => null, 'is_ppmp' => false],
            ['category_item_id' => 83, 'category_group_id' => 5,'supply_code' => null, 'particulars' => 'Other Professional Services', 'unit_cost' => null, 'is_ppmp' => false],
            ['category_item_id' => 87, 'category_group_id' => 6,'supply_code' => null, 'particulars' => 'Representation Expenses', 'unit_cost' => null, 'is_ppmp' => false],
            ['category_item_id' => 68, 'category_group_id' => 7,'supply_code' => null, 'particulars' => 'Water Expenses', 'unit_cost' => null, 'is_ppmp' => false],
            ['category_item_id' => 69, 'category_group_id' => 7,'supply_code' => null, 'particulars' => 'Electricity Expenses', 'unit_cost' => null, 'is_ppmp' => false],
            ['category_item_id' => 65, 'category_group_id' => 8,'supply_code' => null, 'particulars' => 'Traveling Expenses-Local', 'unit_cost' => null, 'is_ppmp' => false],
            ['category_item_id' => 66, 'category_group_id' => 9,'supply_code' => null, 'particulars' => 'Traveling Expenses-Foreign', 'unit_cost' => null, 'is_ppmp' => false],
        ];

        foreach ($supplies as $supply) {
            Supply::create($supply);
        }
    }
}
