<nav x-data x-cloak class="flex flex-col px-2 space-y-1 md:mt-5">

    <div class="space-y-1" x-data="{ open: false }">
        @php
            $to_sign_count = App\Models\DisbursementVoucher::whereSignatoryId(auth()->id())
                ->where('current_step_id', '<=', 4000)
                ->where('previous_step_id', '<=', 4000)
                ->count();
        @endphp
        <!-- Current: "bg-primary-100 text-primary-900", Default: "bg-white text-primary-600 hover:bg-primary-50 hover:text-primary-900" -->
        <button x-on:click="open=!open" type="button"
            class="flex items-center w-full py-2 pr-2 text-sm font-medium text-left rounded-md text-primary-600 group hover:bg-primary-50 hover:text-primary-900 focus:ring-primary-500 focus:outline-none focus:ring-2"
            aria-controls="sub-menu-1" aria-expanded="false">
            <!-- Expanded: "text-primary-400 rotate-90", Collapsed: "text-primary-300" -->
            <svg :class="open ?
                'rotate-90 flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400' :
                'flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400'"
                class="" viewBox="0 0 20 20" aria-hidden="true">
                <path d="M6 6L14 10L6 14V6Z" fill="currentColor" />
            </svg>
            Disbursement Vouchers
            @if ($to_sign_count > 0)
                <span
                    class="inline-flex items-center justify-center w-2 h-2 p-3 mx-auto text-xs font-medium rounded-full text-primary-600 bg-primary-100">
                    {{ $to_sign_count }}
                </span>
            @endif
        </button>
        <!-- Expandable link section, show/hide based on state. -->
        <div class="space-y-1" id="sub-menu-1" x-show='open' x-transition:enter='transition ease-out duration-300'
            x-transition:enter-start='opacity-0 scale-95' x-transition:enter-end='opacity-100 scale-100'
            x-transition:leave='transition ease-in duration-300' x-transition:leave-start='opacity-100 scale-100'
            x-transition:leave-end='opacity-0 scale-95' class="origin-top-left">
            {{-- drafts --}}
            <a href="#"
                class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900">
                Drafts
                {{-- <span
                    class="inline-flex items-center justify-center w-2 h-2 p-3 mx-auto text-xs font-medium rounded-full text-primary-600 bg-primary-100">3</span> --}}
            </a>

            {{-- pending dv's --}}
            <a href="{{ route('requisitioner.disbursement-vouchers.index') }}"
                class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900">
                Submitted
                {{-- <span
                    class="inline-flex items-center justify-center w-2 h-2 p-3 mx-auto text-xs font-medium rounded-full text-primary-600 bg-primary-100">3</span> --}}
            </a>

            {{-- cancelled dv's --}}
            <a href="{{ route('requisitioner.disbursement-vouchers.cancelled') }}"
                class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900">
                Cancelled
                {{-- <span
                    class="inline-flex items-center justify-center w-2 h-2 p-3 mx-auto text-xs font-medium rounded-full text-primary-600 bg-primary-100">3</span> --}}
            </a>

            {{-- signatory dv's --}}

            <a href="{{ route('signatory.disbursement-vouchers.index') }}"
                class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900">
                For Signature
                @if ($to_sign_count > 0)
                    <span
                        class="inline-flex items-center justify-center w-2 h-2 p-3 mx-auto text-xs font-medium rounded-full text-primary-600 bg-primary-100">
                        {{ $to_sign_count }}
                    </span>
                @endif

            </a>

            {{-- closed dv's --}}
            <a href="#"
                class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900">
                Signed
                {{-- <span
                    class="inline-flex items-center justify-center w-2 h-2 p-3 mx-auto text-xs font-medium rounded-full text-primary-600 bg-primary-100">3</span> --}}
            </a>
        </div>
    </div>

    {{-- unliquidated dv's --}}
    <div class="space-y-1" x-data="{ open: false }">
        <button x-on:click="open=!open" type="button"
            class="flex items-center w-full py-2 pr-2 text-sm font-medium text-left rounded-md text-primary-600 group hover:bg-primary-50 hover:text-primary-900 focus:ring-primary-500 focus:outline-none focus:ring-2"
            aria-controls="sub-menu-1" aria-expanded="false">
            <!-- Expanded: "text-primary-400 rotate-90", Collapsed: "text-primary-300" -->
            <svg :class="open ?
                'rotate-90 flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400' :
                'flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400'"
                class="" viewBox="0 0 20 20" aria-hidden="true">
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
        <button x-on:click="open=!open" type="button"
            class="flex items-center w-full py-2 pr-2 text-sm font-medium text-left rounded-md text-primary-600 group hover:bg-primary-50 hover:text-primary-900 focus:ring-primary-500 focus:outline-none focus:ring-2"
            aria-controls="sub-menu-1" aria-expanded="false">
            <!-- Expanded: "text-primary-400 rotate-90", Collapsed: "text-primary-300" -->
            <svg :class="open ?
                'rotate-90 flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400' :
                'flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400'"
                class="" viewBox="0 0 20 20" aria-hidden="true">
                <path d="M6 6L14 10L6 14V6Z" fill="currentColor" />
            </svg>
            Liquidation Reports
        </button>

        {{-- for liquidation reports --}}

        <div class="space-y-1" id="sub-menu-1" x-show='open' x-transition:enter='transition ease-out duration-300'
            x-transition:enter-start='opacity-0 scale-95' x-transition:enter-end='opacity-100 scale-100'
            x-transition:leave='transition ease-in duration-300' x-transition:leave-start='opacity-100 scale-100'
            x-transition:leave-end='opacity-0 scale-95' class="origin-top-left">

            <a href=""
                class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900">
                Drafts
            </a>

            <a href=""
                class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900">
                Submitted
            </a>

            <a href=""
                class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900">
                For Signature
            </a>

            <a href=""
                class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900">
                Signed
            </a>
        </div>
    </div>

    <div class="space-y-1" x-data="{ open: false }">
        <button x-on:click="open=!open" type="button"
            class="flex items-center w-full py-2 pr-2 text-sm font-medium text-left rounded-md text-primary-600 group hover:bg-primary-50 hover:text-primary-900 focus:ring-primary-500 focus:outline-none focus:ring-2"
            aria-controls="sub-menu-1" aria-expanded="false">
            <!-- Expanded: "text-primary-400 rotate-90", Collapsed: "text-primary-300" -->
            <svg :class="open ?
                'rotate-90 flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400' :
                'flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400'"
                class="" viewBox="0 0 20 20" aria-hidden="true">
                <path d="M6 6L14 10L6 14V6Z" fill="currentColor" />
            </svg>
            Travel Orders
        </button>
        <!-- Expandable link section, show/hide based on state. -->
        <div class="space-y-1" id="sub-menu-1" x-show='open' x-transition:enter='transition ease-out duration-300'
            x-transition:enter-start='opacity-0 scale-95' x-transition:enter-end='opacity-100 scale-100'
            x-transition:leave='transition ease-in duration-300' x-transition:leave-start='opacity-100 scale-100'
            x-transition:leave-end='opacity-0 scale-95' class="origin-top-left">

            {{-- drafts --}}
            <a href="#"
                class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900">
                Drafts
            </a>

            {{-- pending travel orders --}}
            <a href="{{ route('requisitioner.travel-orders.index') }}"
                class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900">
                Submitted
            </a>

            <a href="{{ route('signatory.travel-orders.index') }}"
                class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900">
                For Signature
            </a>

            {{-- signed travel orders --}}
            <a href=""
                class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900">
                Signed
            </a>
        </div>
    </div>
    @if (in_array(auth()->user()->employee_information->position_id, [24, 12, 15]))
        <div class="space-y-1" x-data="{ open: false }">
            <button x-on:click="open=!open" type="button"
                class="flex items-center w-full py-2 pr-2 text-sm font-medium text-left rounded-md text-primary-600 group hover:bg-primary-50 hover:text-primary-900 focus:ring-primary-500 focus:outline-none focus:ring-2"
                aria-controls="sub-menu-1" aria-expanded="false">
                <!-- Expanded: "text-primary-400 rotate-90", Collapsed: "text-primary-300" -->
                <svg :class="open ?
                    'rotate-90 flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400' :
                    'flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400'"
                    class="" viewBox="0 0 20 20" aria-hidden="true">
                    <path d="M6 6L14 10L6 14V6Z" fill="currentColor" />
                </svg>
                Archives
            </button>
            <!-- Expandable link section, show/hide based on state. -->
            <div class="space-y-1" id="sub-menu-1" x-show='open'
                x-transition:enter='transition ease-out duration-300' x-transition:enter-start='opacity-0 scale-95'
                x-transition:enter-end='opacity-100 scale-100' x-transition:leave='transition ease-in duration-300'
                x-transition:leave-start='opacity-100 scale-100' x-transition:leave-end='opacity-0 scale-95'
                class="origin-top-left">
                <a href="{{ route('archiver.view-archives') }}"
                    class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900">
                    Archived Documents
                </a>
                @if (in_array(auth()->user()->employee_information->position_id, [24]) || auth()->user()->id == 19)
                    <a href="{{ route('archiver.archive-doc.create') }}"
                        class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900">
                        Upload Documents
                    </a>
                    <a href="{{ route('archiver.archive-leg-doc.create') }}"
                        class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900">
                        Upload Legacy Documents
                    </a>
                    <a href="{{ route('requisitioner.travel-orders.index') }}"
                        class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900">
                        Assign Documents
                    </a>
                @endif
            </div>
        </div>
    @endif
    <div class="space-y-1" x-data="{ open: false }">
        <button x-on:click="open=!open" type="button"
            class="flex items-center w-full py-2 pr-2 text-sm font-medium text-left rounded-md text-primary-600 group hover:bg-primary-50 hover:text-primary-900 focus:ring-primary-500 focus:outline-none focus:ring-2"
            aria-controls="sub-menu-1" aria-expanded="false">
            <!-- Expanded: "text-primary-400 rotate-90", Collapsed: "text-primary-300" -->
            <svg :class="open ?
                'rotate-90 flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400' :
                'flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400'"
                class="" viewBox="0 0 20 20" aria-hidden="true">
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
        <div class="space-y-1" id="sub-menu-1" x-show='open' x-transition:enter='transition ease-out duration-300'
            x-transition:enter-start='opacity-0 scale-95' x-transition:enter-end='opacity-100 scale-100'
            x-transition:leave='transition ease-in duration-300' x-transition:leave-start='opacity-100 scale-100'
            x-transition:leave-end='opacity-0 scale-95' class="origin-top-left">
            @if ($isCustodian)
                <a href="{{ route('pcv.index') }}"
                    class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900">
                    Petty Cash Vouchers
                </a>
            @endif
            <a href="{{ route('pcv.rppcv') }}"
                class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900">
                Report on Paid Petty Cash Vouchers
            </a>
            <a href="{{ route('pcv.pcf.record') }}"
                class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900">
                Petty Cash Fund Record
            </a>
            @if ($isCustodian)
                <a href="{{ route('pcv.pcf.replenish') }}"
                    class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900">
                    Replenish Petty Cash Fund
                </a>
            @endif

        </div>
        @php
            $motorpool_head = App\Models\Office::where('name', 'Motorpool')->first();
        @endphp

        <div class="space-y-1" x-data="{ open: false }">
            <button x-on:click="open=!open" type="button"
                class="flex items-center w-full py-2 pr-2 text-sm font-medium text-left rounded-md text-primary-600 group hover:bg-primary-50 hover:text-primary-900 focus:ring-primary-500 focus:outline-none focus:ring-2"
                aria-controls="sub-menu-1" aria-expanded="false">
                <!-- Expanded: "text-primary-400 rotate-90", Collapsed: "text-primary-300" -->
                <svg :class="open ?
                    'rotate-90 flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400' :
                    'flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400'"
                    class="" viewBox="0 0 20 20" aria-hidden="true">
                    <path d="M6 6L14 10L6 14V6Z" fill="currentColor" />
                </svg>
                Motorpool
            </button>
            <!-- Expandable link section, show/hide based on state. -->
            <div class="space-y-1" id="sub-menu-1" x-show='open'
                x-transition:enter='transition ease-out duration-300' x-transition:enter-start='opacity-0 scale-95'
                x-transition:enter-end='opacity-100 scale-100' x-transition:leave='transition ease-in duration-300'
                x-transition:leave-start='opacity-100 scale-100' x-transition:leave-end='opacity-0 scale-95'
                class="origin-top-left">
                @if ($motorpool_head?->head_id == auth()->user()->id)
                    <a href="{{ route('motorpool.vehicle.index') }}"
                        class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900">
                        Vehicles
                    </a>
                    <a href="{{ route('motorpool.request.index') }}"
                        class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900">
                        Requests
                    </a>
                @endif
                <a href="{{ route('motorpool.weekly-schedule') }}"
                    class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900">
                    Schedules
                </a>
            </div>
        </div>

    </div>
</nav>
