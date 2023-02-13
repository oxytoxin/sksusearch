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
            $table->date('date_of_travel_to')->after('date_of_travel')->nullable();
            $table->renameColumn('date_of_travel', 'date_of_travel_from');
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
            $table->dropColumn('date_of_travel_to');
            $table->renameColumn('date_of_travel_from', 'date_of_travel');
        });
    }
};
