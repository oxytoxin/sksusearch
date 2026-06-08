<?php

use App\Models\FundCluster;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Add fund cluster 101 (Regular Agency Fund) used by the COA aging schedule.
     * Additive and idempotent — only creates it if missing.
     */
    public function up()
    {
        FundCluster::firstOrCreate(['name' => '101']);
    }

    public function down()
    {
        FundCluster::where('name', '101')->delete();
    }
};
