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
        Schema::create('fund_draft_amounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fund_draft_id')->constrained();
            $table->integer('category_group_id')->nullable();
            $table->string('category_group')->nullable();
            $table->string('initial_amount')->nullable();
            $table->integer('current_total')->nullable();
            $table->string('balance')->nullable();
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
        Schema::dropIfExists('fund_draft_amounts');
    }
};
