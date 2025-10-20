<div class="p-6 space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between border-b border-primary-200 dark:border-primary-700 pb-2">
        <div class="flex items-center gap-2">
            {{-- Back Button --}}
            <a href="{{ route('requisitioner.disbursement-vouchers.liquidation.status') }}"
                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-sm font-medium
                       text-primary-700 dark:text-primary-400 hover:bg-primary-50 dark:hover:bg-primary-900/30
                       transition-colors">
                <x-heroicon-o-arrow-left class="w-4 h-4" />
                Back
            </a>

            <h4 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                Disbursement Voucher Notices
            </h4>
        </div>

        <span
            class="px-3 py-1 text-sm font-medium rounded-full
            {{ $disbursement_voucher->for_cancellation ? 'bg-red-500/10 text-red-600 dark:text-red-400' : ' bg-primary-700 text-white dark:text-primary-400' }}">
            {{ $disbursement_voucher->for_cancellation ? 'For Cancellation' : 'Active' }}
        </span>
    </div>

    {{-- Voucher Summary --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-4 text-sm">
        <div class="flex flex-col">
            <span class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">Tracking #</span>
            <span
                class="font-medium text-gray-900 dark:text-gray-100">{{ $disbursement_voucher->tracking_number }}</span>
        </div>

        <div class="flex flex-col">
            <span class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">Payee</span>
            <span class="font-medium text-gray-900 dark:text-gray-100">{{ $disbursement_voucher->payee }}</span>
        </div>

        <div class="flex flex-col">
            <span class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">Cheque #</span>
            <span
                class="font-medium text-gray-900 dark:text-gray-100">{{ $disbursement_voucher->cheque_number ?? '—' }}</span>
        </div>

        <div class="flex flex-col">
            <span class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">ORS/BURS</span>
            <span
                class="font-medium text-gray-900 dark:text-gray-100">{{ $disbursement_voucher->ors_burs ?? '—' }}</span>
        </div>

        <div class="flex flex-col">
            <span class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">DV #</span>
            <span
                class="font-medium text-gray-900 dark:text-gray-100">{{ $disbursement_voucher->dv_number ?? '—' }}</span>
        </div>

        <div class="flex flex-col">
            <span class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">Journal Date</span>
            <span class="font-medium text-gray-900 dark:text-gray-100">
                {{ optional($disbursement_voucher->journal_date)->format('M d, Y') ?? '—' }}
            </span>
        </div>

        <div class="flex flex-col">
            <span class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">Documents Verified</span>
            <span class="font-medium text-gray-900 dark:text-gray-100">
                {{ optional($disbursement_voucher->documents_verified_at)->format('M d, Y h:i A') ?? '—' }}
            </span>
        </div>
    </div>

    {{-- Divider --}}
    <div class="border-t border-primary-200 dark:border-primary-700"></div>

    {{-- Notices Table --}}
    <div>
        <h5 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-3">
            Notices / History
        </h5>
        {{ $this->table }}
    </div>

</div>
