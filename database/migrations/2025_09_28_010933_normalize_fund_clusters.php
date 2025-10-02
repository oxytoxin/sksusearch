<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void
        {
            Schema::table('activity_designs', function (Blueprint $table) {
                $table->dropForeign('activity_designs_fund_cluster_id_foreign');
            });
            Schema::drop('fund_clusters');
            Schema::rename('fund_cluster_w_f_p_s', 'fund_clusters');

            Schema::table('cost_centers', function (Blueprint $table) {
                $table->dropForeign('cost_centers_fund_cluster_w_f_p_s_id_foreign');
                $table->renameColumn('fund_cluster_w_f_p_s_id', 'fund_cluster_id');
                $table->foreign('fund_cluster_id')->references('id')->on('fund_clusters');
            });
            Schema::table('fund_allocations', function (Blueprint $table) {
                $table->dropForeign('fund_allocations_fund_cluster_w_f_p_s_id_foreign');
                $table->renameColumn('fund_cluster_w_f_p_s_id', 'fund_cluster_id');
                $table->foreign('fund_cluster_id')->references('id')->on('fund_clusters');
            });
            Schema::table('mfo_fees', function (Blueprint $table) {
                $table->dropForeign('mfo_fees_fund_cluster_w_f_p_s_id_foreign');
                $table->renameColumn('fund_cluster_w_f_p_s_id', 'fund_cluster_id');
                $table->foreign('fund_cluster_id')->references('id')->on('fund_clusters');
            });
            Schema::table('wfps', function (Blueprint $table) {
                $table->dropForeign('wfps_fund_cluster_w_f_p_s_id_foreign');
                $table->renameColumn('fund_cluster_w_f_p_s_id', 'fund_cluster_id');
                $table->foreign('fund_cluster_id')->references('id')->on('fund_clusters');
            });
        }

        public function down(): void
        {
            Schema::table('', function (Blueprint $table) {
                //
            });
        }
    };
