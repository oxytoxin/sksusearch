<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert(
            [
                [
                    'code' => 'ADMIN',
                    'description' => 'Admin',
                ], //1
                [
                    'code' => 'DH',
                    'description' => 'Department Head',
                ], //2
                [
                    'code' => 'SEC',
                    'description' => 'Secretary',
                ], //3
                [
                    'code' => 'ACCT',
                    'description' => 'Accountant',
                ], //4
                [
                    'code' => 'BUDOFF',
                    'description' => 'Budget Officer',
                ], //5
                [
                    'code' => 'PRES',
                    'description' => 'President',
                ], //6
                [
                    'code' => 'ARC',
                    'description' => 'Archiver',
                ], //7
            ]
        );
    }
}
