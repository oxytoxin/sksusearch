<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('positions')->insert(
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
                    'code' => 'UPRES',
                    'description' => 'University President',
                ], //5
                [
                    'code' => 'CH',
                    'description' => 'Campus Head',
                ], //6
                [
                    'code' => 'BUDOFF',
                    'description' => 'Budget Officer',
                ], //7
                [
                    'code' => 'PRESEC',
                    'description' => "President's Secretary",
                ], //8
                [
                    'code' => 'FAC',
                    'description' => 'Faculty',
                ], //9
                [
                    'code' => 'STAFF',
                    'description' => 'Staff',
                ], //10
                [
                    'code' => 'ICUOFF',
                    'description' => 'ICU Officer',
                ], //11
                [
                    'code' => 'DIR',
                    'description' => 'Director',
                ], //12
                [
                    'code' => 'VP',
                    'description' => 'Vice President',
                ], //13
                [
                    'code' => 'UBSD',
                    'description' => 'University Board Secretary & Director',
                ], //14
                [
                    'code' => 'CHIEF',
                    'description' => 'Chief',
                ], //15
                [
                    'code' => 'ASSTDIR',
                    'description' => 'Asst. Director',
                ], //16
                [
                    'code' => 'UREG',
                    'description' => 'University Registrar',
                ], //17
                [
                    'code' => 'DEAN',
                    'description' => 'Dean',
                ], //18
                [
                    'code' => 'CHMAN',
                    'description' => 'Chairman',
                ], //19
                [
                    'code' => 'BUDOFFIII',
                    'description' => 'Budget Officer III',
                ], //20
                [
                    'code' => 'UCASH',
                    'description' => 'University Cashier',
                ], //21
                [
                    'code' => 'ACCTOFF',
                    'description' => 'Accounting Officer',
                ], //22
                [
                    'code' => 'ADOFF',
                    'description' => 'Admin Officer',
                ], //23
                [
                    'code' => 'ARC',
                    'description' => 'Archiver',
                ], //24
                [
                    'code' => 'REG',
                    'description' => 'Registrar',
                ], //25

            ]
        );
    }
}
