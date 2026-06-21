<?php

    use App\Models\ActivityLog;
    use App\Models\DisbursementVoucher;
    use App\Models\LiquidationReport;
    use App\Models\TravelOrder;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void
        {
            ActivityLog::where('loggable_type', TravelOrder::class)->update(['loggable_type' => 'to']);
            ActivityLog::where('loggable_type', DisbursementVoucher::class)->update(['loggable_type' => 'dv']);
            ActivityLog::where('loggable_type', LiquidationReport::class)->update(['loggable_type' => 'lr']);
        }
    };
