<div>
    <h4 class="text-lg font-semibold">Cash Advance Liquidation Overview</h4>

    <div x-data="{ tab: 'unliquidated' }" x-cloak>
        <!-- Tabs -->
        <div class="mt-2 inline-flex flex-row">
            <button class="mt-2 rounded-t-lg px-4 py-2 text-lg font-semibold hover:bg-primary-300"
                @click="tab = 'unliquidated'" :class="tab === 'unliquidated' && 'bg-white -mt-2 text-primary-600'">
               For Liquidation
            </button>
            <button
                class="mt-2 flex items-center gap-2 rounded-t-lg px-4 py-2 text-lg font-semibold hover:bg-primary-300"
                @click="tab = 'liquidated'" :class="tab === 'liquidated' && 'bg-white -mt-2 text-primary-600'">
                Liquidated
            </button>
        </div>

        <!-- Liquidated Tab -->


        <!-- Unliquidated Tab -->
        <div x-show="tab === 'unliquidated'" class="origin-top-left bg-white p-4 rounded-b-lg rounded-r-lg"
            x-transition:enter="transform ease-out duration-200" x-transition:enter-start="scale-0"
            x-transition:enter-end="scale-100">
            <div x-transition:enter="transition fade-in duration-700" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100">
                <livewire:requisitioner.disbursement-vouchers.disbursement-vouchers-unliquidated />
            </div>
        </div>
        <div x-show="tab === 'liquidated'" class="origin-top-left bg-white p-4 rounded-b-lg rounded-r-lg"
            {{-- x-transition:enter="transform ease-out duration-200"
            x-transition:enter-start="scale-0"
            x-transition:enter-end="scale-100" --}}>
            <div {{-- x-transition:enter="transition fade-in duration-700"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" --}}>
                Liquidated
                {{-- <livewire:requisitioner.disbursement-vouchers.disbursement-vouchers-liquidated /> --}}
            </div>
        </div>
    </div>
</div>
