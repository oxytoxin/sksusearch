<div class="text-xs text-gray-900 mt-2">
            <table class="w-full">
                <tr>
                    <td class="border border-gray-800 px-2">DV number:</td>
                    <td class="border border-gray-800 px-2">{{$record->dv_number ??''}}</td>
                    <td class="border border-gray-800 px-2">Date Disbursed</td>
                    <td class="border border-gray-800 px-2" style="min-width: 120px;">
    {{ $record?->cheque_number_added_at ? date_format(date_create($record->cheque_number_added_at), 'F d, Y') : '' }}
</td>

                </tr>
                <tr>
                    <td class="border border-gray-800 px-2">Check/ADA </td>
                    <td class="border border-gray-800 px-2">{{$record->cheque_number ??''}} </td>
                    <td class="border border-gray-800 px-2">End of travel/implementation/payroll period:</td>
                    <td class="border border-gray-800 px-2" style="min-width: 120px;">
    {{ $record?->cash_advance_reminder?->voucher_end_date ? date_format(date_create($record->cash_advance_reminder->voucher_end_date), 'F d, Y') : '' }}
</td>
                </tr>
                <tr>
                    <td class="border border-gray-800 px-2">Amount</td>
                    <td class="border border-gray-800 px-2">{{ number_format($record->totalSumDisbursementVoucherParticular() ?? 0, 2) }}</td>
                    <td class="border border-gray-800 px-2">Liquidation deadline:</td>
                    <td class="border border-gray-800 px-2" style="min-width: 120px;">
                        {{ $record?->cash_advance_reminder?->liquidation_period_end_date ? date_format(date_create($record->cash_advance_reminder->liquidation_period_end_date), 'F d, Y') : '' }}
                    </td>
                </tr>
            </table>
        </div>
