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
        Schema::create('activity_design_particulars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_design_id')->constrained()->cascadeOnDelete();
            $table->text('description');
            $table->decimal('amount', 12, 4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_design_particulars');
    }
};
