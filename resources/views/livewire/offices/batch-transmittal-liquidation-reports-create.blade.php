<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold text-primary-600">Create Batch Transmittal — Liquidation Reports</h2>
        <a href="{{ route('office.batch-transmittal.index') }}"
           class="inline-flex items-center gap-1 rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
            Back to List
        </a>
    </div>

    <div class="rounded-lg bg-white p-5 shadow-sm">
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <span class="font-semibold text-gray-600">From:</span>
                <span class="ml-1">{{ $officeName }}</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="font-semibold text-gray-600">To:</span>
                @if (count($availableDestinations) > 1)
                    <select wire:model="destination" class="rounded-md border-gray-300 text-sm">
                        <option value="">-- Select Destination --</option>
                        @foreach ($availableDestinations as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                @else
                    <span class="ml-1">{{ $destination }}</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Scan input --}}
    <div class="rounded-lg border border-gray-300 bg-white p-4 shadow-sm">
        <input class="w-full rounded-md border-gray-300 py-2" type="text"
               wire:model.lazy="scanInput"
               placeholder="Scan QR / Enter tracking number to add LR to batch">
        <p class="mt-1 text-xs text-blue-600"><strong class="italic">Tip:</strong> Click the input field, then scan the QR code.</p>
    </div>

    {{-- Available LRs table --}}
    <div class="rounded-lg bg-white p-4 shadow-sm">
        <div class="mb-3 flex items-center justify-between">
            <h3 class="font-semibold text-gray-700">Available Certified Liquidation Reports ({{ $this->forwardableLrs->count() }})</h3>
            <div class="flex gap-2">
                <button wire:click="selectAll" class="rounded-md border border-gray-300 px-3 py-1 text-xs font-semibold text-gray-600 hover:bg-gray-50">Select All</button>
                <button wire:click="deselectAll" class="rounded-md border border-gray-300 px-3 py-1 text-xs font-semibold text-gray-600 hover:bg-gray-50">Deselect All</button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b bg-gray-50">
                    <tr>
                        <th class="px-3 py-2"></th>
                        <th class="px-3 py-2">Tracking No.</th>
                        <th class="px-3 py-2">Requisitioner</th>
                        <th class="px-3 py-2">Disbursement Voucher</th>
                        <th class="px-3 py-2">Amount</th>
                        <th class="px-3 py-2">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->forwardableLrs as $lr)
                        <tr class="border-b hover:bg-gray-50 {{ in_array($lr->id, $selectedLrs) ? 'bg-green-50' : '' }}">
                            <td class="px-3 py-2">
                                <input type="checkbox"
                                       wire:click="toggleLr({{ $lr->id }})"
                                       {{ in_array($lr->id, $selectedLrs) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            </td>
                            <td class="px-3 py-2 font-mono text-xs">{{ $lr->tracking_number }}</td>
                            <td class="px-3 py-2">{{ $lr->requisitioner?->employee_information?->full_name ?? '—' }}</td>
                            <td class="px-3 py-2 font-mono text-xs">{{ $lr->disbursement_voucher?->tracking_number ?? '—' }}</td>
                            <td class="px-3 py-2 whitespace-nowrap">₱{{ number_format($lr->total_amount, 2) }}</td>
                            <td class="px-3 py-2">
                                @if (in_array($lr->id, $selectedLrs))
                                    <input type="text"
                                           wire:model.lazy="lrRemarks.{{ $lr->id }}"
                                           class="w-full rounded-md border-gray-300 text-xs"
                                           placeholder="Optional remarks">
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-3 py-8 text-center text-gray-400">No certified liquidation reports ready to forward.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Submit --}}
    @if (count($selectedLrs) > 0)
        <div class="flex items-center justify-between rounded-lg bg-white p-4 shadow-sm">
            <span class="text-sm font-semibold text-gray-700">{{ count($selectedLrs) }} LR(s) selected</span>
            <button wire:click="createAndForward"
                    wire:loading.attr="disabled"
                    onclick="return confirm('Create and forward batch with {{ count($selectedLrs) }} LR(s)?')"
                    class="inline-flex items-center gap-1 rounded-md bg-primary-600 px-6 py-2 text-sm font-semibold text-white hover:bg-primary-700 disabled:opacity-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                </svg>
                Create Batch & Forward
            </button>
        </div>
    @endif
</div>
