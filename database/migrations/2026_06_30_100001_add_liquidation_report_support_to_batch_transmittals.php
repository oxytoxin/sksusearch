<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tag a batch with the kind of document it carries. Existing batches are DVs.
        Schema::table('batch_transmittals', function (Blueprint $table) {
            $table->string('document_type')->default('disbursement_voucher')->after('serial_number');
        });

        // Allow a batch item to point at a liquidation report instead of a DV.
        Schema::table('batch_transmittal_items', function (Blueprint $table) {
            $table->foreignId('disbursement_voucher_id')->nullable()->change();
            $table->foreignId('liquidation_report_id')->nullable()->after('disbursement_voucher_id')
                ->constrained('liquidation_reports');
        });
    }

    public function down(): void
    {
        Schema::table('batch_transmittal_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('liquidation_report_id');
            $table->foreignId('disbursement_voucher_id')->nullable(false)->change();
        });

        Schema::table('batch_transmittals', function (Blueprint $table) {
            $table->dropColumn('document_type');
        });
    }
};
