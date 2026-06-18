<?php

    use App\Models\TravelOrder;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Support\Facades\DB;

    return new class extends Migration {
        public function up(): void
        {
            $this->backfillDisbursementVoucherSignatoryApprovals();
            $this->backfillDisbursementVoucherPresidentApprovals();
            $this->backfillDisbursementVoucherAccountantApprovals();
            $this->backfillLiquidationReportSignatoryApprovals();
            $this->backfillLiquidationReportAccountantApprovals();
            $this->backfillTravelOrderApprovals();
        }

        private function backfillDisbursementVoucherSignatoryApprovals(): void
        {
            DB::table('disbursement_vouchers')
                ->join('users', 'users.id', '=', 'disbursement_vouchers.signatory_id')
                ->where(function ($query) {
                    $query
                        ->where('disbursement_vouchers.current_step_id', '>', 4000)
                        ->orWhere('disbursement_vouchers.previous_step_id', '>', 4000);
                })
                ->select([
                    'disbursement_vouchers.id',
                    'disbursement_vouchers.signatory_id as user_id',
                    'disbursement_vouchers.updated_at',
                    'disbursement_vouchers.created_at',
                ])
                ->chunkById(1000, function ($vouchers) {
                    $this->insertApprovals($vouchers->map(fn($voucher) => [
                        'approvable_type' => 'dv',
                        'approvable_id' => $voucher->id,
                        'role' => 'signatory',
                        'user_id' => $voucher->user_id,
                        'approved_at' => $this->timestampFrom($voucher),
                        'approved_by_oic_id' => null,
                    ]));
                }, 'disbursement_vouchers.id', 'id');
        }

        private function backfillDisbursementVoucherPresidentApprovals(): void
        {
            $presidentUserId = $this->presidentUserId();

            if (!$presidentUserId) {
                return;
            }

            DB::table('disbursement_vouchers')
                ->where(function ($query) {
                    $query
                        ->where('current_step_id', '>', 15000)
                        ->orWhere('previous_step_id', '>', 15000);
                })
                ->select(['id', 'updated_at', 'created_at'])
                ->chunkById(1000, function ($vouchers) use ($presidentUserId) {
                    $this->insertApprovals($vouchers->map(fn($voucher) => [
                        'approvable_type' => 'dv',
                        'approvable_id' => $voucher->id,
                        'role' => 'president',
                        'user_id' => $presidentUserId,
                        'approved_at' => $this->timestampFrom($voucher),
                        'approved_by_oic_id' => null,
                    ]));
                });
        }

        private function backfillDisbursementVoucherAccountantApprovals(): void
        {
            $accountantUserId = $this->accountantUserId();

            if (!$accountantUserId) {
                return;
            }

            DB::table('disbursement_vouchers')
                ->where('certified_by_accountant', true)
                ->select(['id', 'updated_at', 'created_at'])
                ->chunkById(1000, function ($vouchers) use ($accountantUserId) {
                    $this->insertApprovals($vouchers->map(fn($voucher) => [
                        'approvable_type' => 'dv',
                        'approvable_id' => $voucher->id,
                        'role' => 'accountant',
                        'user_id' => $accountantUserId,
                        'approved_at' => $this->timestampFrom($voucher),
                        'approved_by_oic_id' => null,
                    ]));
                });
        }

        private function backfillLiquidationReportSignatoryApprovals(): void
        {
            DB::table('liquidation_reports')
                ->join('users', 'users.id', '=', 'liquidation_reports.signatory_id')
                ->where(function ($query) {
                    $query
                        ->whereNotNull('liquidation_reports.signatory_date')
                        ->orWhere('liquidation_reports.current_step_id', '>', 4000)
                        ->orWhere('liquidation_reports.previous_step_id', '>', 4000);
                })
                ->select([
                    'liquidation_reports.id',
                    'liquidation_reports.signatory_id as user_id',
                    'liquidation_reports.signatory_date',
                    'liquidation_reports.updated_at',
                    'liquidation_reports.created_at',
                ])
                ->chunkById(1000, function ($reports) {
                    $this->insertApprovals($reports->map(fn($report) => [
                        'approvable_type' => 'lr',
                        'approvable_id' => $report->id,
                        'role' => 'signatory',
                        'user_id' => $report->user_id,
                        'approved_at' => $this->timestampFrom($report, 'signatory_date'),
                        'approved_by_oic_id' => null,
                    ]));
                }, 'liquidation_reports.id', 'id');
        }

        private function backfillLiquidationReportAccountantApprovals(): void
        {
            $accountantUserId = $this->accountantUserId();

            if (!$accountantUserId) {
                return;
            }

            DB::table('liquidation_reports')
                ->where('certified_by_accountant', true)
                ->select(['id', 'updated_at', 'created_at'])
                ->chunkById(1000, function ($reports) use ($accountantUserId) {
                    $this->insertApprovals($reports->map(fn($report) => [
                        'approvable_type' => 'lr',
                        'approvable_id' => $report->id,
                        'role' => 'accountant',
                        'user_id' => $accountantUserId,
                        'approved_at' => $this->timestampFrom($report),
                        'approved_by_oic_id' => null,
                    ]));
                });
        }

        private function backfillTravelOrderApprovals(): void
        {
            DB::table('travel_order_signatories')
                ->join('users as slot_owners', 'slot_owners.id', '=', 'travel_order_signatories.user_id')
                ->leftJoin('users as oic_users', 'oic_users.id', '=', 'travel_order_signatories.approved_by_oic_id')
                ->where('travel_order_signatories.is_approved', true)
                ->whereNotNull('travel_order_signatories.role')
                ->select([
                    'travel_order_signatories.id as signatory_row_id',
                    'travel_order_signatories.travel_order_id',
                    'travel_order_signatories.user_id',
                    'travel_order_signatories.role',
                    'travel_order_signatories.approved_at',
                    'oic_users.id as approved_by_oic_id',
                    'travel_order_signatories.updated_at',
                    'travel_order_signatories.created_at',
                ])
                ->chunkById(1000, function ($signatories) {
                    $this->insertApprovals($signatories->map(fn($signatory) => [
                        'approvable_type' => 'to',
                        'approvable_id' => $signatory->travel_order_id,
                        'role' => $signatory->role,
                        'user_id' => $signatory->user_id,
                        'approved_at' => $this->timestampFrom($signatory, 'approved_at'),
                        'approved_by_oic_id' => $signatory->approved_by_oic_id,
                    ]));
                }, 'travel_order_signatories.id', 'signatory_row_id');
        }

        private function insertApprovals($approvals): void
        {
            $now = now();

            $rows = $approvals
                ->filter(fn($approval) => filled($approval['approved_at']))
                ->map(fn($approval) => $approval + [
                        'created_at' => $now,
                        'updated_at' => $now,
                    ])
                ->values()
                ->all();

            if ($rows) {
                DB::table('approvals')->insertOrIgnore($rows);
            }
        }

        private function timestampFrom(object $record, ?string $preferredColumn = null)
        {
            if ($preferredColumn && filled($record->{$preferredColumn})) {
                return $record->{$preferredColumn};
            }

            return $record->updated_at ?? $record->created_at ?? now();
        }

        private function accountantUserId(): ?int
        {
            return DB::table('employee_information')
                ->join('users', 'users.id', '=', 'employee_information.user_id')
                ->where('employee_information.position_id', 15)
                ->where('employee_information.office_id', 3)
                ->value('employee_information.user_id');
        }

        private function presidentUserId(): ?int
        {
            return DB::table('employee_information')
                ->join('users', 'users.id', '=', 'employee_information.user_id')
                ->where('employee_information.position_id', 34)
                ->where('employee_information.office_id', 51)
                ->value('employee_information.user_id');
        }
    };
