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
        Schema::table('fuel_requisitions', function (Blueprint $table) {
          $table->decimal('requested_unit_price', 10, 2)->nullable()->after('unit');
            $table->decimal('requested_total_amount', 12, 2)->nullable()->after('requested_unit_price');

            // ACTUAL fields
            $table->decimal('actual_quantity', 8, 2)->nullable();
            $table->decimal('actual_unit_price', 10, 2)->nullable();
            $table->decimal('actual_total_amount', 12, 2)->nullable();
            $table->string('actual_or_number')->nullable();
            $table->date('actual_date')->nullable();
            $table->time('actual_time')->nullable();
            $table->string('actual_supplier_attendant')->nullable();
            $table->boolean('is_liquidated')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fuel_requisitions', function (Blueprint $table) {
             // Drop REQUEST fields
            $table->dropColumn([
                'requested_unit_price',
                'requested_total_amount',
            ]);

            // Drop ACTUAL fields
            $table->dropColumn([
                'actual_quantity',
                'actual_unit_price',
                'actual_total_amount',
                'actual_or_number',
                'actual_date',
                'actual_time',
                'actual_supplier_attendant',
                'is_liquidated',
            ]);
        });
    }
};
