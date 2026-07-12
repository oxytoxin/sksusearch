<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('travel_orders', function (Blueprint $table) {
            $table->timestamp('submitted_at')->nullable()->after('tracking_code');
        });

        Schema::table('itineraries', function (Blueprint $table) {
            $table->timestamp('submitted_at')->nullable()->after('coverage');
        });

        DB::table('travel_orders')
            ->whereNull('submitted_at')
            ->update(['submitted_at' => DB::raw('created_at')]);

        DB::table('itineraries')
            ->whereNull('submitted_at')
            ->update(['submitted_at' => DB::raw('created_at')]);
    }

    public function down(): void
    {
        Schema::table('itineraries', function (Blueprint $table) {
            $table->dropColumn('submitted_at');
        });

        Schema::table('travel_orders', function (Blueprint $table) {
            $table->dropColumn('submitted_at');
        });
    }
};
