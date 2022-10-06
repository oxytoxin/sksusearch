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
        Schema::create('legacy_documents', function (Blueprint $table) {
            $table->id();
            $table->string('dv_number')->unique();
            $table->string('document_code');
            $table->string('payee_name');
            $table->json('particulars')->nullable();
            $table->json('other_details');
            $table->date('journal_date');
            $table->date('upload_date');
            $table->foreignId('building_id')->nullable()->index();
            $table->foreignId('shelf_id')->nullable()->index();
            $table->foreignId('drawer_id')->nullable()->index();
            $table->foreignId('folder_id')->nullable()->index();
            $table->foreignId('fund_cluster_id')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('legacy_documents');
    }
};
