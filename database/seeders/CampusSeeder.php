<?php

namespace Database\Seeders;

use App\Models\Campus;
use Illuminate\Database\Seeder;

class CampusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //ACCESS CAMPUS 1
        Campus::create([
            'name' => 'ACCESS',
            'address' => 'EJC Montilla, Tacurong City, 9800, Philippines',
            'campus_code' => 'ACSC',
        ]);

        //TACURONG CAMPUS 2
        Campus::create([
            'name' => 'Tacurong',
            'address' => 'Pan-Philippine Highway, Tacurong City, 9800, Sultan Kudarat',
            'campus_code' => 'TACC',
            'admin_user_id' => '175',
        ]);

        //ISULAN CAMPUS 3
        Campus::create([
            'name' => 'Isulan',
            'address' => 'Isulan, 9805, Sultan Kudarat',
            'campus_code' => 'ISUC',
            'admin_user_id' => '264',
        ]);

        //KALAMANSIG CAMPUS 4
        Campus::create([
            'name' => 'Kalamansig',
            'address' => 'Kalamansig, 9808, Sultan Kudarat',
            'campus_code' => 'KALC',
            'admin_user_id' => '169',
        ]);

        //LUTAYAN CAMPUS 5
        Campus::create([
            'name' => 'Lutayan',
            'address' => 'Blingkong, Lutayan, 9803, Sultan Kudarat',
            'campus_code' => 'LUTC',
            'admin_user_id' => '295',
        ]);

        //PALIMBANG CAMPUS 6
        Campus::create([
            'name' => 'Palimbang',
            'address' => 'Palimbang, 9809, Sultan Kudarat',
            'campus_code' => 'PALC',
            'admin_user_id' => '397',
        ]);

        //BAGUMBAYAN CAMPUS 7
        Campus::create([
            'name' => 'Bagumbayan',
            'address' => 'Bagumbayan, 9801, Sultan Kudarat',
            'campus_code' => 'BAGC',
            'admin_user_id' => '276',
        ]);
    }
}
