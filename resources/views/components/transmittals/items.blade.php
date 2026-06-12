<div class="space-y-4 text-sm">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <span class="text-xs font-medium text-gray-500 uppercase">Transmittal No.</span>
            <p class="font-semibold text-gray-800">{{ $transmittal->transmittal_number }}</p>
        </div>
        <div>
            <span class="text-xs font-medium text-gray-500 uppercase">Transmitted To</span>
            <p class="font-semibold text-gray-800">{{ $transmittal->recipient }}</p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border border-gray-200">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-3 py-2 border-b">#</th>
                    <th class="px-3 py-2 border-b">Tracking No.</th>
                    <th class="px-3 py-2 border-b">Disbursement Sub Type</th>
                    <th class="px-3 py-2 border-b">Payee</th>
                    <th class="px-3 py-2 border-b text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transmittal->disbursement_vouchers as $i => $dv)
                    <tr class="border-b">
                        <td class="px-3 py-2">{{ $i + 1 }}</td>
                        <td class="px-3 py-2 font-medium">{{ $dv->tracking_number }}</td>
                        <td class="px-3 py-2">{{ $dv->voucher_subtype?->name ?? 'N/A' }}</td>
                        <td class="px-3 py-2">{{ $dv->payee ?? 'N/A' }}</td>
                        <td class="px-3 py-2 text-right">₱{{ number_format($dv->disbursement_voucher_particulars->sum('amount'), 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-3 py-4 text-center text-gray-400">No items.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($transmittal->remarks)
        <div>
            <span class="text-xs font-medium text-gray-500 uppercase">Remarks</span>
            <p class="text-gray-700">{{ $transmittal->remarks }}</p>
        </div>
    @endif
</div>
