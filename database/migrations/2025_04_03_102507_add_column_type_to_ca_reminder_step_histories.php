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
        Schema::table('ca_reminder_step_histories', function (Blueprint $table) {
            $table->string('sender_name')->nullable()->after('step_data');
            $table->string('receiver_name')->nullable()->after('sender_name');
            $table->timestamp('sent_at')->nullable()->after('receiver_name');
            $table->enum('type', ['FMR', 'FMD', 'SCO', 'FD',])->nullable()->after('receiver_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ca_reminder_step_histories', function (Blueprint $table) {
            $table->dropColumn('sender_name');
            $table->dropColumn('receiver_name');
            $table->dropColumn('sent_at');
            $table->dropColumn('type');
        });
    }
};
