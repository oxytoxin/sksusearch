<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('activity_design_coordinators', function (Blueprint $table) {
            $table->foreignId('employee_information_id')->nullable()->after('activity_design_id')->change();
        });

        Schema::table('activity_design_participants', function (Blueprint $table) {
            $table->foreignId('employee_information_id')->nullable()->after('activity_design_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_design_participants', function (Blueprint $table) {
            //
        });
    }
};
