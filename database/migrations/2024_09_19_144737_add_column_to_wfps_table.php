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
        Schema::table('wfps', function (Blueprint $table) {
            //add column total after specify_fund_source nullable
            $table->decimal('total_allocated_fund', 10, 2)->after('specify_fund_source')->nullable();
            $table->decimal('program_allocated', 10, 2)->after('specify_fund_source')->nullable();
            $table->decimal('balance', 10, 2)->after('specify_fund_source')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wfps', function (Blueprint $table) {
            $table->dropColumn('total_allocated_fund');
            $table->dropColumn('program_allocated');
            $table->dropColumn('balance');
        });
    }
};
