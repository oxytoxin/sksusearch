<?php

namespace Database\Seeders;

use App\Models\CategoryItems;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $supplies = [
            ['name' => 'Office Supplies Inventory', 'uacs_code' => '1040401000'],
            ['name' => 'Accountable Forms, Plates and Stickers Inventory', 'uacs_code' => '1040402000'],
            ['name' => 'Non-Accountable Forms Inventory', 'uacs_code' => '1040403000'],
            ['name' => 'Animal/Zoological Supplies Inventory', 'uacs_code' => '1040404000'],
            ['name' => 'Food Supplies Inventory', 'uacs_code' => '1040405000'],
            ['name' => 'Drugs and Medicines Inventory', 'uacs_code' => '1040406000'],
            ['name' => 'Medical, Dental and Laboratory Supplies Inventory', 'uacs_code' => '1040407000'],
            ['name' => 'Fuel, Oil and Lubricants Inventory', 'uacs_code' => '1040408000'],
            ['name' => 'Agricultural and Marine Supplies Inventory', 'uacs_code' => '1040409000'],
            ['name' => 'Textbooks and Instructional Materials Inventory', 'uacs_code' => '1040410000'],
            ['name' => 'Military, Police and Traffic Supplies Inventory', 'uacs_code' => '1040411000'],
            ['name' => 'Chemical and Filtering Supplies Inventory', 'uacs_code' => '1040412000'],
            ['name' => 'Construction Materials Inventory', 'uacs_code' => '1040413000'],
            ['name' => 'Other Supplies and Materials Inventory', 'uacs_code' => '1040499000'],
            ['name' => 'Semi-Expendable Machinery', 'uacs_code' => '1040501000'],
            ['name' => 'Semi-Expendable Office Equipment', 'uacs_code' => '1040502000'],
            ['name' => 'Semi-Expendable Information and Communications Technology Equipment', 'uacs_code' => '1040503000'],
            ['name' => 'Semi-Expendable Agricultural and Forestry Equipment', 'uacs_code' => '1040504000'],
            ['name' => 'Semi-Expendable Marine and Fishery Equipment', 'uacs_code' => '1040505000'],
            ['name' => 'Semi-Expendable Airport Equipment', 'uacs_code' => '1040506000'],
            ['name' => 'Semi-Expendable Communications Equipment', 'uacs_code' => '1040507000'],
            ['name' => 'Semi-Expendable Disaster Response and Rescue Equipment', 'uacs_code' => '1040508000'],
            ['name' => 'Semi-Expendable Military, Police and Security Equipment', 'uacs_code' => '1040509000'],
            ['name' => 'Semi-Expendable Medical Equipment', 'uacs_code' => '1040510000'],
            ['name' => 'Semi-Expendable Printing Equipment', 'uacs_code' => '1040511000'],
            ['name' => 'Semi-Expendable Sports Equipment', 'uacs_code' => '1040512000'],
            ['name' => 'Semi-Expendable Technical and Scientific Equipment', 'uacs_code' => '1040513000'],
            ['name' => 'Semi-Expendable Other Machinery and Equipment', 'uacs_code' => '1040519000'],
            ['name' => 'Semi-Expendable Furniture and Fixtures', 'uacs_code' => '1040601000'],
            ['name' => 'Semi-Expendable Books', 'uacs_code' => '1040602000'],
        ];

        $mooe = [
            ['name' => 'Research, Exploration and Development Expenses', 'uacs_code' => '5020702000'],
            ['name' => 'Demolition and Relocation Expenses', 'uacs_code' => '5020801000'],
            ['name' => 'Desilting and Dredging Expenses', 'uacs_code' => '5020802000'],
            ['name' => 'Extraordinary and Miscellaneous Expenses', 'uacs_code' => '5021003000'],
            ['name' => 'Legal Services', 'uacs_code' => '5021101000'],
            ['name' => 'Auditing Services', 'uacs_code' => '5021102000'],
            ['name' => 'Consultancy Services', 'uacs_code' => '5021103000'],
            ['name' => 'Other Professional Services', 'uacs_code' => '5021199000'],
            ['name' => 'Environment/Sanitary Services', 'uacs_code' => '5021201000'],
            ['name' => 'Janitorial Services', 'uacs_code' => '5021202000'],
            ['name' => 'Security Services', 'uacs_code' => '5021203000'],
            ['name' => 'Other General Services', 'uacs_code' => '5021299000'],
            ['name' => 'Repairs and Maintenance-Land Improvements', 'uacs_code' => '5021302000'],
            ['name' => 'Repairs and Maintenance-Infrastructure Assets', 'uacs_code' => '5021303000'],
            ['name' => 'Repairs and Maintenance-Buildings and Other Structures', 'uacs_code' => '5021304000'],
            ['name' => 'Repairs and Maintenance-Machinery and Equipment', 'uacs_code' => '5021305000'],
            ['name' => 'Repairs and Maintenance-Transportation Equipment', 'uacs_code' => '5021306000'],
            ['name' => 'Repairs and Maintenance-Furniture and Fixtures', 'uacs_code' => '5021307000'],
            ['name' => 'Repairs and Maintenance-Leased Assets', 'uacs_code' => '5021308000'],
            ['name' => 'Repairs and Maintenance-Semi-Expendable Machinery and Equipment', 'uacs_code' => '5021321000'],
            ['name' => 'Repairs and Maintenance-Semi-Expendable Furniture, Fixtures and Books', 'uacs_code' => '5021322000'],
            ['name' => 'Repairs and Maintenance-Other Property, Plant and Equipment', 'uacs_code' => '5021399000'],
            ['name' => 'Labor and Wages', 'uacs_code' => '5021601000'],
            ['name' => 'Advertising Expenses', 'uacs_code' => '5029901000'],
            ['name' => 'Printing and Publication Expenses', 'uacs_code' => '5029902000'],
            ['name' => 'Representation Expenses', 'uacs_code' => '5029903000'],
            ['name' => 'Transportation and Delivery Expenses', 'uacs_code' => '5029904000'],
            ['name' => 'Rent/Lease Expenses', 'uacs_code' => '5029905000'],
            ['name' => 'Membership Dues and Contributions to Organizations', 'uacs_code' => '5029906000'],
            ['name' => 'Subscription Expenses', 'uacs_code' => '5029907000'],
            ['name' => 'Donations', 'uacs_code' => '5029908000'],
            ['name' => 'Litigation/Acquired Assets Expenses', 'uacs_code' => '5029909000'],
            ['name' => 'Website Maintenance', 'uacs_code' => '5029999001'],
            ['name' => 'Other Maintenance and Operating Expenses', 'uacs_code' => '5029999002'],
        ];

        $trainings = [
            ['name' => 'Traveling Expenses-Local', 'uacs_code' => '5020101000'],
            ['name' => 'Traveling Expenses-Foreign', 'uacs_code' => '5020102000'],
            ['name' => 'Training Expenses', 'uacs_code' => '5020201000'],
            ['name' => 'Water Expenses', 'uacs_code' => '5020401000'],
            ['name' => 'Electricity Expenses', 'uacs_code' => '5020402000'],
            ['name' => 'Gas/Heating Expenses', 'uacs_code' => '5020403000'],
            ['name' => 'Other Utility Expenses', 'uacs_code' => '5020499000'],
            ['name' => 'Postage and Courier Expenses', 'uacs_code' => '5020501000'],
            ['name' => 'Telephone Expenses', 'uacs_code' => '5020502000'],
            ['name' => 'Internet Subscription Expenses', 'uacs_code' => '5020503000'],
            ['name' => 'Awards/Rewards Expenses', 'uacs_code' => '5020601000'],
            ['name' => 'Prizes', 'uacs_code' => '5020602000'],
            ['name' => 'Survey Expenses', 'uacs_code' => '5020701000'],
            ['name' => 'Research, Exploration and Development Expenses', 'uacs_code' => '5020702000'],
            ['name' => 'Extraordinary and Miscellaneous Expenses', 'uacs_code' => '5021003000'],
            ['name' => 'Legal Services', 'uacs_code' => '5021101000'],
            ['name' => 'Auditing Services', 'uacs_code' => '5021102000'],
            ['name' => 'Consultancy Services', 'uacs_code' => '5021103000'],
            ['name' => 'Other Professional Services', 'uacs_code' => '5021199000'],
            ['name' => 'Labor and Wages', 'uacs_code' => '5021601000'],
            ['name' => 'Advertising Expenses', 'uacs_code' => '5029901000'],
            ['name' => 'Printing and Publication Expenses', 'uacs_code' => '5029902000'],
            ['name' => 'Representation Expenses', 'uacs_code' => '5029903000'],
            ['name' => 'Transportation and Delivery Expenses', 'uacs_code' => '5029904000'],
            ['name' => 'Rent/Lease Expenses', 'uacs_code' => '5029905000'],
            ['name' => 'Subscription Expenses', 'uacs_code' => '5029907000'],
            ['name' => 'Other Maintenance and Operating Expenses', 'uacs_code' => '5029999002'],
            ['name' => 'Office Supplies Inventory', 'uacs_code' => '1040401000'],
            ['name' => 'Animal/Zoological Supplies Inventory', 'uacs_code' => '1040404000'],
            ['name' => 'Food Supplies Inventory', 'uacs_code' => '1040405000'],
            ['name' => 'Drugs and Medicines Inventory', 'uacs_code' => '1040406000'],
            ['name' => 'Medical, Dental and Laboratory Supplies Inventory', 'uacs_code' => '1040407000'],
            ['name' => 'Fuel, Oil and Lubricants Inventory', 'uacs_code' => '1040408000'],
            ['name' => 'Agricultural and Marine Supplies Inventory', 'uacs_code' => '1040409000'],
            ['name' => 'Textbooks and Instructional Materials Inventory', 'uacs_code' => '1040410000'],
            ['name' => 'Military, Police and Traffic Supplies Inventory', 'uacs_code' => '1040411000'],
            ['name' => 'Chemical and Filtering Supplies Inventory', 'uacs_code' => '1040412000'],
            ['name' => 'Construction Materials Inventory', 'uacs_code' => '1040413000'],
            ['name' => 'Other Supplies and Materials Inventory', 'uacs_code' => '1040499000'],
            ['name' => 'Semi-Expendable Machinery', 'uacs_code' => '1040501000'],
            ['name' => 'Semi-Expendable Office Equipment', 'uacs_code' => '1040502000'],
            ['name' => 'Semi-Expendable Information and Communications Technology Equipment', 'uacs_code' => '1040503000'],
            ['name' => 'Semi-Expendable Agricultural and Forestry Equipment', 'uacs_code' => '1040504000'],
            ['name' => 'Semi-Expendable Marine and Fishery Equipment', 'uacs_code' => '1040505000'],
            ['name' => 'Semi-Expendable Airport Equipment', 'uacs_code' => '1040506000'],
            ['name' => 'Semi-Expendable Communications Equipment', 'uacs_code' => '1040507000'],
            ['name' => 'Semi-Expendable Disaster Response and Rescue Equipment', 'uacs_code' => '1040508000'],
            ['name' => 'Semi-Expendable Military, Police and Security Equipment', 'uacs_code' => '1040509000'],
            ['name' => 'Semi-Expendable Medical Equipment', 'uacs_code' => '1040510000'],
            ['name' => 'Semi-Expendable Printing Equipment', 'uacs_code' => '1040511000'],
            ['name' => 'Semi-Expendable Sports Equipment', 'uacs_code' => '1040512000'],
            ['name' => 'Semi-Expendable Technical and Scientific Equipment', 'uacs_code' => '1040513000'],
            ['name' => 'Semi-Expendable Other Machinery and Equipment', 'uacs_code' => '1040519000'],
            ['name' => 'Semi-Expendable Furniture and Fixtures', 'uacs_code' => '1040601000'],
            ['name' => 'Semi-Expendable Books', 'uacs_code' => '1040602000'],
        ];

        $machines = [
            ['name' => 'Machinery', 'uacs_code' => '1060501000'],
            ['name' => 'Office Equipment', 'uacs_code' => '1060502000'],
            ['name' => 'Information and Communications Technology Equipment', 'uacs_code' => '1060503000'],
            ['name' => 'Agricultural and Forestry Equipment', 'uacs_code' => '1060504000'],
            ['name' => 'Marine and Fishery Equipment', 'uacs_code' => '1060505000'],
            ['name' => 'Airport Equipment', 'uacs_code' => '1060506000'],
            ['name' => 'Communication Equipment', 'uacs_code' => '1060507000'],
            ['name' => 'Construction and Heavy Equipment', 'uacs_code' => '1060508000'],
            ['name' => 'Disaster Response and Rescue Equipment', 'uacs_code' => '1060509000'],
            ['name' => 'Military, Police and Security Equipment', 'uacs_code' => '1060510000'],
            ['name' => 'Medical Equipment', 'uacs_code' => '1060511000'],
            ['name' => 'Printing Equipment', 'uacs_code' => '1060512000'],
            ['name' => 'Sports Equipment', 'uacs_code' => '1060513000'],
            ['name' => 'Technical and Scientific Equipment', 'uacs_code' => '1060514000'],
            ['name' => 'Other Machinery and Equipment', 'uacs_code' => '1060599000'],
            ['name' => 'Motor Vehicles', 'uacs_code' => '1060601000'],
            ['name' => 'Other Transportation Equipment', 'uacs_code' => '1060699000'],
            ['name' => 'Furniture and Fixtures', 'uacs_code' => '1060701000'],
            ['name' => 'Books', 'uacs_code' => '1060702000'],
            ['name' => 'Work/Zoo Animals', 'uacs_code' => '1069801000'],
            ['name' => 'Other Property, Plant and Equipment', 'uacs_code' => '1069899000'],
            ['name' => 'Breeding Stocks', 'uacs_code' => '1070101000'],
            ['name' => 'Livestock', 'uacs_code' => '1070102000'],
            ['name' => 'Trees, Plants and Crops', 'uacs_code' => '1070103000'],
            ['name' => 'Aquaculture', 'uacs_code' => '1070104000'],
            ['name' => 'Other Bearer Biological Assets', 'uacs_code' => '1070199000'],
            ['name' => 'Patents/Copyrights', 'uacs_code' => '1080101000'],
            ['name' => 'Computer Software', 'uacs_code' => '1080102000'],
            ['name' => 'Websites', 'uacs_code' => '1080103000'],
            ['name' => 'Other Intangible Assets', 'uacs_code' => '1080198000'],
        ];

        $buildings = [
            ['name' => 'Land Improvements, Aquaculture Structures', 'uacs_code' => '1060201000'],
            ['name' => 'Land Improvements, Reforestation Projects', 'uacs_code' => '1060202000'],
            ['name' => 'Other Land Improvements', 'uacs_code' => '1060299000'],
            ['name' => 'Road Networks', 'uacs_code' => '1060301000'],
            ['name' => 'Flood Control Systems', 'uacs_code' => '1060302000'],
            ['name' => 'Sewer Systems', 'uacs_code' => '1060303000'],
            ['name' => 'Water Supply Systems', 'uacs_code' => '1060304000'],
            ['name' => 'Power Supply Systems', 'uacs_code' => '1060305000'],
            ['name' => 'Communications Networks', 'uacs_code' => '1060306000'],
            ['name' => 'Parks, Plazas and Monuments', 'uacs_code' => '1060309000'],
            ['name' => 'Other Infrastructure Assets', 'uacs_code' => '1060399000'],
            ['name' => 'Buildings', 'uacs_code' => '1060401000'],
            ['name' => 'School Buildings', 'uacs_code' => '1060402000'],
            ['name' => 'Hospitals and Health Centers', 'uacs_code' => '1060403000'],
            ['name' => 'Markets', 'uacs_code' => '1060404000'],
            ['name' => 'Slaughterhouses', 'uacs_code' => '1060405000'],
            ['name' => 'Hostels and Dormitories', 'uacs_code' => '1060406000'],
            ['name' => 'Other Structures', 'uacs_code' => '1060499000'],
        ];



        foreach ($supplies as $item) {
            CategoryItems::create([
                'budget_category_id' => 1,
                'name' => $item['name'],
                'uacs_code' => $item['uacs_code'],
            ]);
        }

        foreach ($mooe as $item) {
            CategoryItems::create([
                'budget_category_id' => 2,
                'name' => $item['name'],
                'uacs_code' => $item['uacs_code'],
            ]);
        }

        foreach ($trainings as $item) {
            CategoryItems::create([
                'budget_category_id' => 3,
                'name' => $item['name'],
                'uacs_code' => $item['uacs_code'],
            ]);
        }

        foreach ($machines as $item) {
            CategoryItems::create([
                'budget_category_id' => 4,
                'name' => $item['name'],
                'uacs_code' => $item['uacs_code'],
            ]);
        }

        foreach ($buildings as $item) {
            CategoryItems::create([
                'budget_category_id' => 5,
                'name' => $item['name'],
                'uacs_code' => $item['uacs_code'],
            ]);
        }
    }
}
