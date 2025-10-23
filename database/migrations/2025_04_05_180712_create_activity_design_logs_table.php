<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        /**
         * Run the migrations.
         */
        public function up(): void
        {
            Schema::create('activity_design_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('activity_design_id')->constrained()->cascadeOnDelete();
                $table->foreignId('signatory_id')->nullable();
                $table->foreign('signatory_id', 'activity_design_logs_signatory_foreign')->references('id')->on('activity_design_signatories')->onDelete('cascade');
                $table->text('remarks');
                $table->text('comments')->nullable();
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('activity_design_logs');
        }
    };
