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
        Schema::create('travel_completed_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index();
            $table->foreignId('signatory_id')->index();
            $table->foreignId('travel_order_id')->index();
            $table->foreignId('itinerary_id')->index()->nullable();
            $table->foreignId('liquidation_report_id')->index()->nullable();
            $table->foreignId('disbursement_voucher_id')->index()->nullable();
            $table->smallInteger('condition')->default(0);
            $table->text('explanation')->nullable();
            $table->json('details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('travel_completed_certificates');
    }
};
