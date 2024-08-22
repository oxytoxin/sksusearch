<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fund_allocations', function (Blueprint $table) {
            $table->foreignId('wpf_type_id')->nullable()->after('cost_center_id')->constrained();
            $table->renameColumn('amount', 'initial_amount');
            $table->foreignId('fund_cluster_w_f_p_s_id')->nullable()->after('wpf_type_id')->constrained();
            $table->foreignId('category_group_id')->nullable()->after('fund_cluster_w_f_p_s_id')->constrained();
            $table->decimal('adjustment_amount', 15, 2)->nullable()->after('amount');
            $table->decimal('adjusted_amount', 15, 2)->nullable()->after('adjustment_amount');
            $table->text('description')->nullable()->after('category_group_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fund_allocations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('wpf_type_id');
            $table->renameColumn('initial_amount', 'amount');
            $table->dropConstrainedForeignId('wfp_fund_cluster_id');
            $table->dropConstrainedForeignId('category_group_id');
            $table->dropColumn('adjustment_amount');
            $table->dropColumn('adjusted_amount');
            $table->dropColumn('description');
        });
    }
};
