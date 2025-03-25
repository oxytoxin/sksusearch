<div>
    <h4 class="text-lg font-semibold">Office Dashboard for {{ auth()->user()->employee_information?->office->name ?? 'Unknown Office' }}</h4>

    <div x-data="{ tab: 'office_dv' }" x-cloak>
        <div class="mt-2 inline-flex flex-row">
            <button class="mt-2 flex items-center gap-2 rounded-t-lg px-4 py-2 text-lg font-semibold hover:bg-primary-300" @click="tab = 'office_dv'" :class="tab == 'office_dv' && 'bg-white -mt-2 text-primary-600'">
                Disbursement Vouchers To Sign
                @if ($disbursement_vouchers_count > 0)
                    <span class="mx-auto inline-flex h-2 w-2 items-center justify-center rounded-full bg-primary-200 p-3 text-xs font-semibold text-primary-600">
                        {{ $disbursement_vouchers_count }}
                    </span>
                @endif
            </button>
            <button class="mt-2 rounded-t-lg px-4 py-2 text-lg font-semibold hover:bg-primary-300" @click="tab = 'dv_forwarded'" :class="tab == 'dv_forwarded' && 'bg-white -mt-2 text-primary-600'">
                Forwarded Disbursement Vouchers
            </button>
            @if (auth()->user()->employee_information->office->office_group_id == 2)
                <button class="mt-2 flex items-center gap-2 rounded-t-lg px-4 py-2 text-lg font-semibold hover:bg-primary-300" @click="tab = 'office_lr'" :class="tab == 'office_lr' && 'bg-white -mt-2 text-primary-600'">
                    Liquidation Reports
                    @if ($liquidation_reports_count > 0)
                        <span class="mx-auto inline-flex h-2 w-2 items-center justify-center rounded-full bg-primary-200 p-3 text-xs font-semibold text-primary-600">
                            {{ $liquidation_reports_count }}
                        </span>
                    @endif
                </button>
                <button class="mt-2 rounded-t-lg px-4 py-2 text-lg font-semibold hover:bg-primary-300" @click="tab = 'office_lr_forwarded'" :class="tab == 'office_lr_forwarded' && 'bg-white -mt-2 text-primary-600'">
                    Liquidation Reports Forwarded
                </button>
            @endif
        </div>
        <div class="origin-top-left bg-white p-4" x-show="tab == 'office_dv'" :class="tab == 'office_dv' && 'rounded-b-lg rounded-r-lg'" x-transition:enter='transform ease-out duration-200' x-transition:enter-start='scale-0' x-transition:enter-end='scale-100'>
            <div x-show="tab == 'office_dv'" x-transition:enter='transition fade-in duration-700' x-transition:enter-start='opacity-0' x-transition:enter-end='opacity-100'>
                <livewire:offices.office-disbursement-vouchers-index wire:key="office-disbursement-vouchers-index" />
            </div>
        </div>
        <div class="origin-[10%_0] bg-white p-4" x-show="tab == 'dv_forwarded'" :class="tab == 'dv_forwarded' && 'rounded-b-lg rounded-r-lg'" x-transition:enter='transform ease-out duration-200' x-transition:enter-start='scale-0' x-transition:enter-end='scale-100'>
            <div x-show="tab == 'dv_forwarded'" x-transition:enter='transition fade-in duration-700' x-transition:enter-start='opacity-0' x-transition:enter-end='opacity-100'>
                <livewire:offices.office-disbursement-vouchers-forwarded wire:key="office-disbursement-vouchers-forwarded" />
            </div>
        </div>
        @if (auth()->user()->employee_information->office->office_group_id == 2)
            <div class="origin-[10%_0] bg-white p-4" x-show="tab == 'office_lr'" :class="tab == 'office_lr' && 'rounded-b-lg rounded-r-lg'" x-transition:enter='transform ease-out duration-200' x-transition:enter-start='scale-0' x-transition:enter-end='scale-100'>
                <div x-show="tab == 'office_lr'" x-transition:enter='transition fade-in duration-700' x-transition:enter-start='opacity-0' x-transition:enter-end='opacity-100'>
                    <livewire:offices.office-liquidation-reports-index wire:key="office-liquidation-reports-index" />
                </div>
            </div>
            <div class="origin-[10%_0] bg-white p-4" x-show="tab == 'office_lr_forwarded'" :class="tab == 'office_lr_forwarded' && 'rounded-b-lg rounded-r-lg'" x-transition:enter='transform ease-out duration-200' x-transition:enter-start='scale-0' x-transition:enter-end='scale-100'>
                <div x-show="tab == 'office_lr_forwarded'" x-transition:enter='transition fade-in duration-700' x-transition:enter-start='opacity-0' x-transition:enter-end='opacity-100'>
                    <livewire:offices.office-liquidation-reports-forwarded wire:key="office-liquidation-reports-index" />
                </div>
            </div>
        @endif
    </div>
</div>
