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
        Schema::table('ca_reminder_steps', function (Blueprint $table) {
            $table->timestamp('fmr_date')->nullable();
            $table->timestamp('fmd_date')->nullable();
            $table->timestamp('sco_date')->nullable();
            $table->timestamp('fd_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ca_reminder_steps', function (Blueprint $table) {
            $table->dropColumn('fmr_date');
            $table->dropColumn('fmd_date');
            $table->dropColumn('sco_date');
            $table->dropColumn('fd_date');
        });
    }
};
