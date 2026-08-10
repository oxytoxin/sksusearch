<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        private const PARENT_FUNDS = ['101', '161', '163', '164'];

        public function up(): void
        {
            Schema::create('fund_cluster_groups', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->timestamps();
            });

            $this->seedFundClusterGroups();

            Schema::table('fund_clusters', function (Blueprint $table) {
                $table->foreignId('fund_cluster_group_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('fund_cluster_groups');
            });

            $this->backfillFundClusters();
        }

        public function down(): void
        {
            Schema::table('fund_clusters', function (Blueprint $table) {
                $table->dropConstrainedForeignId('fund_cluster_group_id');
            });

            Schema::dropIfExists('fund_cluster_groups');
        }

        private function seedFundClusterGroups(): void
        {
            $now = now();

            foreach (self::PARENT_FUNDS as $name) {
                DB::table('fund_cluster_groups')->updateOrInsert(
                    ['name' => $name],
                    ['created_at' => $now, 'updated_at' => $now],
                );
            }
        }

        private function backfillFundClusters(): void
        {
            $groupIds = DB::table('fund_cluster_groups')
                ->whereIn('name', self::PARENT_FUNDS)
                ->pluck('id', 'name');

            foreach (['101', '161', '163'] as $name) {
                if (! $groupIds->has($name)) {
                    continue;
                }

                DB::table('fund_clusters')
                    ->whereRaw('TRIM(name) = ?', [$name])
                    ->update(['fund_cluster_group_id' => $groupIds[$name]]);
            }

            if (! $groupIds->has('164')) {
                return;
            }

            DB::table('fund_clusters')
                ->whereRaw('TRIM(name) LIKE ?', ['164%'])
                ->update(['fund_cluster_group_id' => $groupIds['164']]);
        }
    };
