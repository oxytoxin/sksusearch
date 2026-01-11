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
        Schema::create('fund_allocation_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cost_center_id')->references('id')->on('cost_centers');
            $table->foreignId('wfp_type_id')->nullable()->references('id')->on('wpf_types');
            $table->foreignId('fund_cluster_id')->references('id')->on('fund_clusters');
            $table->foreignId('supplemental_quarter_id')->nullable()->references('id')->on('supplemental_quarters');
            $table->boolean('is_supplemental')->default(false);
            $table->boolean('is_locked')->default(false);
            $table->string('status')->default('draft'); // draft, pending, approved, for_modification
            $table->boolean('acknowledged')->default(true);
            $table->boolean('is_forwared')->default(false);
            $table->unsignedBigInteger('forwarded_to_supplemental_quarter')->nullable();
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
        Schema::dropIfExists('fund_allocation_batches');
    }
};
