<?php

    use Carbon\Carbon;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Support\Collection;
    use Illuminate\Support\Facades\DB;

    return new class extends Migration {
        private const FALLBACK_DESCRIPTION = 'Disbursement voucher submitted.';

        public function up(): void
        {
            $steps = $this->steps();

            DB::table('disbursement_vouchers')
                ->leftJoin('activity_logs', function ($join) {
                    $join->on('activity_logs.loggable_id', '=', 'disbursement_vouchers.id')
                        ->where('activity_logs.loggable_type', 'dv');
                })
                ->leftJoin('users', 'users.id', '=', 'disbursement_vouchers.signatory_id')
                ->leftJoin('employee_information', 'employee_information.user_id', '=', 'users.id')
                ->whereNull('activity_logs.id')
                ->select([
                    'disbursement_vouchers.id',
                    'disbursement_vouchers.current_step_id',
                    'disbursement_vouchers.previous_step_id',
                    'disbursement_vouchers.submitted_at',
                    'disbursement_vouchers.created_at',
                    'employee_information.full_name as signatory_name',
                ])
                ->orderBy('disbursement_vouchers.id')
                ->chunkById(500, function ($vouchers) use ($steps) {
                    $logs = $vouchers
                        ->flatMap(fn($voucher) => $this->logsForVoucher($voucher, $steps))
                        ->values()
                        ->all();

                    if (!empty($logs)) {
                        DB::table('activity_logs')->insert($logs);
                    }
                }, 'disbursement_vouchers.id', 'id');
        }

        private function steps(): Collection
        {
            return DB::table('disbursement_voucher_steps')
                ->where('enabled', true)
                ->orderBy('id')
                ->get(['id', 'process', 'recipient', 'sender']);
        }

        private function logsForVoucher(object $voucher, Collection $steps): Collection
        {
            $reachedStepId = max((int) $voucher->current_step_id, (int) $voucher->previous_step_id);
            $baseTimestamp = $this->baseTimestamp($voucher);

            return $steps
                ->filter(fn($step) => (int) $step->id <= $reachedStepId)
                ->values()
                ->map(fn($step, $index) => [
                    'loggable_type' => 'dv',
                    'loggable_id' => $voucher->id,
                    'description' => $this->descriptionForStep($voucher, $step),
                    'remarks' => null,
                    'created_at' => $baseTimestamp->copy()->addSeconds($index),
                    'updated_at' => $baseTimestamp->copy()->addSeconds($index),
                ]);
        }

        private function baseTimestamp(object $voucher): Carbon
        {
            return Carbon::parse($voucher->submitted_at ?: ($voucher->created_at ?: now()));
        }

        private function descriptionForStep(object $voucher, object $step): string
        {
            if ((int) $step->id === 3000) {
                return filled($voucher->signatory_name)
                    ? 'Forwarded to '.$voucher->signatory_name.' by Requisitioner'
                    : self::FALLBACK_DESCRIPTION;
            }

            return collect([$step->process, $step->recipient, $step->sender])
                ->filter(fn($part) => filled($part))
                ->implode(' ');
        }
    };
