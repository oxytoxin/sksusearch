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
        DB::statement("ALTER TABLE ca_reminder_step_histories
        MODIFY COLUMN type ENUM('FMR', 'FMD', 'SCO', 'FD', 'ENDORSEMENT')
        NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         DB::statement("ALTER TABLE ca_reminder_step_histories
        MODIFY COLUMN type ENUM('FMR', 'FMD', 'SCO', 'FD')
        NULL");
    }
};
