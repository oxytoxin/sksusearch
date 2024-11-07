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
            $table->boolean('is_approved')->default(false)->after('total_allocated_fund');
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
            $table->dropColumn('is_approved');
        });
    }
};
