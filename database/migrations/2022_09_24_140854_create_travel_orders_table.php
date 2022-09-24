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
        Schema::create('travel_orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('tracking_code')->unique();
            $table->foreignId('travel_order_type_id')->index();
            $table->date('date_from');
            $table->date('date_to');
            $table->text('purpose');
            $table->boolean('has_registration')->default(false);
            $table->integer('registration_amount')->default(0);
            $table->foreignId('philippine_region_id')->index()->nullable();
            $table->foreignId('philippine_province_id')->index()->nullable();
            $table->foreignId('philippine_city_id')->index()->nullable();
            $table->string('other_details')->nullable();
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
        Schema::dropIfExists('travel_orders');
    }
};
