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
        Schema::table('fuel_requisitions', function (Blueprint $table) {
            $table->foreignId('actual_supplier_id')->nullable()->after('actual_supplier_attendant')->constrained('suppliers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fuel_requisitions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('actual_supplier_id');
        });
    }
};
