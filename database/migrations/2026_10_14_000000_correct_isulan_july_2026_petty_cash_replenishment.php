<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function (): void {
            $fundId = 4;
            $recordId = 974;
            $mayDvId = 435;
            $julyDvId = 1195;
            $difference = '964.00';

            $records = DB::table('petty_cash_fund_records')
                ->where('petty_cash_fund_id', $fundId)
                ->where('id', '>=', $recordId)
                ->orderBy('id')
                ->lockForUpdate()
                ->get();
            $record = $records->first();
            $previous = DB::table('petty_cash_fund_records')
                ->where('petty_cash_fund_id', $fundId)
                ->where('id', '<', $recordId)
                ->orderByDesc('id')
                ->lockForUpdate()
                ->first();
            $mayDv = DB::table('disbursement_vouchers')->where('id', $mayDvId)->first();
            $julyDv = DB::table('disbursement_vouchers')->where('id', $julyDvId)->first();
            $originalMayRecord = DB::table('petty_cash_fund_records')->where('id', 884)->first();
            $julyParticulars = DB::table('disbursement_voucher_particulars')
                ->where('disbursement_voucher_id', $julyDvId)
                ->get();

            if (!$previous || $previous->id !== 946 || bccomp($previous->running_balance, '1893.00', 2) !== 0
                || !$record || $record->id !== $recordId || $record->type !== 1
                || $record->recordable_type !== 'dv' || $record->recordable_id !== $mayDvId
                || $record->nature_of_payment !== 'Petty cash replenishment for May 2026.'
                || bccomp($record->amount, '9071.00', 2) !== 0
                || bccomp($record->running_balance, '10964.00', 2) !== 0
                || !$mayDv || $mayDv->tracking_number !== 'dv-26-fvkgRFYJ'
                || !$originalMayRecord || $originalMayRecord->petty_cash_fund_id !== $fundId
                || $originalMayRecord->type !== 1 || $originalMayRecord->recordable_type !== 'dv'
                || $originalMayRecord->recordable_id !== $mayDvId
                || bccomp($originalMayRecord->amount, '9071.00', 2) !== 0
                || !$julyDv || $julyDv->tracking_number !== 'dv-26-R1ZUUZLV'
                || $julyDv->voucher_subtype_id !== 69 || $julyDv->user_id !== $mayDv->user_id
                || $julyDv->cheque_number !== '101-08-344-2026'
                || $julyParticulars->count() !== 1
                || $julyParticulars->first()->purpose !== 'Petty cash replenishment for July 2026.'
                || bccomp($julyParticulars->first()->amount, '8107.00', 2) !== 0
                || DB::table('petty_cash_fund_records')
                    ->where('type', 1)
                    ->whereIn('recordable_type', ['dv', 'App\\Models\\DisbursementVoucher'])
                    ->where('recordable_id', $julyDvId)
                    ->exists()
                || DB::table('petty_cash_fund_records')
                    ->where('type', 1)
                    ->where('petty_cash_fund_id', $fundId)
                    ->whereIn('recordable_type', ['dv', 'App\\Models\\DisbursementVoucher'])
                    ->where('recordable_id', $mayDvId)
                    ->count() !== 2) {
                throw new RuntimeException('Unexpected Isulan petty cash replenishment data; no correction was applied.');
            }

            $balance = $previous->running_balance;
            foreach ($records as $entry) {
                if ($entry->type === 1 || $entry->type === 3) {
                    $balance = bcadd($balance, $entry->amount, 2);
                } elseif ($entry->type === 2 || $entry->type === 4) {
                    $balance = bcsub($balance, $entry->amount, 2);
                } else {
                    throw new RuntimeException('Unexpected petty cash record type; no correction was applied.');
                }

                if (bccomp($balance, $entry->running_balance, 2) !== 0) {
                    throw new RuntimeException('Petty cash running balances differ from the expected ledger; no correction was applied.');
                }
            }

            DB::table('petty_cash_fund_records')->where('id', $recordId)->update([
                'recordable_id' => $julyDvId,
                'nature_of_payment' => $julyParticulars->first()->purpose,
                'amount' => $julyParticulars->first()->amount,
            ]);

            DB::table('petty_cash_fund_records')
                ->where('petty_cash_fund_id', $fundId)
                ->where('id', '>=', $recordId)
                ->update(['running_balance' => DB::raw('running_balance - ' . $difference)]);
        });
    }
};
