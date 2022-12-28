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
        Schema::create('archived_cheques', function (Blueprint $table) {
            $table->id();
            $table->text('cheque_number');
            $table->integer('cheque_amount');
            $table->date('cheque_date')->nullable();
            $table->tinyInteger('cheque_state')->nullable();
            $table->foreignId('building_id')->nullable()->index();
            $table->foreignId('shelf_id')->nullable()->index();
            $table->foreignId('drawer_id')->nullable()->index();
            $table->foreignId('folder_id')->nullable()->index();
            $table->foreignId('fund_cluster_id')->nullable()->index();
            $table->json('other_details')->nullable();
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
        Schema::dropIfExists('archived_cheques');
    }
};
