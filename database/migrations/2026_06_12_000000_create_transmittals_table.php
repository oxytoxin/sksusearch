<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transmittals', function (Blueprint $table) {
            $table->id();
            $table->string('transmittal_number')->unique();
            $table->string('recipient'); // office/person the DVs are transmitted to
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('office_group_id')->nullable()->index(); // originating office group
            $table->foreignId('prepared_by')->constrained('users');
            $table->timestamp('acknowledged_at')->nullable();
            $table->string('acknowledged_by')->nullable(); // name of receiver who signed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transmittals');
    }
};
