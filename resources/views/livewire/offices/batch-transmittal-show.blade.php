<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold text-primary-600">Batch Transmittal No. {{ $batch->serial_number }}</h2>
        <div class="flex gap-2">
            <a href="{{ route('office.batch-transmittal.print', $batch) }}" target="_blank"
               class="inline-flex items-center gap-1 rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18.75 12h.008v.008h-.008V12zm-2.25 0h.008v.008H16.5V12z" />
                </svg>
                Print
            </a>
            <a href="{{ route('office.batch-transmittal.index') }}"
               class="inline-flex items-center gap-1 rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                Back to List
            </a>
        </div>
    </div>

    <div class="rounded-lg bg-white p-5 shadow-sm">
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div><span class="font-semibold text-gray-600">From:</span> {{ $batch->from_office_name }}</div>
            <div><span class="font-semibold text-gray-600">To:</span> {{ $batch->to_office_name }}</div>
            <div><span class="font-semibold text-gray-600">Created by:</span> {{ $batch->created_by_user?->employee_information?->full_name ?? '—' }}</div>
            <div><span class="font-semibold text-gray-600">Created at:</span> {{ $batch->created_at?->format('M d, Y g:i A') }}</div>
            <div><span class="font-semibold text-gray-600">Forwarded by:</span> {{ $batch->forwarded_by_user?->employee_information?->full_name ?? '—' }}</div>
            <div><span class="font-semibold text-gray-600">Forwarded at:</span> {{ $batch->forwarded_at?->format('M d, Y g:i A') ?? '—' }}</div>
            <div><span class="font-semibold text-gray-600">Received by:</span> {{ $batch->received_by_user?->employee_information?->full_name ?? '—' }}</div>
            <div><span class="font-semibold text-gray-600">Received at:</span> {{ $batch->received_at?->format('M d, Y g:i A') ?? '—' }}</div>
        </div>
    </div>

    @if ($batch->document_type === 'liquidation_report')
        <div class="rounded-lg bg-white p-4 shadow-sm">
            <h3 class="mb-3 font-semibold text-gray-700">Liquidation Reports ({{ $batch->items->count() }})</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b bg-gray-50">
                        <tr>
                            <th class="px-3 py-2">#</th>
                            <th class="px-3 py-2">Tracking No.</th>
                            <th class="px-3 py-2">LR No.</th>
                            <th class="px-3 py-2">Requisitioner</th>
                            <th class="px-3 py-2">Disbursement Voucher</th>
                            <th class="px-3 py-2">Amount</th>
                            <th class="px-3 py-2">Current Step</th>
                            <th class="px-3 py-2">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($batch->items as $index => $item)
                            <tr class="border-b">
                                <td class="px-3 py-2">{{ $index + 1 }}</td>
                                <td class="px-3 py-2 font-mono text-xs">{{ $item->liquidation_report?->tracking_number }}</td>
                                <td class="px-3 py-2 font-mono text-xs">{{ $item->liquidation_report?->lr_number ?? '—' }}</td>
                                <td class="px-3 py-2">{{ $item->liquidation_report?->requisitioner?->employee_information?->full_name ?? '—' }}</td>
                                <td class="px-3 py-2 font-mono text-xs">{{ $item->liquidation_report?->disbursement_voucher?->tracking_number ?? '—' }}</td>
                                <td class="px-3 py-2 whitespace-nowrap">₱{{ number_format($item->liquidation_report?->total_amount ?? 0, 2) }}</td>
                                <td class="px-3 py-2 text-xs">{{ $item->liquidation_report?->current_step?->process }} {{ $item->liquidation_report?->current_step?->recipient }}</td>
                                <td class="px-3 py-2">{{ $item->remarks ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="rounded-lg bg-white p-4 shadow-sm">
            <h3 class="mb-3 font-semibold text-gray-700">Vouchers ({{ $batch->items->count() }})</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b bg-gray-50">
                        <tr>
                            <th class="px-3 py-2">#</th>
                            <th class="px-3 py-2">Tracking No.</th>
                            <th class="px-3 py-2">DV No.</th>
                            <th class="px-3 py-2">Payee</th>
                            <th class="px-3 py-2">Particulars</th>
                            <th class="px-3 py-2">Gross Amount</th>
                            <th class="px-3 py-2">Net Amount</th>
                            <th class="px-3 py-2">Current Step</th>
                            <th class="px-3 py-2">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($batch->items as $index => $item)
                            <tr class="border-b">
                                <td class="px-3 py-2">{{ $index + 1 }}</td>
                                <td class="px-3 py-2 font-mono text-xs">{{ $item->disbursement_voucher->tracking_number }}</td>
                                <td class="px-3 py-2 font-mono text-xs">{{ $item->disbursement_voucher->dv_number ?? '—' }}</td>
                                <td class="px-3 py-2">{{ $item->disbursement_voucher->payee }}</td>
                                <td class="px-3 py-2 max-w-xs truncate">{{ $item->disbursement_voucher->disbursement_voucher_particulars->pluck('purpose')->join('; ') }}</td>
                                <td class="px-3 py-2 whitespace-nowrap">₱{{ number_format($item->disbursement_voucher->gross_amount ?? $item->disbursement_voucher->disbursement_voucher_particulars->sum('amount'), 2) }}</td>
                                <td class="px-3 py-2 whitespace-nowrap">₱{{ number_format($item->disbursement_voucher->disbursement_voucher_particulars->sum('amount'), 2) }}</td>
                                <td class="px-3 py-2 text-xs">{{ $item->disbursement_voucher->current_step?->process }} {{ $item->disbursement_voucher->current_step?->recipient }}</td>
                                <td class="px-3 py-2">{{ $item->remarks ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
