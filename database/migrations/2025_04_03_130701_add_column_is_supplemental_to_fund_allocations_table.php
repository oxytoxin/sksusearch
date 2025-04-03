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
            $table->boolean('is_supplemental')->default(0)->after('is_locked')->comment('1 = Supplemental Fund, 0 = Programmed Fund');
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
            $table->dropColumn('is_supplemental');
        });
    }
};
