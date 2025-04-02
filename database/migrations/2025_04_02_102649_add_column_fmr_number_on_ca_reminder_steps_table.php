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
            $table->string('fmr_number')->nullable()->after('message');
            $table->string('fmd_number')->nullable()->after('fmr_number');
            $table->string('memorandum_number')->nullable()->after('fmd_number');
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
            $table->dropColumn('fmr_number');
            $table->dropColumn('fmd_number');
            $table->dropColumn('memorandum_number');
        });
    }
};
