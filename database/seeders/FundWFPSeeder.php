<?php

    namespace Database\Seeders;

    use App\Models\FundCluster;
    use Illuminate\Database\Seeder;
    use Illuminate\Database\Console\Seeds\WithoutModelEvents;

    class FundWFPSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         *
         * @return void
         */
        public function run()
        {
            FundCluster::create([
                'name' => '101',
            ]);

            FundCluster::create([
                'name' => '161',
            ]);

            FundCluster::create([
                'name' => '163',
            ]);

            FundCluster::create([
                'name' => '164TF',
            ]);

            FundCluster::create([
                'name' => '164FF/OSF',
            ]);
        }
    }
