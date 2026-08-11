<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bank_account_transactions', function (Blueprint $table) {
            $table->dropColumn('operation');
        });
    }

    public function down(): void
    {
        Schema::table('bank_account_transactions', function (Blueprint $table) {
            $table->tinyInteger('operation')->default(1)->after('amount');
        });
    }
};
