<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void
        {
            Schema::table('disbursement_vouchers', function (Blueprint $table) {
                $table->string('cancellation_remarks')->nullable()->after('cancelled_at');
            });
        }
    };
