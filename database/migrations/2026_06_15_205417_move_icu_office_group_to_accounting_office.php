<?php

    use App\Models\DisbursementVoucherStep;
    use App\Models\Office;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void
        {
            Office::where('name', 'Internal Control Unit')->update(['office_group_id' => 2]);
            DisbursementVoucherStep::whereIn('id', [19000, 20000])->update(['office_group_id' => 2, 'recipient' => 'Accounting Office (Post-Audit)']);
        }
    };
