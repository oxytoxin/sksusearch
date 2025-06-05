<?php

use App\Models\FundClusterWFP;
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
        Schema::table('fund_cluster_w_f_p_s', function (Blueprint $table) {
            $table->integer('position')->default(0);
        });

        FundClusterWFP::where('id', 1)->update(['position' => 100]);
        FundClusterWFP::where('id', 2)->update(['position' => 200]);
        FundClusterWFP::where('id', 3)->update(['position' => 300]);
        FundClusterWFP::where('id',4)->update(['position' => 400]);
        FundClusterWFP::where('id',5)->update(['position'=> 600]);
        FundClusterWFP::where('id',6)->update(['position'=> 700]);
        FundClusterWFP::where('id',7)->update(['position'=> 500]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fund_cluster_w_f_p_s', function (Blueprint $table) {
            //
        });
    }
};
