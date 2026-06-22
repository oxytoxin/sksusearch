<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('voucher_sub_types', function (Blueprint $table) {
            $table->tinyInteger('order_column')->after('name')->default(1);
        });

        // Set initial order based on current id ordering within each type
        $subtypes = DB::table('voucher_sub_types')->orderBy('voucher_type_id')->orderBy('id')->get();
        $grouped = $subtypes->groupBy('voucher_type_id');
        foreach ($grouped as $typeId => $items) {
            foreach ($items->values() as $index => $item) {
                DB::table('voucher_sub_types')->where('id', $item->id)->update(['order_column' => $index + 1]);
            }
        }
    }

    public function down()
    {
        Schema::table('voucher_sub_types', function (Blueprint $table) {
            $table->dropColumn('order_column');
        });
    }
};
