<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('request_schedules', function (Blueprint $table) {
            $table->foreignId('vehicle_assigned_by')->nullable()->constrained('users');
            $table->foreignId('driver_assigned_by')->nullable()->constrained('users');
        });
    }

    public function down()
    {
        Schema::table('request_schedules', function (Blueprint $table) {
            $table->dropConstrainedForeignId('vehicle_assigned_by');
            $table->dropConstrainedForeignId('driver_assigned_by');
        });
    }
};
