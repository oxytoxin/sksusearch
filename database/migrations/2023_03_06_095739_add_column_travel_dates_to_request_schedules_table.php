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
            $table->json('travel_dates')->after('date_of_travel_to')->nullable();
            $table->json('available_travel_dates')->after('travel_dates')->nullable();
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
            $table->dropColumn('travel_dates');
            $table->dropColumn('available_travel_dates');
        });
    }
};
