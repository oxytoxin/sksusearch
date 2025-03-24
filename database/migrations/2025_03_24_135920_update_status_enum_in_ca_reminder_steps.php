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
        DB::statement("ALTER TABLE ca_reminder_steps MODIFY COLUMN status ENUM('On-Going', 'Pending', 'Completed', 'Unliquidated') DEFAULT 'On-Going'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ca_reminder_steps', function (Blueprint $table) {
            DB::statement("ALTER TABLE ca_reminder_steps MODIFY COLUMN status ENUM('On-Going', 'Pending', 'Completed') DEFAULT 'On-Going'");
        });
    }
};
