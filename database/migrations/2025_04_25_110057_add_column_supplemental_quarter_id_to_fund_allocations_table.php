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
            $table->foreignId('supplemental_quarter_id')->nullable()->after('wpf_type_id');
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
            $table->dropForeign(['supplemental_quarter_id']);
            $table->dropColumn('supplemental_quarter_id');
        });
    }
};
