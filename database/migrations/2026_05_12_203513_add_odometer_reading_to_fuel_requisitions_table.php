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
            $table->unsignedInteger('odometer_reading')->nullable()->after('actual_supplier_attendant');
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
            $table->dropColumn('odometer_reading');
        });
    }
};
