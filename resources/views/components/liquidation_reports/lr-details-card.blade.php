<div class="rounded-lg bg-green-50 border border-green-200 p-4">
    <div class="flex items-center justify-between mb-3">
        <div>
            <span class="text-xs font-medium text-green-600 uppercase">Payee</span>
            <p class="text-sm font-semibold text-gray-800">{{ $record->disbursement_voucher?->payee ?? 'N/A' }}</p>
        </div>
        <div class="text-right">
            <span class="text-xs font-medium text-green-600 uppercase">Total Amount</span>
            <p class="text-sm font-semibold text-gray-800">₱{{ number_format($record->total_amount ?? 0, 2) }}</p>
        </div>
    </div>
    <div class="border-t border-green-200 pt-3 flex flex-wrap items-center gap-6">
        <div>
            <span class="text-xs font-medium text-green-600 uppercase">Requisitioner</span>
            <p class="text-sm font-semibold text-gray-800">{{ $record->requisitioner?->employee_information?->full_name ?? 'N/A' }}</p>
        </div>
        <div>
            <span class="text-xs font-medium text-green-600 uppercase">Disbursement Voucher</span>
            <p class="text-sm font-semibold text-gray-800">{{ $record->disbursement_voucher?->tracking_number ?? 'N/A' }}</p>
        </div>
        <div>
            <span class="text-xs font-medium text-green-600 uppercase">CA Type</span>
            <p class="text-sm font-semibold text-gray-800">{{ $record->disbursement_voucher?->voucher_subtype?->voucher_type?->name ?? 'N/A' }}</p>
        </div>
        <div>
            <span class="text-xs font-medium text-green-600 uppercase">CA Subtype</span>
            <p class="text-sm font-semibold text-gray-800">{{ $record->disbursement_voucher?->voucher_subtype?->name ?? 'N/A' }}</p>
        </div>
    </div>
    <div class="border-t border-green-200 pt-3 mt-3">
        <span class="text-xs font-medium text-green-600 uppercase">Particulars</span>
        <div class="mt-1 space-y-1">
            @forelse(collect($record->particulars ?? []) as $particular)
                <div class="flex items-start justify-between text-sm">
                    <span class="text-gray-700 whitespace-pre-line">{{ $particular['purpose'] ?? '' }}</span>
                    <span class="font-medium text-gray-800 ml-4 whitespace-nowrap">₱{{ number_format($particular['amount'] ?? 0, 2) }}</span>
                </div>
            @empty
                <p class="text-sm text-gray-400">No particulars</p>
            @endforelse
        </div>
    </div>
</div>
