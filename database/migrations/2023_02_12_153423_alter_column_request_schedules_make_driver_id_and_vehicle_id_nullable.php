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
            $table->string('driver_id')->nullable()->change();
            $table->string('vehicle_id')->nullable()->change();
            $table->string('time_start')->nullable()->change();
            $table->string('time_end')->nullable()->change();
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
            $table->string('driver_id')->change();
            $table->string('vehicle_id')->change();
            $table->string('time_start')->change();
            $table->string('time_end')->change();
        });   
    }
};
