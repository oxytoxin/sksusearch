<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PhilippinePlacesSeeder::class,
            RoleSeeder::class,
            CampusSeeder::class,
            OfficeSeeder::class,
            PositionSeeder::class,
            EmployeeInformationSeeder::class,
            DteSeeder::class,
            VoucherCategorySeeder::class,
            VoucherTypeSeeder::class,
            VoucherSubTypeSeeder::class,
            MopSeeder::class,
            DisbursementVoucherStepSeeder::class,
            TravelOrderTypeSeeder::class,
            MotSeeder::class,
            ActivityLogTypeSeeder::class,
        ]);
    }
}
