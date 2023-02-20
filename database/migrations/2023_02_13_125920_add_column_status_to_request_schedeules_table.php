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
        Schema::table('request_schedules', function (Blueprint $table) {
            $table->string('status')->after('time_end')->nullable();
            $table->text('remarks')->after('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('request_schedules', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('remarks');
        });
    }
};
