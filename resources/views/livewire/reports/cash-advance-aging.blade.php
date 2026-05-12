<div class="p-4">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Aging of Cash Advances</h1>
            <p class="text-sm text-gray-500">Unliquidated cash advances grouped by days overdue from the liquidation deadline.</p>
        </div>
        <div class="flex space-x-2">
            <x-filament-support::button icon="heroicon-s-arrow-left" type="button" onclick="window.history.back()">
                Back
            </x-filament-support::button>
            <button type="button" onclick="printDiv('printableDiv')"
                class="px-4 py-2 bg-primary-500 text-white rounded text-sm">
                Print Document
            </button>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        @php
            $cardStyles = [
                '0-30'  => 'bg-green-50 border-green-200 text-green-800',
                '31-60' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
                '61-90' => 'bg-orange-50 border-orange-200 text-orange-800',
                '90+'   => 'bg-red-50 border-red-200 text-red-800',
            ];
        @endphp
        @foreach ($bucketCounts as $label => $data)
            <div class="rounded-lg border p-4 {{ $cardStyles[$label] ?? '' }}">
                <p class="text-xs uppercase tracking-wide font-semibold">{{ $label }} days</p>
                <p class="text-2xl font-bold mt-1">{{ $data['count'] }}</p>
                <p class="text-sm mt-1">₱ {{ number_format($data['total'] ?? 0, 2) }}</p>
            </div>
        @endforeach
    </div>

    {{-- Filter bar --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-4 bg-white p-3 rounded-lg shadow-sm border">
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Aging Bucket</label>
            <select wire:model="bucket" class="block w-full rounded-md border-gray-300 text-sm">
                <option value="all">All</option>
                <option value="0-30">0 – 30 days</option>
                <option value="31-60">31 – 60 days</option>
                <option value="61-90">61 – 90 days</option>
                <option value="90+">90+ days</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Office</label>
            <select wire:model="officeId" class="block w-full rounded-md border-gray-300 text-sm">
                <option value="">All offices</option>
                @foreach ($offices as $office)
                    <option value="{{ $office->id }}">{{ $office->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="md:col-span-2">
            <label class="block text-xs font-medium text-gray-600 mb-1">Search (DV No., Tracking No., Owner)</label>
            <input type="text" wire:model.debounce.400ms="search"
                class="block w-full rounded-md border-gray-300 text-sm"
                placeholder="e.g. DV-2025-001 or John Doe">
        </div>
    </div>

    {{-- Printable Region --}}
    <div id="printableDiv" class="bg-white rounded-lg shadow-sm border p-4">
        <div class="mb-3">
            <h2 class="text-lg font-semibold text-gray-800">Aging of Cash Advances Report</h2>
            <p class="text-xs text-gray-500">Generated {{ now()->format('F d, Y h:i A') }}</p>
            @if ($bucket !== 'all')
                <p class="text-xs text-gray-500">Filter: Bucket {{ $bucket }} days</p>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-xs border-collapse">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="border px-2 py-2 text-left">DV No.</th>
                        <th class="border px-2 py-2 text-left">Tracking No.</th>
                        <th class="border px-2 py-2 text-left">Owner</th>
                        <th class="border px-2 py-2 text-left">Office</th>
                        <th class="border px-2 py-2 text-left">Date Granted</th>
                        <th class="border px-2 py-2 text-right">Amount</th>
                        <th class="border px-2 py-2 text-left">Liquidation Deadline</th>
                        <th class="border px-2 py-2 text-right">Days Overdue</th>
                        <th class="border px-2 py-2 text-left">Bucket</th>
                        <th class="border px-2 py-2 text-left">Current Stage</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rows as $row)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-2 py-1">{{ $row->dv_number ?? '—' }}</td>
                            <td class="border px-2 py-1">{{ $row->tracking_number ?? '—' }}</td>
                            <td class="border px-2 py-1">{{ $row->owner_name ?? '—' }}</td>
                            <td class="border px-2 py-1">{{ $row->office_name ?? '—' }}</td>
                            <td class="border px-2 py-1">
                                {{ $row->date_granted ? \Carbon\Carbon::parse($row->date_granted)->format('M d, Y') : '—' }}
                            </td>
                            <td class="border px-2 py-1 text-right">
                                {{ $row->amount ? number_format($row->amount, 2) : '0.00' }}
                            </td>
                            <td class="border px-2 py-1">
                                {{ $row->liquidation_deadline ? $row->liquidation_deadline->format('M d, Y') : '—' }}
                            </td>
                            <td class="border px-2 py-1 text-right">{{ $row->days_overdue }}</td>
                            <td class="border px-2 py-1">{{ $row->bucket }}</td>
                            <td class="border px-2 py-1">{{ $row->current_stage }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="border px-2 py-4 text-center text-gray-500">
                                No unliquidated cash advances match the current filters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if ($rows->count() > 0)
                    <tfoot class="bg-gray-50 font-semibold">
                        <tr>
                            <td class="border px-2 py-2 text-right" colspan="5">Total:</td>
                            <td class="border px-2 py-2 text-right">
                                {{ number_format($rows->sum('amount'), 2) }}
                            </td>
                            <td class="border px-2 py-2" colspan="4">{{ $rows->count() }} record(s)</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    @push('scripts')
        <script>
            function printDiv(divName) {
                var printContents = document.getElementById(divName).innerHTML;
                var originalContents = document.body.innerHTML;
                document.body.innerHTML = printContents;
                window.print();
                document.body.innerHTML = originalContents;
                window.location.reload();
            }
        </script>
    @endpush
</div>
