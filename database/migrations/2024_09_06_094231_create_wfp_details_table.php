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
        Schema::create('wfp_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wfp_id')->constrained();
            $table->foreignId('budget_category_id')->constrained();
            $table->foreignId('supply_id')->constrained();
            $table->foreignId('category_group_id')->constrained();
            $table->foreignId('category_item_id')->constrained();
            $table->string('uacs_code')->nullable();
            $table->boolean('is_ppmp')->default(false);
            $table->json('quantity_year')->nullable();
            $table->string('cost_per_unit')->nullable();
            $table->string('total_quantity')->nullable();
            $table->string('uom')->nullable();
            $table->string('estimated_budget')->nullable();
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
        Schema::dropIfExists('wfp_details');
    }
};
