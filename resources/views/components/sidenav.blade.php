<nav class="flex flex-col px-2 space-y-1 md:mt-5" x-data x-cloak>

    <div class="space-y-1" x-data="{ open: false }">
        @php
            $to_sign_count = App\Models\DisbursementVoucher::whereSignatoryId(auth()->id())
                ->where('current_step_id', '<=', 4000)
                ->where('previous_step_id', '<=', 4000)
                ->count();
        @endphp
        <!-- Current: "bg-primary-100 text-primary-900", Default: "bg-white text-primary-600 hover:bg-primary-50 hover:text-primary-900" -->
        <button class="flex items-center w-full py-2 pr-2 text-sm font-medium text-left rounded-md text-primary-600 group hover:bg-primary-50 hover:text-primary-900 focus:ring-primary-500 focus:outline-none focus:ring-2"
                type="button" aria-controls="sub-menu-1" aria-expanded="false" x-on:click="open=!open">
            <!-- Expanded: "text-primary-400 rotate-90", Collapsed: "text-primary-300" -->
            <svg class="" aria-hidden="true"
                 :class="open ?
                     'rotate-90 flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400' :
                     'flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400'"
                 viewBox="0 0 20 20">
                <path d="M6 6L14 10L6 14V6Z" fill="currentColor" />
            </svg>
            Disbursement Vouchers
            @if ($to_sign_count > 0)
                <span class="inline-flex items-center justify-center w-2 h-2 p-3 mx-auto text-xs font-medium rounded-full text-primary-600 bg-primary-100">
                    {{ $to_sign_count }}
                </span>
            @endif
        </button>
        <!-- Expandable link section, show/hide based on state. -->
        <div class="space-y-1" class="origin-top-left" id="sub-menu-1" x-show='open' x-transition:enter='transition ease-out duration-300' x-transition:enter-start='opacity-0 scale-95'
             x-transition:enter-end='opacity-100 scale-100' x-transition:leave='transition ease-in duration-300' x-transition:leave-start='opacity-100 scale-100'
             x-transition:leave-end='opacity-0 scale-95'>
            {{-- drafts --}}
            <a class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900" href="#">
                Drafts
                {{-- <span
                    class="inline-flex items-center justify-center w-2 h-2 p-3 mx-auto text-xs font-medium rounded-full text-primary-600 bg-primary-100">3</span> --}}
            </a>

            {{-- pending dv's --}}
            <a class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900"
               href="{{ route('requisitioner.disbursement-vouchers.index') }}">
                Submitted
                {{-- <span
                    class="inline-flex items-center justify-center w-2 h-2 p-3 mx-auto text-xs font-medium rounded-full text-primary-600 bg-primary-100">3</span> --}}
            </a>

            {{-- cancelled dv's --}}
            <a class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900"
               href="{{ route('requisitioner.disbursement-vouchers.cancelled') }}">
                Cancelled
                {{-- <span
                    class="inline-flex items-center justify-center w-2 h-2 p-3 mx-auto text-xs font-medium rounded-full text-primary-600 bg-primary-100">3</span> --}}
            </a>

            {{-- signatory dv's --}}

            <a class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900"
               href="{{ route('signatory.disbursement-vouchers.index') }}">
                Signatory
                @if ($to_sign_count > 0)
                    <span class="inline-flex items-center justify-center w-2 h-2 p-3 mx-auto text-xs font-medium rounded-full text-primary-600 bg-primary-100">
                        {{ $to_sign_count }}
                    </span>
                @endif
            </a>
        </div>
    </div>

    {{-- unliquidated dv's --}}
    <div class="space-y-1" x-data="{ open: false }">
        <button class="flex items-center w-full py-2 pr-2 text-sm font-medium text-left rounded-md text-primary-600 group hover:bg-primary-50 hover:text-primary-900 focus:ring-primary-500 focus:outline-none focus:ring-2"
                type="button" aria-controls="sub-menu-1" aria-expanded="false" x-on:click="open=!open">
            <!-- Expanded: "text-primary-400 rotate-90", Collapsed: "text-primary-300" -->
            <svg class="" aria-hidden="true"
                 :class="open ?
                     'rotate-90 flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400' :
                     'flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400'"
                 viewBox="0 0 20 20">
                <path d="M6 6L14 10L6 14V6Z" fill="currentColor" />
            </svg>
            Unliquidated DVs
        </button>

        {{-- for unliquidated --}}

        {{-- <div class="space-y-1" id="sub-menu-1" x-show='open' x-transition:enter='transition ease-out duration-300'
            x-transition:enter-start='opacity-0 scale-95' x-transition:enter-end='opacity-100 scale-100'
            x-transition:leave='transition ease-in duration-300' x-transition:leave-start='opacity-100 scale-100'
            x-transition:leave-end='opacity-0 scale-95' class="origin-top-left">
            <a href="{{ route('requisitioner.travel-orders.index') }}"
                class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900">
                My Travel Orders
            </a>
            <a href="{{ route('signatory.travel-orders.index') }}"
                class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900">
                Signatory Travel orders
            </a>
        </div> --}}
    </div>

    {{-- liquidation reports --}}
    <div class="space-y-1" x-data="{ open: false }">
        <button class="flex items-center w-full py-2 pr-2 text-sm font-medium text-left rounded-md text-primary-600 group hover:bg-primary-50 hover:text-primary-900 focus:ring-primary-500 focus:outline-none focus:ring-2"
                type="button" aria-controls="sub-menu-1" aria-expanded="false" x-on:click="open=!open">
            <!-- Expanded: "text-primary-400 rotate-90", Collapsed: "text-primary-300" -->
            <svg class="" aria-hidden="true"
                 :class="open ?
                     'rotate-90 flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400' :
                     'flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400'"
                 viewBox="0 0 20 20">
                <path d="M6 6L14 10L6 14V6Z" fill="currentColor" />
            </svg>
            Liquidation Reports
        </button>

        {{-- for liquidation reports --}}

        <div class="space-y-1" class="origin-top-left" id="sub-menu-1" x-show='open' x-transition:enter='transition ease-out duration-300' x-transition:enter-start='opacity-0 scale-95'
             x-transition:enter-end='opacity-100 scale-100' x-transition:leave='transition ease-in duration-300' x-transition:leave-start='opacity-100 scale-100'
             x-transition:leave-end='opacity-0 scale-95'>

            <a class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900" href="#">
                Drafts
            </a>

            <a class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900"
               href="{{ route('requisitioner.liquidation-reports.index') }}">
                Submitted
            </a>

            <a class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900"
               href="{{ route('requisitioner.liquidation-reports.cancelled') }}">
                Cancelled
            </a>

            <a class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900"
               href="{{ route('signatory.liquidation-reports.index') }}">
                Signatory
            </a>
        </div>
    </div>

    <div class="space-y-1" x-data="{ open: false }">
        <button class="flex items-center w-full py-2 pr-2 text-sm font-medium text-left rounded-md text-primary-600 group hover:bg-primary-50 hover:text-primary-900 focus:ring-primary-500 focus:outline-none focus:ring-2"
                type="button" aria-controls="sub-menu-1" aria-expanded="false" x-on:click="open=!open">
            <!-- Expanded: "text-primary-400 rotate-90", Collapsed: "text-primary-300" -->
            <svg class="" aria-hidden="true"
                 :class="open ?
                     'rotate-90 flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400' :
                     'flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400'"
                 viewBox="0 0 20 20">
                <path d="M6 6L14 10L6 14V6Z" fill="currentColor" />
            </svg>
            Travel Orders
        </button>
        <!-- Expandable link section, show/hide based on state. -->
        <div class="space-y-1" class="origin-top-left" id="sub-menu-1" x-show='open' x-transition:enter='transition ease-out duration-300' x-transition:enter-start='opacity-0 scale-95'
             x-transition:enter-end='opacity-100 scale-100' x-transition:leave='transition ease-in duration-300' x-transition:leave-start='opacity-100 scale-100'
             x-transition:leave-end='opacity-0 scale-95'>

            {{-- drafts --}}
            <a class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900" href="#">
                Drafts
            </a>

            {{-- pending travel orders --}}
            <a class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900"
               href="{{ route('requisitioner.travel-orders.index') }}">
                Submitted
            </a>

            <a class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900"
               href="{{ route('signatory.travel-orders.index') }}">
                For Signature
            </a>

            {{-- signed travel orders --}}
            <a class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900" href="">
                Signed
            </a>
        </div>
    </div>
    @if (in_array(auth()->user()->employee_information->position_id, [24, 12, 15]))
        <div class="space-y-1" x-data="{ open: false }">
            <button class="flex items-center w-full py-2 pr-2 text-sm font-medium text-left rounded-md text-primary-600 group hover:bg-primary-50 hover:text-primary-900 focus:ring-primary-500 focus:outline-none focus:ring-2"
                    type="button" aria-controls="sub-menu-1" aria-expanded="false" x-on:click="open=!open">
                <!-- Expanded: "text-primary-400 rotate-90", Collapsed: "text-primary-300" -->
                <svg class="" aria-hidden="true"
                     :class="open ?
                         'rotate-90 flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400' :
                         'flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400'"
                     viewBox="0 0 20 20">
                    <path d="M6 6L14 10L6 14V6Z" fill="currentColor" />
                </svg>
                Archives
            </button>
            <!-- Expandable link section, show/hide based on state. -->
            <div class="space-y-1" class="origin-top-left" id="sub-menu-1" x-show='open' x-transition:enter='transition ease-out duration-300' x-transition:enter-start='opacity-0 scale-95'
                 x-transition:enter-end='opacity-100 scale-100' x-transition:leave='transition ease-in duration-300' x-transition:leave-start='opacity-100 scale-100'
                 x-transition:leave-end='opacity-0 scale-95'>
                <a class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900"
                   href="{{ route('archiver.view-archives') }}">
                    Archived Documents
                </a>
                @if (in_array(auth()->user()->employee_information->position_id, [24]) || auth()->user()->id == 19)
                    <a class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900"
                       href="{{ route('archiver.archive-doc.create') }}">
                        Upload Documents
                    </a>
                    <a class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900"
                       href="{{ route('archiver.archive-leg-doc.create') }}">
                        Upload Legacy Documents
                    </a>
                    <a class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900"
                       href="{{ route('archiver.archive-cheques.create') }}">
                        Upload Stale / Cancelled Cheques
                    </a>
                    <a class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900"
                       href="{{ route('requisitioner.travel-orders.index') }}">
                        Assign Documents
                    </a>
                @endif
            </div>
        </div>
    @endif
    <div class="space-y-1" x-data="{ open: false }">
        <button class="flex items-center w-full py-2 pr-2 text-sm font-medium text-left rounded-md text-primary-600 group hover:bg-primary-50 hover:text-primary-900 focus:ring-primary-500 focus:outline-none focus:ring-2"
                type="button" aria-controls="sub-menu-1" aria-expanded="false" x-on:click="open=!open">
            <!-- Expanded: "text-primary-400 rotate-90", Collapsed: "text-primary-300" -->
            <svg class="" aria-hidden="true"
                 :class="open ?
                     'rotate-90 flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400' :
                     'flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400'"
                 viewBox="0 0 20 20">
                <path d="M6 6L14 10L6 14V6Z" fill="currentColor" />
            </svg>
            Petty Cash Vouchers
        </button>
        @php
            $isCustodian = auth()
                ->user()
                ->petty_cash_fund()
                ->exists();
        @endphp
        <!-- Expandable link section, show/hide based on state. -->
        <div class="space-y-1" class="origin-top-left" id="sub-menu-1" x-show='open' x-transition:enter='transition ease-out duration-300' x-transition:enter-start='opacity-0 scale-95'
             x-transition:enter-end='opacity-100 scale-100' x-transition:leave='transition ease-in duration-300' x-transition:leave-start='opacity-100 scale-100'
             x-transition:leave-end='opacity-0 scale-95'>
            @if ($isCustodian)
                <a class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900"
                   href="{{ route('pcv.index') }}">
                    Petty Cash Vouchers
                </a>
            @endif
            <a class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900" href="{{ route('pcv.rppcv') }}">
                Report on Paid Petty Cash Vouchers
            </a>
            <a class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900"
               href="{{ route('pcv.pcf.record') }}">
                Petty Cash Fund Record
            </a>
            @if ($isCustodian)
                <a class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900"
                   href="{{ route('pcv.pcf.replenish') }}">
                    Replenish Petty Cash Fund
                </a>
            @endif

        </div>
        @php
            $motorpool_head = App\Models\Office::where('name', 'like', '%Motorpool%')->first();
            // dd($motorpool_head?->admin_user_id.' ?= '.auth()->user()->id.$motorpool_head);
        @endphp

        <div class="space-y-1" x-data="{ open: false }">
            <button class="flex items-center w-full py-2 pr-2 text-sm font-medium text-left rounded-md text-primary-600 group hover:bg-primary-50 hover:text-primary-900 focus:ring-primary-500 focus:outline-none focus:ring-2"
                    type="button" aria-controls="sub-menu-1" aria-expanded="false" x-on:click="open=!open">
                <!-- Expanded: "text-primary-400 rotate-90", Collapsed: "text-primary-300" -->
                <svg class="" aria-hidden="true"
                     :class="open ?
                         'rotate-90 flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400' :
                         'flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400'"
                     viewBox="0 0 20 20">
                    <path d="M6 6L14 10L6 14V6Z" fill="currentColor" />
                </svg>
                Motorpool
            </button>
            <!-- Expandable link section, show/hide based on state. -->
            <div class="space-y-1" class="origin-top-left" id="sub-menu-1" x-show='open' x-transition:enter='transition ease-out duration-300' x-transition:enter-start='opacity-0 scale-95'
                 x-transition:enter-end='opacity-100 scale-100' x-transition:leave='transition ease-in duration-300' x-transition:leave-start='opacity-100 scale-100'
                 x-transition:leave-end='opacity-0 scale-95'>
                @if ($motorpool_head?->head_id == auth()->user()->id || $motorpool_head?->admin_user_id == auth()->user()->id)
                    <a class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900"
                       href="{{ route('motorpool.vehicle.index') }}">
                        Vehicles
                    </a>
                    <a class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900"
                       href="{{ route('motorpool.request.index') }}">
                        Requests
                    </a>
                @endif
                <a class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900"
                   href="{{ route('motorpool.weekly-schedule') }}">
                    Schedules
                </a>
            </div>
        </div>

    </div>
</nav>
