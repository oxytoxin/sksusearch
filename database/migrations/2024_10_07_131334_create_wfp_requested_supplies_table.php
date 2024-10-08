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
        Schema::create('wfp_requested_supplies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index();
            $table->foreignId('category_item_id')->nullable();
            $table->foreignId('category_group_id')->nullable();
            $table->string('supply_code')->nullable();
            $table->string('particulars')->nullable();
            $table->decimal('unit_cost', 10, 2)->nullable();
            $table->boolean('is_ppmp')->default(false);
            $table->boolean('is_approved_finance')->default(false);
            $table->boolean('is_approved_supply')->default(false);
            $table->string('status')->default('Pending');
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('wfp_requested_supplies');
    }
};
