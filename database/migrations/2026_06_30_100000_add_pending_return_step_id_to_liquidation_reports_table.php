<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('liquidation_reports', function (Blueprint $table) {
            $table->unsignedBigInteger('pending_return_step_id')->nullable()->after('previous_step_id');
            $table->foreign('pending_return_step_id')->references('id')->on('liquidation_report_steps');
        });
    }

    public function down(): void
    {
        Schema::table('liquidation_reports', function (Blueprint $table) {
            $table->dropForeign(['pending_return_step_id']);
            $table->dropColumn('pending_return_step_id');
        });
    }
};
