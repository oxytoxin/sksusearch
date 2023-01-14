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
        Schema::create('liquidation_report_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('office_group_id')->nullable();
            $table->string('process');
            $table->string('recipient')->nullable();
            $table->string('sender')->nullable();
            $table->foreignId('office_id')->nullable();
            $table->foreignId('return_step_id')->default(1000);
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
        Schema::dropIfExists('liquidation_report_steps');
    }
};
