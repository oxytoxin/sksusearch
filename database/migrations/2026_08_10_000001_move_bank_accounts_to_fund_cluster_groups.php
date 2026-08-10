<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void
        {
            Schema::create('bank_account_fund_cluster_group', function (Blueprint $table) {
                $table->id();
                $table->foreignId('bank_account_id')->constrained()->cascadeOnDelete();
                $table->foreignId('fund_cluster_group_id')->constrained()->cascadeOnDelete();
                $table->timestamps();

                $table->unique(
                    ['bank_account_id', 'fund_cluster_group_id'],
                    'bank_account_fund_cluster_group_unique',
                );
            });

            $this->backfillFundClusterGroupPivot();

            Schema::dropIfExists('bank_account_fund_cluster');
        }

        public function down(): void
        {
            if (! Schema::hasTable('bank_account_fund_cluster')) {
                Schema::create('bank_account_fund_cluster', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('bank_account_id')->constrained();
                    $table->foreignId('fund_cluster_id')->constrained();
                    $table->timestamps();
                });
            }

            $this->backfillFundClusterPivot();

            Schema::dropIfExists('bank_account_fund_cluster_group');
        }

        private function backfillFundClusterGroupPivot(): void
        {
            if (! Schema::hasTable('bank_account_fund_cluster')) {
                return;
            }

            $now = now();

            DB::table('bank_account_fund_cluster')
                ->join('fund_clusters', 'fund_clusters.id', '=', 'bank_account_fund_cluster.fund_cluster_id')
                ->whereNotNull('fund_clusters.fund_cluster_group_id')
                ->distinct()
                ->select([
                    'bank_account_fund_cluster.bank_account_id',
                    'fund_clusters.fund_cluster_group_id',
                ])
                ->orderBy('bank_account_fund_cluster.bank_account_id')
                ->get()
                ->each(function ($pivot) use ($now) {
                    DB::table('bank_account_fund_cluster_group')->updateOrInsert(
                        [
                            'bank_account_id' => $pivot->bank_account_id,
                            'fund_cluster_group_id' => $pivot->fund_cluster_group_id,
                        ],
                        ['created_at' => $now, 'updated_at' => $now],
                    );
                });
        }

        private function backfillFundClusterPivot(): void
        {
            if (! Schema::hasTable('bank_account_fund_cluster_group')) {
                return;
            }

            $now = now();
            $fundClusterIdsByGroup = DB::table('fund_clusters')
                ->whereNotNull('fund_cluster_group_id')
                ->selectRaw('MIN(id) as id, fund_cluster_group_id')
                ->groupBy('fund_cluster_group_id')
                ->pluck('id', 'fund_cluster_group_id');

            DB::table('bank_account_fund_cluster_group')
                ->orderBy('bank_account_id')
                ->get()
                ->each(function ($pivot) use ($fundClusterIdsByGroup, $now) {
                    if (! $fundClusterIdsByGroup->has($pivot->fund_cluster_group_id)) {
                        return;
                    }

                    DB::table('bank_account_fund_cluster')->updateOrInsert(
                        [
                            'bank_account_id' => $pivot->bank_account_id,
                            'fund_cluster_id' => $fundClusterIdsByGroup[$pivot->fund_cluster_group_id],
                        ],
                        ['created_at' => $now, 'updated_at' => $now],
                    );
                });
        }
    };
