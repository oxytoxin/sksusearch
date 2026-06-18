<?php

    use App\Models\DisbursementVoucher;
    use App\Models\EmployeeInformation;
    use App\Models\User;
    use Carbon\Carbon;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Hash;
    use Spatie\SimpleExcel\SimpleExcelReader;

    return new class extends Migration {
        private const IMPORT_KEY = 'legacy_ca_2026';
        private const VOUCHER_SUBTYPE_ID = 77;
        private const LEGACY_ADMIN_USER_ID = 450;
        private const ARCHIVED_STEP_ID = 23000;

        public function up(): void
        {
            DB::transaction(function () {
                $this->ensureLegacyAccountsAdmin();

                $rows = SimpleExcelReader::create($this->csvPath())->getRows();

                $rowNumber = 1;

                $rows->each(function (array $data) use (&$rowNumber) {
                    $rowNumber++;
                    $row = $this->normalizeRow($data);

                    if (blank($row['user_id']) || blank($row['ref'])) {
                        return;
                    }

                    if ($this->alreadyImported($rowNumber)) {
                        return;
                    }

                    $submittedAt = $this->parseDate($row['date']);

                    $dv = DisbursementVoucher::create([
                        'voucher_subtype_id' => self::VOUCHER_SUBTYPE_ID,
                        'user_id' => trim($row['user_id']),
                        'tracking_number' => DisbursementVoucher::generateTrackingNumber(),
                        'dv_number' => trim($row['ref']),
                        'certified_by_accountant' => true,
                        'signatory_id' => self::LEGACY_ADMIN_USER_ID,
                        'fund_cluster_id' => trim($row['fund_cluster']),
                        'payee' => $this->payeeFor($row),
                        'other_details' => [
                            'legacy_import' => self::IMPORT_KEY,
                            'legacy_row_number' => $rowNumber,
                            'account' => trim($row['fund_id']),
                            'ref' => trim($row['ref']),
                            'date_liquidated' => filled($row['date_liquidated']) ? trim($row['date_liquidated']) : null,
                        ],
                        'cheque_number' => 'LEGACY-'.trim($row['ref']),
                        'cheque_number_added_at' => $submittedAt,
                        'submitted_at' => $submittedAt,
                        'documents_verified_at' => $submittedAt,
                        'journal_date' => $submittedAt?->toDateString(),
                        'current_step_id' => self::ARCHIVED_STEP_ID,
                    ]);

                    $dv->disbursement_voucher_particulars()->create([
                        'purpose' => trim($row['particulars']),
                        'amount' => $this->parseAmount($row['amount']),
                    ]);

                    $this->createApprovals($dv, $submittedAt);
//                    $this->createCashAdvanceReminder($dv, $submittedAt);
                });
            });
        }

        public function down(): void
        {
            DB::transaction(function () {
                DisbursementVoucher::query()
                    ->where('voucher_subtype_id', self::VOUCHER_SUBTYPE_ID)
                    ->where('other_details->legacy_import', self::IMPORT_KEY)
                    ->each(function (DisbursementVoucher $voucher) {
                        $voucher->approvals()->delete();
                        $voucher->cash_advance_reminder()->delete();
                        $voucher->disbursement_voucher_particulars()->delete();
                        $voucher->delete();
                    });
            });
        }

        private function alreadyImported(int $rowNumber): bool
        {
            return DisbursementVoucher::query()
                ->where('voucher_subtype_id', self::VOUCHER_SUBTYPE_ID)
                ->where('other_details->legacy_import', self::IMPORT_KEY)
                ->where('other_details->legacy_row_number', $rowNumber)
                ->exists();
        }

        private function ensureLegacyAccountsAdmin(): void
        {
            User::query()->firstOrCreate(
                ['id' => self::LEGACY_ADMIN_USER_ID],
                [
                    'email' => 'searchlegacyaccountsadmin@sksu.edu.ph',
                    'password' => Hash::make('sksu@12345'),
                ],
            );

            EmployeeInformation::query()->firstOrCreate(
                ['user_id' => self::LEGACY_ADMIN_USER_ID],
                [
                    'first_name' => 'LEGACY',
                    'last_name' => 'ACCOUNTS ADMINISTRATOR',
                    'full_name' => 'LEGACY ACCOUNTS ADMINISTRATOR',
                    'role_id' => 2,
                    'position_id' => 9,
                    'office_id' => 50,
                ],
            );
        }

        private function csvPath(): string
        {
            return storage_path('csv/legacy_ca_2026.csv');
        }

        private function normalizeRow(array $data): array
        {
            $normalized = [];

            foreach ($data as $key => $value) {
                $normalized[trim(str_replace("\u{FEFF}", '', $key))] = is_string($value) ? trim($value) : $value;
            }

            return [
                'name' => $normalized['name'] ?? '',
                'user_id' => $normalized['user_id'] ?? '',
                'date' => $normalized['date'] ?? '',
                'ref' => $normalized['ref'] ?? '',
                'particulars' => $normalized['particulars'] ?? '',
                'amount' => $normalized['amount'] ?? '',
                'fund_cluster' => $normalized['fund_cluster'] ?? '',
                'fund_id' => $normalized['fund_id'] ?? '',
                'date_liquidated' => $normalized['date_liquidated'] ?? '',
            ];
        }

        private function payeeFor(array $row): string
        {
            if ((int) $row['user_id'] === self::LEGACY_ADMIN_USER_ID) {
                return strtoupper(trim($row['name']));
            }

            return EmployeeInformation::query()->firstWhere('user_id', $row['user_id'])?->full_name
                ?? strtoupper(trim($row['name']));
        }

        private function parseDate(?string $value): ?Carbon
        {
            if (blank($value)) {
                return null;
            }

            foreach (['n/j/y', 'm/d/y', 'n/j/Y', 'm/d/Y'] as $format) {
                try {
                    return Carbon::createFromFormat($format, trim($value))->startOfDay();
                } catch (Throwable) {
                    continue;
                }
            }

            return Carbon::parse(trim($value))->startOfDay();
        }

        private function parseAmount(?string $value): float
        {
            return (float) str_replace(',', '', trim((string) $value));
        }

        private function createApprovals(DisbursementVoucher $voucher, ?Carbon $approvedAt): void
        {
            $approvedAt ??= now();

            $approvals = collect([
                [
                    'role' => 'signatory',
                    'user_id' => self::LEGACY_ADMIN_USER_ID,
                ],
                [
                    'role' => 'accountant',
                    'user_id' => $this->accountantUserId(),
                ],
                [
                    'role' => 'president',
                    'user_id' => $this->presidentUserId(),
                ],
            ])
                ->filter(fn(array $approval) => filled($approval['user_id']))
                ->map(fn(array $approval) => [
                    'approvable_type' => 'dv',
                    'approvable_id' => $voucher->id,
                    'role' => $approval['role'],
                    'user_id' => $approval['user_id'],
                    'approved_at' => $approvedAt,
                    'approved_by_oic_id' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
                ->all();

            if ($approvals) {
                DB::table('approvals')->insertOrIgnore($approvals);
            }
        }

//        private function createCashAdvanceReminder(DisbursementVoucher $voucher, ?Carbon $submittedAt): void
//        {
//            $voucherEndDate = $submittedAt?->toDateString();
//
//            $voucher->cash_advance_reminder()->create([
//                'status' => 'On-Going',
//                'voucher_end_date' => $voucherEndDate,
//                'liquidation_period_end_date' => $submittedAt?->copy()->addDays(20)->toDateString(),
//                'step' => 1,
//                'is_sent' => false,
//                'title' => 'Send FMR',
//                'message' => 'Ongoing liquidation of cash advance.',
//                'user_id' => $voucher->user_id,
//            ]);
//        }

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
