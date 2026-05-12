<div class="p-4">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Aging of Cash Advances</h1>
            <p class="text-sm text-gray-500">Unliquidated cash advances grouped by days overdue from the liquidation deadline.</p>
        </div>
        <x-filament-support::button icon="heroicon-s-arrow-left" type="button" onclick="window.history.back()">
            Back
        </x-filament-support::button>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        @php
            $cardStyles = [
                '0-30'  => 'bg-primary-100 border-primary-300 text-primary-800',
                '31-60' => 'bg-warning-50 border-warning-300 text-warning-800',
                '61-90' => 'bg-secondary-alt-100 border-secondary-alt-300 text-secondary-alt-700',
                '90+'   => 'bg-danger-50 border-danger-300 text-danger-700',
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

    {{-- Filament Table --}}
    {{ $this->table }}
</div>
