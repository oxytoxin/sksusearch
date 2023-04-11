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
        Schema::table('bonds', function (Blueprint $table) {
            $table->string('bond_certificate_number')->after('amount')->nullable();
            $table->date('validity_date_to')->after('validity_date')->nullable();
            $table->renameColumn('validity_date', 'validity_date_from');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->dropColumn('bond_certificate_number');
        $table->dropColumn('validity_date_to');
        $table->renameColumn('validity_date_from', 'validity_date');
    }
};
