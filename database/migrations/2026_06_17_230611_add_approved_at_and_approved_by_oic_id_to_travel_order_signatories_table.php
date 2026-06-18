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
        Schema::table('travel_order_signatories', function (Blueprint $table) {
            // When this signatory approved the travel order (null until approved).
            $table->timestamp('approved_at')->nullable()->after('is_approved');
            // The OIC who signed on this slot owner's behalf; null = direct sign-off.
            $table->foreignId('approved_by_oic_id')->nullable()->after('approved_at')->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('travel_order_signatories', function (Blueprint $table) {
            $table->dropConstrainedForeignId('approved_by_oic_id');
            $table->dropColumn('approved_at');
        });
    }
};
