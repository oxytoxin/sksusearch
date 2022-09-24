<?php

namespace Database\Seeders;

use App\Models\ActivityLogType;
use Illuminate\Database\Seeder;

class ActivityLogTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ActivityLogType::create([
            'id' => ActivityLogType::DISBURSEMENT_VOUCHER_LOG,
            'name' => 'Disbursement Voucher Log',
        ]);
    }
}
