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
        Schema::create('offices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->foreignId('campus_id')->index();
            $table->foreignId('head_id')->index()->nullable();
            $table->foreignId('admin_user_id')->index()->nullable();
            $table->foreignId('OIC_id_1')->index()->nullable();
            $table->foreignId('OIC_id_2')->index()->nullable();
            $table->foreignId('OIC_id_3')->index()->nullable();
            $table->string('email')->unique()->nullable();
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
        Schema::dropIfExists('offices');
    }
};
