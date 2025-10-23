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
            Schema::create('activity_design_signatories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('activity_design_signatory_group_id');
                $table->foreign('activity_design_signatory_group_id', 'activity_design_signatory_foreign')->references('id')->on('activity_design_signatory_groups')->onDelete('cascade');
                $table->foreignId('signatory_id')->constrained('users');
                $table->foreignId('designation_id')->constrained();
                $table->boolean('is_approved')->default(false);
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('activity_design_signatories');
        }
    };
