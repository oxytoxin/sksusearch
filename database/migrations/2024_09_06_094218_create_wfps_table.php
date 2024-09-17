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
        Schema::create('wfps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cost_center_id')->constrained();
            $table->foreignId('wpf_type_id')->constrained();
            $table->foreignId('fund_cluster_w_f_p_s_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->string('fund_description')->nullable();
            $table->string('source_fund')->nullable();
            $table->string('confirm_fund_source')->nullable();
            $table->string('specify_fund_source')->nullable();
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
        Schema::dropIfExists('wfps');
    }
};
