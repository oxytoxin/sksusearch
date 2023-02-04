<?php

namespace App\Console\Commands;

use DB;
use Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\VoucherSubType;
use Illuminate\Console\Command;
use App\Models\DisbursementVoucher;
use App\Models\EmployeeInformation;
use Spatie\SimpleExcel\SimpleExcelReader;

class ProcessLegacyCashAdvances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:legacy-ca';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seeds legacy cash advances';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        DB::beginTransaction();
        $user = User::create([
            'id' => 450,
            'email' => 'legacy-accounts@sksu.edu.ph',
            'password' => Hash::make('legacy-accounts'),
        ]);
        $employee = EmployeeInformation::create([
            'first_name' => 'LEGACY',
            'last_name' => 'ACCOUNTS ADMINISTRATOR',
            'full_name' => 'LEGACY ACCOUNTS ADMINISTRATOR',
            'user_id' => $user->id,
            'role_id' => 2,
            'position_id' => 9,
            'office_id' => 50,
        ]);
        $voucher_subtype = VoucherSubType::create([
            'voucher_type_id' => 1,
            'name' => 'Legacy Cash Advances',
        ]);

        $rows = SimpleExcelReader::create(storage_path('csv/legacy-ca.csv'))->getRows();
        $rows->each(function ($data) use ($voucher_subtype) {
            $dv = DisbursementVoucher::create([
                'voucher_subtype_id' => $voucher_subtype->id,
                'user_id' => trim($data['user_id']),
                'tracking_number' => DisbursementVoucher::generateTrackingNumber(),
                'dv_number' => trim($data['Reference']),
                'certified_by_accountant' => true,
                'signatory_id' => 450,
                'fund_cluster_id' => $data['fund_cluster_id'],
                'payee' => $data['user_id'] == 450 ? strtoupper(trim($data['Name'])) : EmployeeInformation::firstWhere('user_id', $data['user_id'])->full_name,
                'other_details' => [
                    'account' => trim($data['Account']),
                    'ref' => trim($data['Ref.']),
                ],
                'cheque_number' => 'LEGACY-' . $data['Ref.'],
                'submitted_at' => date_create(trim($data['Date'])),
                'current_step_id' => 23000,
            ]);

            $dv->disbursement_voucher_particulars()->create([
                'purpose' => trim($data['Particulars']),
                'amount' => trim($data['Amount'])
            ]);
        });

        DB::commit();

        return Command::SUCCESS;
    }
}
