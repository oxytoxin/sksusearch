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
        Schema::table('employee_information', function (Blueprint $table) {
            $table->dropForeign('employee_information_role_id_foreign');
            $table->dropColumn('role_id');
        });
        Schema::dropIfExists('roles');
        Schema::dropIfExists('office_user');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offices', function (Blueprint $table) {
            //
        });
    }
};
