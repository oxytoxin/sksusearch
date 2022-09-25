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
        Schema::create('itenerary_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('itenerary_id')->index();
            $table->foreignId('mot_id')->index();
            $table->date('date');
            $table->string('place');
            $table->time('departure_time');
            $table->time('arrival_time');
            $table->integer('transportation_expenses');
            $table->integer('other_expenses')->default(0);
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
        Schema::dropIfExists('itenerary_entries');
    }
};
