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
            $table->text('particulars')->change();
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
            $table->string('particulars')->change();
        });
    }
};
