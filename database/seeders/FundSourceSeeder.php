<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\FundClusterWFP;
use Illuminate\Database\Seeder;

class FundSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fundClusterWFP101 = FundClusterWFP::where('id', 1)->first();
        $fundClusterWFP101->fund_source = 'General Fund';
        $fundClusterWFP101->save();

        $fundClusterWFP161 = FundClusterWFP::where('id', 2)->first();
        $fundClusterWFP161->fund_source = 'Trust Fund';
        $fundClusterWFP161->save();

        $fundClusterWFP163 = FundClusterWFP::where('id', 3)->first();
        $fundClusterWFP163->fund_source = 'Internally-Generated Projects';
        $fundClusterWFP163->save();

        $fundClusterWFP164T1 = FundClusterWFP::where('id', 4)->first();
        $fundClusterWFP164T1->fund_source = 'Internally-Generated Income';
        $fundClusterWFP164T1->save();

        $fundClusterWFP164T2 = FundClusterWFP::where('id', 5)->first();
        $fundClusterWFP164T2->fund_source = 'Internally-Generated Income';
        $fundClusterWFP164T2->save();

        $fundClusterWFP164T3 = FundClusterWFP::where('id', 6)->first();
        $fundClusterWFP164T3->fund_source = 'Internally-Generated Income';
        $fundClusterWFP164T3->save();

        $fundClusterWFP164T4 = FundClusterWFP::where('id', 7)->first();
        $fundClusterWFP164T4->fund_source = 'Internally-Generated Income';
        $fundClusterWFP164T4->save();
    }
}
