<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wfp_requested_supplies', function (Blueprint $table) {
            $table->foreignId('category_item_budget_id')
                ->after('category_item_id')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wfp_requested_supplies', function (Blueprint $table) {
            $table->dropForeign(['category_item_budget_id']);
            $table->dropColumn('category_item_budget_id');
        });
    }
};
