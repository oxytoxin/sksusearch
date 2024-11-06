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
        Schema::create('fund_draft_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fund_draft_id')->constrained();
            $table->integer('budget_category_id')->nullable();
            $table->string('budget_category')->nullable();
            $table->integer('particular_id')->nullable();
            $table->text('particular')->nullable();
            $table->string('supply_code')->nullable();
            $table->string('specifications')->nullable();
            $table->string('uacs')->nullable();
            $table->integer('title_group')->nullable();
            $table->integer('account_title_id')->nullable();
            $table->string('account_title')->nullable();
            $table->boolean('ppmp')->nullable();
            $table->string('cost_per_unit')->nullable();
            $table->string('quantity')->nullable();
            $table->integer('total_quantity')->nullable();
            $table->string('uom')->nullable();
            $table->integer('estimated_budget')->nullable();
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
        Schema::dropIfExists('fund_draft_items');
    }
};
