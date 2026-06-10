<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('disbursement_vouchers', function (Blueprint $table) {
            $table->unsignedBigInteger('pending_return_step_id')->nullable()->after('previous_step_id');
        });
    }

    public function down()
    {
        Schema::table('disbursement_vouchers', function (Blueprint $table) {
            $table->dropColumn('pending_return_step_id');
        });
    }
};
