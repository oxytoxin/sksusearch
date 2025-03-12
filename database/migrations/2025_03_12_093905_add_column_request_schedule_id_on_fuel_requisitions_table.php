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
        Schema::table('fuel_requisitions', function (Blueprint $table) {
            $table->unsignedBigInteger('request_schedule_id')->nullable()->after('requested_by');
            $table->foreign('request_schedule_id')->references('id')->on('request_schedules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fuel_requisitions', function (Blueprint $table) {
            $table->dropForeign(['request_schedule_id']);
            $table->dropColumn('request_schedule_id');
        });
    }
};
