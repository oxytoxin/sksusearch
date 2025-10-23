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
        Schema::create('activity_design_coordinators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_design_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employee_information_id')->constrained('employee_information');
            $table->string('name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_design_coordinators');
    }
};
