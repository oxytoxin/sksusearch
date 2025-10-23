<?php

    use App\Models\ActivityDesignSignatory;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        /**
         * Run the migrations.
         */
        public function up(): void
        {
            Schema::table('activity_design_signatories', function (Blueprint $table) {
                $table->tinyInteger('status')->default(0)->after('is_approved');
            });
            Schema::table('activity_design_signatories', function (Blueprint $table) {
                $table->dropColumn('is_approved');
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::table('activity_design_signatories', function (Blueprint $table) {
                //
            });
        }
    };
