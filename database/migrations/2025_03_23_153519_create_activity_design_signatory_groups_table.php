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
            Schema::create('activity_design_signatory_groups', function (Blueprint $table) {
                $table->id();
                $table->integer('order');
                $table->tinyInteger('status')->default(0);
                $table->foreignId('activity_design_id');
                $table->foreign('activity_design_id', 'activity_design_signatory_group_foreign')->references('id')->on('activity_designs')->onDelete('cascade');
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('activity_design_signatory_groups');
        }
    };
