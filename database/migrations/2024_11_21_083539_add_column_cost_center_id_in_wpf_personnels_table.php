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
        Schema::table('wpf_personnels', function (Blueprint $table) {
            $table->integer('cost_center_id')->after('head_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wpf_personnels', function (Blueprint $table) {
            $table->dropColumn('cost_center_id');
        });
    }
};
