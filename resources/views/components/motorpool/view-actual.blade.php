<div class="space-y-6 p-4">
    {{-- Comparison Section --}}
    <div class="grid grid-cols-2 gap-6">
        {{-- Requested Values --}}
        <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
            <h3 class="text-lg font-semibold mb-3 text-gray-700 dark:text-gray-300">REQUESTED</h3>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-600 dark:text-gray-400">Quantity:</dt>
                    <dd class="font-semibold">{{ $record->quantity }} {{ $record->unit }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-600 dark:text-gray-400">Article:</dt>
                    <dd class="font-semibold">{{ $record->article }}</dd>
                </div>
                @if($record->requested_unit_price)
                <div class="flex justify-between">
                    <dt class="text-gray-600 dark:text-gray-400">Unit Price:</dt>
                    <dd class="font-semibold">₱{{ number_format($record->requested_unit_price, 2) }}</dd>
                </div>
                @endif
                @if($record->requested_total_amount)
                <div class="flex justify-between border-t pt-2 mt-2">
                    <dt class="text-gray-600 dark:text-gray-400 font-bold">Total Amount:</dt>
                    <dd class="font-bold text-lg">₱{{ number_format($record->requested_total_amount, 2) }}</dd>
                </div>
                @endif
                <div class="flex justify-between border-t pt-2 mt-2">
                    <dt class="text-gray-600 dark:text-gray-400">Purpose:</dt>
                    <dd class="font-semibold">{{ $record->purpose }}</dd>
                </div>
            </dl>
        </div>

        {{-- Actual Values --}}
        <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
            <h3 class="text-lg font-semibold mb-3 text-green-700 dark:text-green-300">ACTUAL</h3>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-600 dark:text-gray-400">Quantity:</dt>
                    <dd class="font-semibold">{{ $record->actual_quantity }} {{ $record->unit }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-600 dark:text-gray-400">Unit Price:</dt>
                    <dd class="font-semibold">₱{{ number_format($record->actual_unit_price, 2) }}</dd>
                </div>
                <div class="flex justify-between border-t pt-2 mt-2">
                    <dt class="text-gray-600 dark:text-gray-400 font-bold">Total Amount:</dt>
                    <dd class="font-bold text-lg">₱{{ number_format($record->actual_total_amount, 2) }}</dd>
                </div>
            </dl>
        </div>
    </div>

    {{-- Fuel Details --}}
    <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
        <h3 class="text-lg font-semibold mb-3 text-blue-700 dark:text-blue-300">Fuel Details</h3>
        <dl class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <dt class="text-gray-600 dark:text-gray-400">OR Number:</dt>
                <dd class="font-semibold">{{ $record->actual_or_number }}</dd>
            </div>
            <div>
                <dt class="text-gray-600 dark:text-gray-400">Date & Time:</dt>
                <dd class="font-semibold">
                    {{ \Carbon\Carbon::parse($record->actual_date)->format('M d, Y') }}
                    {{ \Carbon\Carbon::parse($record->actual_time)->format('h:i A') }}
                </dd>
            </div>
            <div class="col-span-2">
                <dt class="text-gray-600 dark:text-gray-400">Supplier/Attendant:</dt>
                <dd class="font-semibold">{{ $record->actual_supplier_attendant }}</dd>
            </div>
        </dl>
    </div>

    {{-- Status Badge --}}
    <div class="flex items-center justify-center p-4 bg-green-100 dark:bg-green-900/30 rounded-lg">
        <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span class="text-green-700 dark:text-green-300 font-semibold">Completed - Record Locked</span>
    </div>

    @if($record->updated_at)
    <div class="text-center text-xs text-gray-500 dark:text-gray-400">
        Last updated: {{ $record->updated_at->format('M d, Y h:i A') }}
    </div>
    @endif
</div>
