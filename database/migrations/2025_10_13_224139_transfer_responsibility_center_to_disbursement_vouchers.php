<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void
        {
            Schema::table('disbursement_vouchers', function (Blueprint $table) {
                $table->string('responsibility_center')->nullable()->after('fund_cluster_id');
            });
            Schema::table('disbursement_voucher_particulars', function (Blueprint $table) {
                $table->dropColumn('responsibility_center');
            });
        }


    };
