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
            $table->string('driver_id', 255)->nullable()->after('travel_order_id')->change();
            $table->string('vehicle_id', 255)->nullable()->after('driver_id')->change();
            $table->string('time_start', 255)->nullable()->after('date_of_travel')->change();
            $table->string('time_end', 255)->nullable()->after('time_start')->change();
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
            $table->string('driver_id', 255)->after('travel_order_id')->change();
            $table->string('vehicle_id', 255)->after('driver_id')->change();
            $table->string('time_start', 255)->after('date_of_travel')->change();
            $table->string('time_end', 255)->after('time_start')->change();
        });   
    }
};
