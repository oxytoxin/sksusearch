<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
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
        Schema::table('related_documents_lists', function (Blueprint $table) {
            $table->json('liquidation_report_documents')->default(new Expression('(JSON_ARRAY())'))->after('documents');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('related_documents_lists', function (Blueprint $table) {
            //
        });
    }
};
