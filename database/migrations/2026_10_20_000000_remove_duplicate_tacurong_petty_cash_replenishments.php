<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function (): void {
            $fundId = 7;
            $dvId = 347;
            $originalId = 890;
            $duplicateIds = [896, 917, 919];
            $purpose = 'Establishment of Petty Cash Fund for Tacurong Campus';

            $fund = DB::table('petty_cash_funds')->where('id', $fundId)->lockForUpdate()->first();
            $dv = DB::table('disbursement_vouchers')->where('id', $dvId)->first();
            $particulars = DB::table('disbursement_voucher_particulars')
                ->where('disbursement_voucher_id', $dvId)
                ->get();
            $previous = DB::table('petty_cash_fund_records')
                ->where('petty_cash_fund_id', $fundId)
                ->where('id', '<', $originalId)
                ->orderByDesc('id')
                ->lockForUpdate()
                ->first();
            $records = DB::table('petty_cash_fund_records')
                ->where('petty_cash_fund_id', $fundId)
                ->where('id', '>=', $originalId)
                ->orderBy('id')
                ->lockForUpdate()
                ->get();
            $replenishments = $records->filter(fn ($record) => $record->type === 1
                && $record->recordable_type === 'dv' && $record->recordable_id === $dvId);

            if (!$fund || $fund->campus_id !== 2
                || !$dv || $dv->tracking_number !== 'dv-26-3ouvtZSV'
                || $dv->voucher_subtype_id !== 69 || $dv->user_id !== 332
                || $particulars->count() !== 1
                || $particulars->first()->purpose !== $purpose
                || bccomp($particulars->first()->amount, '10000.00', 2) !== 0
                || !$previous || $previous->id !== 776
                || bccomp($previous->running_balance, '0.00', 2) !== 0
                || $replenishments->pluck('id')->all() !== [$originalId, ...$duplicateIds]
                || DB::table('petty_cash_fund_records')
                    ->where('type', 1)
                    ->whereIn('recordable_type', ['dv', 'App\\Models\\DisbursementVoucher'])
                    ->where('recordable_id', $dvId)
                    ->count() !== 4) {
                throw new RuntimeException('Unexpected Tacurong petty cash data; no correction was applied.');
            }

            $balance = $previous->running_balance;
            foreach ($records as $record) {
                if (in_array($record->type, [1, 3], true)) {
                    $balance = bcadd($balance, $record->amount, 2);
                } elseif (in_array($record->type, [2, 4], true)) {
                    $balance = bcsub($balance, $record->amount, 2);
                } else {
                    throw new RuntimeException('Unexpected Tacurong petty cash record type; no correction was applied.');
                }

                if (bccomp($balance, $record->running_balance, 2) !== 0
                    || ($record->id === $originalId || in_array($record->id, $duplicateIds, true))
                    && ($record->type !== 1 || $record->recordable_type !== 'dv'
                        || $record->recordable_id !== $dvId || $record->nature_of_payment !== $purpose
                        || bccomp($record->amount, '10000.00', 2) !== 0)) {
                    throw new RuntimeException('Unexpected Tacurong petty cash ledger; no correction was applied.');
                }
            }

            DB::table('petty_cash_fund_records')->whereIn('id', $duplicateIds)->delete();

            $balance = $previous->running_balance;
            foreach ($records as $record) {
                if (in_array($record->id, $duplicateIds, true)) {
                    continue;
                }

                $balance = in_array($record->type, [1, 3], true)
                    ? bcadd($balance, $record->amount, 2)
                    : bcsub($balance, $record->amount, 2);

                if (bccomp($balance, $record->running_balance, 2) !== 0) {
                    DB::table('petty_cash_fund_records')
                        ->where('id', $record->id)
                        ->update(['running_balance' => $balance]);
                }
            }
        });
    }
};
