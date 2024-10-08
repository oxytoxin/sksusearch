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
        // Add column uom on requested_supplies table
        Schema::table('wfp_requested_supplies', function (Blueprint $table) {
            $table->text('specification')->nullable()->after('particulars');
            $table->string('uom')->nullable()->after('specification');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop column uom on requested_supplies table
        Schema::table('wfp_requested_supplies', function (Blueprint $table) {
            $table->dropColumn('uom');
            $table->dropColumn('specification');
        });
    }
};
