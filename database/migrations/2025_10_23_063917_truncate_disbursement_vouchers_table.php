<?php

    use App\Models\DisbursementVoucher;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void
        {
            $dvs = DisbursementVoucher::whereNot('voucher_subtype_id', 69)->get();
            foreach ($dvs as $dv) {
                $dv->activity_logs()->delete();
                $dv->disbursement_voucher_particulars()->delete();
                $dv->liquidation_report()->delete();
                $dv->delete();
            }
        }
    };
