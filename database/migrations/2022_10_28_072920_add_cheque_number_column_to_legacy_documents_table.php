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
            $table->text('cheque_number')->after('fund_cluster_id')->nullable();
            $table->integer('cheque_amount')->nullable()->after('fund_cluster_id');
            $table->date('cheque_date')->nullable()->after('fund_cluster_id');
            $table->tinyInteger('cheque_state')->nullable()->after('fund_cluster_id');
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
            $table->dropColumn('cheque_number');
            $table->dropColumn('cheque_amount');
            $table->dropColumn('cheque_date');
            $table->dropColumn('cheque_state');
        });
    }
};
