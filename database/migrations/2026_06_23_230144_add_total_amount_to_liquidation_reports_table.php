<?php

    use App\Models\LiquidationReport;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void
        {
            Schema::table('liquidation_reports', function (Blueprint $table) {
                $table->decimal('total_amount', 18, 2)->after('particulars');
            });
            LiquidationReport::each(function (LiquidationReport $report) {
                $report->update([
                    'total_amount' => collect($report->particulars)->sum(fn($particular) => $particular['amount'])
                ]);
            });
        }
    };
