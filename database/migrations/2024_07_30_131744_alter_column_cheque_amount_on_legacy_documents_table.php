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
        //change cheque_amount column from integer to decimal on legacy documents table
        Schema::table('legacy_documents', function (Blueprint $table) {
            $table->decimal('cheque_amount', 10, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //change cheque_amount column from decimal to integer on legacy documents table
        Schema::table('legacy_documents', function (Blueprint $table) {
            $table->integer('cheque_amount')->change();
        });
    }
};
