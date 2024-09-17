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
        Schema::create('mfo_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('m_f_o_s_id')->nullable()->constrained();
            $table->foreignId('fund_cluster_w_f_p_s_id')->nullable()->constrained();
            $table->string('name');
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
        Schema::dropIfExists('mfo_fees');
    }
};
