<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void
        {
            Schema::table('budget_categories', function (Blueprint $table) {
                $table->tinyInteger('type')->after('name');
            });

            DB::table('budget_categories')
                ->where('name', 'like', 'PS%')
                ->update([
                    'type' => 1,
                ]);
            DB::table('budget_categories')
                ->where('name', 'like', 'MOOE%')
                ->update([
                    'type' => 2,
                ]);
            DB::table('budget_categories')
                ->where('name', 'like', 'Capital Outlay%')
                ->update([
                    'type' => 3,
                ]);

        }
    };
