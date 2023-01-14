<div>
    <h4 class="text-lg font-semibold">Office Dashboard for
        {{ auth()->user()->employee_information?->office->name ?? 'Unknown Office' }}</h4>

    <div x-data="{ tab: 'office_dv' }" x-cloak>
        <div class="inline-flex flex-row mt-2">
            <button @click="tab = 'office_dv'" :class="tab == 'office_dv' && 'bg-white -mt-2 text-primary-600'"
                class="px-4 py-2 mt-2 text-lg font-semibold rounded-t-lg hover:bg-primary-300">
                Disbursement Vouchers To Sign
            </button>
            <button @click="tab = 'dv_forwarded'" :class="tab == 'dv_forwarded' && 'bg-white -mt-2 text-primary-600'"
                class="px-4 py-2 mt-2 text-lg font-semibold rounded-t-lg hover:bg-primary-300">
                Forwarded Disbursement Vouchers
            </button>
            @if (auth()->user()->employee_information->office->office_group_id == 2)
                <button @click="tab = 'office_lr'" :class="tab == 'office_lr' && 'bg-white -mt-2 text-primary-600'"
                    class="px-4 py-2 mt-2 text-lg font-semibold rounded-t-lg hover:bg-primary-300">
                    Liquidation Reports
                </button>
                <button @click="tab = 'office_lr_forwarded'"
                    :class="tab == 'office_lr_forwarded' && 'bg-white -mt-2 text-primary-600'"
                    class="px-4 py-2 mt-2 text-lg font-semibold rounded-t-lg hover:bg-primary-300">
                    Liquidation Reports Forwarded
                </button>
            @endif
        </div>
        <div x-show="tab == 'office_dv'" class="p-4 origin-top-left bg-white"
            :class="tab == 'office_dv' && 'rounded-b-lg rounded-r-lg'"
            x-transition:enter='transform ease-out duration-200' x-transition:enter-start='scale-0'
            x-transition:enter-end='scale-100'>
            <div x-show="tab == 'office_dv'" x-transition:enter='transition fade-in duration-700'
                x-transition:enter-start='opacity-0' x-transition:enter-end='opacity-100'>
                <livewire:offices.office-disbursement-vouchers-index wire:key="office-disbursement-vouchers-index" />
            </div>
        </div>
        <div x-show="tab == 'dv_forwarded'" :class="tab == 'dv_forwarded' && 'rounded-b-lg rounded-r-lg'"
            class="origin-[10%_0] bg-white p-4" x-transition:enter='transform ease-out duration-200'
            x-transition:enter-start='scale-0' x-transition:enter-end='scale-100'>
            <div x-show="tab == 'dv_forwarded'" x-transition:enter='transition fade-in duration-700'
                x-transition:enter-start='opacity-0' x-transition:enter-end='opacity-100'>
                <livewire:offices.office-disbursement-vouchers-forwarded
                    wire:key="office-disbursement-vouchers-forwarded" />
            </div>
        </div>
        @if (auth()->user()->employee_information->office->office_group_id == 2)
            <div x-show="tab == 'office_lr'" :class="tab == 'office_lr' && 'rounded-b-lg rounded-r-lg'"
                class="origin-[10%_0] bg-white p-4" x-transition:enter='transform ease-out duration-200'
                x-transition:enter-start='scale-0' x-transition:enter-end='scale-100'>
                <div x-show="tab == 'office_lr'" x-transition:enter='transition fade-in duration-700'
                    x-transition:enter-start='opacity-0' x-transition:enter-end='opacity-100'>
                    <livewire:offices.office-liquidation-reports-index wire:key="office-liquidation-reports-index" />
                </div>
            </div>
            <div x-show="tab == 'office_lr_forwarded'"
                :class="tab == 'office_lr_forwarded' && 'rounded-b-lg rounded-r-lg'" class="origin-[10%_0] bg-white p-4"
                x-transition:enter='transform ease-out duration-200' x-transition:enter-start='scale-0'
                x-transition:enter-end='scale-100'>
                <div x-show="tab == 'office_lr_forwarded'" x-transition:enter='transition fade-in duration-700'
                    x-transition:enter-start='opacity-0' x-transition:enter-end='opacity-100'>
                    <livewire:offices.office-liquidation-reports-forwarded
                        wire:key="office-liquidation-reports-index" />
                </div>
            </div>
        @endif
    </div>
</div>
