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
        Schema::table('wfps', function (Blueprint $table) {
            $table->timestamp('status_last_updated_at')->nullable();
            $table->timestamp('initial_submitted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wfps', function (Blueprint $table) {
            //
        });
    }
};
