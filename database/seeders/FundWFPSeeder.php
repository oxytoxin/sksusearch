<?php

    namespace Database\Seeders;

    use App\Models\FundCluster;
    use App\Models\FundClusterGroup;
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
            $groups = collect(['101', '161', '163', '164'])
                ->mapWithKeys(fn (string $name) => [
                    $name => FundClusterGroup::firstOrCreate(['name' => $name])->id,
                ]);

            foreach ([
                ['name' => '101', 'fund_cluster_group_id' => $groups['101']],
                ['name' => '161', 'fund_cluster_group_id' => $groups['161']],
                ['name' => '163', 'fund_cluster_group_id' => $groups['163']],
                ['name' => '164TF', 'fund_cluster_group_id' => $groups['164']],
                ['name' => '164FF/OSF', 'fund_cluster_group_id' => $groups['164']],
            ] as $fundCluster) {
                FundCluster::updateOrCreate(
                    ['name' => $fundCluster['name']],
                    ['fund_cluster_group_id' => $fundCluster['fund_cluster_group_id']],
                );
            }
        }
    }
