<div class="space-y-6">
    <h2 class="text-lg font-medium text-primary-900">SMS Account Details</h2>

    {{-- Account Details Card --}}
    <div class="rounded-lg bg-white shadow p-6">
        @if ($accountError)
            <div class="rounded-md bg-red-50 border border-red-200 p-4">
                <p class="text-sm text-red-700">{{ $accountError }}</p>
            </div>
        @elseif ($account)
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <p class="text-sm font-medium text-gray-500">Account ID</p>
                    <p class="mt-1 text-lg font-semibold text-gray-900">{{ $account['account_id'] ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Account Name</p>
                    <p class="mt-1 text-lg font-semibold text-gray-900">{{ $account['account_name'] ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Status</p>
                    <span class="mt-1 inline-flex items-center rounded-full px-3 py-1 text-sm font-medium
                        {{ ($account['status'] ?? '') === 'Active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $account['status'] ?? 'N/A' }}
                    </span>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Credit Balance</p>
                    <p class="mt-1 text-lg font-semibold {{ ($account['credit_balance'] ?? 0) <= 500 ? 'text-red-600' : 'text-gray-900' }}">
                        {{ number_format($account['credit_balance'] ?? 0) }}
                    </p>
                    @if (($account['credit_balance'] ?? 0) <= 500)
                        <p class="text-xs text-red-500 mt-1">Low balance! Please top up.</p>
                    @endif
                </div>
            </div>
        @else
            <p class="text-sm text-gray-500">Loading account details...</p>
        @endif
    </div>

    {{-- SMS Count Cards --}}
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
        <div class="rounded-lg bg-white shadow p-6">
            <p class="text-sm font-medium text-gray-500">SMS Sent Today</p>
            <p class="mt-1 text-3xl font-bold text-primary-600">{{ number_format($smsSentToday) }}</p>
        </div>
        <div class="rounded-lg bg-white shadow p-6">
            <p class="text-sm font-medium text-gray-500">Total SMS Sent</p>
            <p class="mt-1 text-3xl font-bold text-primary-600">{{ number_format($smsSentTotal) }}</p>
        </div>
    </div>

    {{-- SMS Logs Table --}}
    <div class="rounded-lg bg-white shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div class="sm:w-1/3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Context</label>
                    <select wire:model="filterContext" class="w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-primary-500 focus:ring-primary-500">
                        <option value="">All</option>
                        @foreach(\App\Http\Livewire\SmsDetails::contextLabels() as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">From</label>
                        <input type="date" wire:model="filterDateFrom" class="rounded-md border-gray-300 shadow-sm text-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">To</label>
                        <input type="date" wire:model="filterDateTo" class="rounded-md border-gray-300 shadow-sm text-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>
                </div>
            </div>
        </div>
        <div class="p-6">
            {{ $this->table }}
        </div>
    </div>
</div>
