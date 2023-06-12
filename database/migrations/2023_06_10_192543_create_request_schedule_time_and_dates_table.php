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
        Schema::create('request_schedule_time_and_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_schedule_id')->index()->nullable();
            $table->foreignId('vehicle_id')->index()->nullable();
            $table->date('travel_date');
            $table->time('time_from');
            $table->time('time_to');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('request_schedule_time_and_dates');
    }
};
