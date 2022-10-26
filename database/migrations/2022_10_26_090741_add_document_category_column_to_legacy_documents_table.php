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
        Schema::table('legacy_documents', function (Blueprint $table) {
            $table->foreignId('document_category')
            ->after('fund_cluster_id')
            ->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('legacy_documents', function (Blueprint $table) {
            //
        });
    }
};
