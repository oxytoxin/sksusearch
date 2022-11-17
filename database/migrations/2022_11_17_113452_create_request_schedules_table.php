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
        Schema::create('request_schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('request_type');
            $table->foreignId('travel_order_id')->index()->nullable();
            $table->foreignId('driver_id')->index();
            $table->foreignId('vehicle_id')->index();
            $table->text('purpose');
            $table->foreignId('philippine_region_id')->index()->nullable();
            $table->foreignId('philippine_province_id')->index()->nullable();
            $table->foreignId('philippine_city_id')->index()->nullable();
            $table->string('other_details')->nullable();
            $table->date('date_of_travel');
            $table->time('time_start');
            $table->time('time_end');
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
        Schema::dropIfExists('request_schedules');
    }
};
