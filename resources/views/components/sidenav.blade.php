<nav class="flex flex-col space-y-1 px-2 md:mt-5" x-data x-cloak>

    <div class="space-y-1" x-data="{ open: false }">

        <!-- Current: "bg-primary-100 text-primary-900", Default: "bg-white text-primary-600 hover:bg-primary-50 hover:text-primary-900" -->
        <button class="group flex w-full items-center rounded-md py-2 pr-2 text-left text-sm font-medium text-primary-600 hover:bg-primary-50 hover:text-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500" type="button" aria-controls="sub-menu-1" aria-expanded="false" x-on:click="open=!open">
            <!-- Expanded: "text-primary-400 rotate-90", Collapsed: "text-primary-300" -->
            <svg class="" aria-hidden="true" :class="open ?
                'rotate-90 flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400' :
                'flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400'" viewBox="0 0 20 20">
                <path d="M6 6L14 10L6 14V6Z" fill="currentColor" />
            </svg>
            Disbursement Vouchers
            @if ($dv_to_sign > 0)
                <span class="mx-auto inline-flex h-2 w-2 items-center justify-center rounded-full bg-primary-100 p-3 text-xs font-medium text-primary-600">
                    {{ $dv_to_sign }}
                </span>
            @endif
        </button>
        <!-- Expandable link section, show/hide based on state. -->
        <div class="space-y-1" class="origin-top-left" id="sub-menu-1" x-show='open' x-transition:enter='transition ease-out duration-300' x-transition:enter-start='opacity-0 scale-95' x-transition:enter-end='opacity-100 scale-100' x-transition:leave='transition ease-in duration-300' x-transition:leave-start='opacity-100 scale-100' x-transition:leave-end='opacity-0 scale-95'>
            {{-- drafts --}}
            <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="#">
                Drafts
                {{-- <span
                class="inline-flex items-center justify-center w-2 h-2 p-3 mx-auto text-xs font-medium rounded-full text-primary-600 bg-primary-100">3</span> --}}
            </a>

            {{-- pending dv's --}}
            <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="{{ route('requisitioner.disbursement-vouchers.index') }}">
                Submitted
                {{-- <span
                class="inline-flex items-center justify-center w-2 h-2 p-3 mx-auto text-xs font-medium rounded-full text-primary-600 bg-primary-100">3</span> --}}
            </a>

            {{-- cancelled dv's --}}
            <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="{{ route('requisitioner.disbursement-vouchers.cancelled') }}">
                Cancelled
                {{-- <span
                class="inline-flex items-center justify-center w-2 h-2 p-3 mx-auto text-xs font-medium rounded-full text-primary-600 bg-primary-100">3</span> --}}
            </a>

            {{-- signatory dv's --}}

            <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="{{ route('signatory.disbursement-vouchers.index') }}">
                For Signature
                @if ($dv_to_sign > 0)
                    <span class="mx-auto inline-flex h-2 w-2 items-center justify-center rounded-full bg-primary-100 p-3 text-xs font-medium text-primary-600">
                        {{ $dv_to_sign }}
                    </span>
                @endif
            </a>
        </div>
    </div>

    {{-- unliquidated dv's --}}
    <div class="space-y-1" x-data="{ open: false }">
        <button class="group flex w-full items-center rounded-md py-2 pr-2 text-left text-sm font-medium text-primary-600 hover:bg-primary-50 hover:text-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500" type="button" aria-controls="sub-menu-1" aria-expanded="false" x-on:click="open=!open">
            <!-- Expanded: "text-primary-400 rotate-90", Collapsed: "text-primary-300" -->
            <svg class="" aria-hidden="true" :class="open ?
                'rotate-90 flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400' :
                'flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400'" viewBox="0 0 20 20">
                <path d="M6 6L14 10L6 14V6Z" fill="currentColor" />
            </svg>
            Cash Advances
            @if ($lr_to_sign > 0)
                <span class="mx-auto inline-flex h-2 w-2 items-center justify-center rounded-full bg-primary-100 p-3 text-xs font-medium text-primary-600">
                    {{ $lr_to_sign }}
                </span>
            @endif
        </button>

        <div class="space-y-1" class="origin-top-left" id="sub-menu-1" x-show='open' x-transition:enter='transition ease-out duration-300' x-transition:enter-start='opacity-0 scale-95' x-transition:enter-end='opacity-100 scale-100' x-transition:leave='transition ease-in duration-300' x-transition:leave-start='opacity-100 scale-100' x-transition:leave-end='opacity-0 scale-95'>
            <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="{{ route('requisitioner.disbursement-vouchers.unliquidated') }}" href="#">
                Unliquidated
            </a>
            @php
                $is_president = auth()->user()->employee_information->office_id == 51 && auth()->user()->employee_information->position_id == 34;
                $is_accountant = auth()->user()->employee_information->office_id == 3 && auth()->user()->employee_information->position_id == 15;
                $is_auditor = auth()->user()->employee_information->office_id == 61 && auth()->user()->employee_information->position_id == 31;
            @endphp
            @if ($is_president || $is_accountant || $is_auditor)
                <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="{{ route('requisitioner.ca-reminders') }}" href="#">
                    Notices
                </a>
            @endif

        </div>
    </div>

    {{-- liquidation reports --}}
    <div class="space-y-1" x-data="{ open: false }">
        <button class="group flex w-full items-center rounded-md py-2 pr-2 text-left text-sm font-medium text-primary-600 hover:bg-primary-50 hover:text-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500" type="button" aria-controls="sub-menu-1" aria-expanded="false" x-on:click="open=!open">
            <!-- Expanded: "text-primary-400 rotate-90", Collapsed: "text-primary-300" -->
            <svg class="" aria-hidden="true" :class="open ?
                'rotate-90 flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400' :
                'flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400'" viewBox="0 0 20 20">
                <path d="M6 6L14 10L6 14V6Z" fill="currentColor" />
            </svg>
            Liquidation Reports
            @if ($lr_to_sign > 0)
                <span class="mx-auto inline-flex h-2 w-2 items-center justify-center rounded-full bg-primary-100 p-3 text-xs font-medium text-primary-600">
                    {{ $lr_to_sign }}
                </span>
            @endif
        </button>

        {{-- for liquidation reports --}}

        <div class="space-y-1" class="origin-top-left" id="sub-menu-1" x-show='open' x-transition:enter='transition ease-out duration-300' x-transition:enter-start='opacity-0 scale-95' x-transition:enter-end='opacity-100 scale-100' x-transition:leave='transition ease-in duration-300' x-transition:leave-start='opacity-100 scale-100' x-transition:leave-end='opacity-0 scale-95'>

            <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="#">
                Drafts
            </a>

            <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="{{ route('requisitioner.liquidation-reports.index') }}">
                Submitted
            </a>

            <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="{{ route('requisitioner.liquidation-reports.cancelled') }}">
                Cancelled
            </a>

            <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="{{ route('signatory.liquidation-reports.index') }}">
                For Signature
                @if ($lr_to_sign > 0)
                    <span class="mx-auto inline-flex h-2 w-2 items-center justify-center rounded-full bg-primary-100 p-3 text-xs font-medium text-primary-600">
                        {{ $lr_to_sign }}
                    </span>
                @endif
            </a>
        </div>
    </div>

    <div class="space-y-1" x-data="{ open: false }">
        <button class="group flex w-full items-center rounded-md py-2 pr-2 text-left text-sm font-medium text-primary-600 hover:bg-primary-50 hover:text-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500" type="button" aria-controls="sub-menu-1" aria-expanded="false" x-on:click="open=!open">
            <!-- Expanded: "text-primary-400 rotate-90", Collapsed: "text-primary-300" -->
            <svg class="" aria-hidden="true" :class="open ?
                'rotate-90 flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400' :
                'flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400'" viewBox="0 0 20 20">
                <path d="M6 6L14 10L6 14V6Z" fill="currentColor" />
            </svg>
            Travel Orders
            @if ($to_to_sign > 0)
                <span class="mx-auto inline-flex h-2 w-2 items-center justify-center rounded-full bg-primary-100 p-3 text-xs font-medium text-primary-600">
                    {{ $to_to_sign }}
                </span>
            @endif
        </button>
        <!-- Expandable link section, show/hide based on state. -->
        <div class="space-y-1" class="origin-top-left" id="sub-menu-1" x-show='open' x-transition:enter='transition ease-out duration-300' x-transition:enter-start='opacity-0 scale-95' x-transition:enter-end='opacity-100 scale-100' x-transition:leave='transition ease-in duration-300' x-transition:leave-start='opacity-100 scale-100' x-transition:leave-end='opacity-0 scale-95'>

            {{-- drafts --}}
            <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="#">
                Drafts
            </a>

            {{-- pending travel orders --}}
            <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="{{ route('requisitioner.travel-orders.index') }}">
                Submitted
            </a>

            <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="{{ route('signatory.travel-orders.index') }}">
                For Signature
                @if ($to_to_sign > 0)
                    <span class="mx-auto inline-flex h-2 w-2 items-center justify-center rounded-full bg-primary-100 p-3 text-xs font-medium text-primary-600">
                        {{ $to_to_sign }}
                    </span>
                @endif
            </a>

            {{-- signed travel orders --}}
            {{-- <a class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900" href="">
            Signed
        </a> --}}
        </div>
    </div>

    <div class="space-y-1" x-data="{ open: false }">
        <button class="group flex w-full items-center rounded-md py-2 pr-2 text-left text-sm font-medium text-primary-600 hover:bg-primary-50 hover:text-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500" type="button" aria-controls="sub-menu-1" aria-expanded="false" x-on:click="open=!open">
            <!-- Expanded: "text-primary-400 rotate-90", Collapsed: "text-primary-300" -->
            <svg class="" aria-hidden="true" :class="open ?
                'rotate-90 flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400' :
                'flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400'" viewBox="0 0 20 20">
                <path d="M6 6L14 10L6 14V6Z" fill="currentColor" />
            </svg>
            Petty Cash Vouchers
        </button>
        @php
            $isCustodian = auth()->user()->petty_cash_fund()->exists();
            $isAccountant = auth()->user()->employee_information->position_id == 15 && auth()->user()->employee_information->office_id == 3;
        @endphp
        <!-- Expandable link section, show/hide based on state. -->
        <div class="space-y-1" class="origin-top-left" id="sub-menu-1" x-show='open' x-transition:enter='transition ease-out duration-300' x-transition:enter-start='opacity-0 scale-95' x-transition:enter-end='opacity-100 scale-100' x-transition:leave='transition ease-in duration-300' x-transition:leave-start='opacity-100 scale-100' x-transition:leave-end='opacity-0 scale-95'>
            @if ($isAccountant)
                <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="{{ route('pcv.accountant.dashboard') }}">
                    Accountant's Dashboard
                </a>
            @endif
            @if ($isCustodian)
                <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="{{ route('pcv.index') }}">
                    Petty Cash Vouchers
                </a>
            @endif
            <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="{{ route('pcv.rppcv') }}">
                Report on Paid Petty Cash Vouchers
            </a>
            <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="{{ route('pcv.pcf.record') }}">
                Petty Cash Fund Record
            </a>
            @if ($isCustodian)
                <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="{{ route('pcv.pcf.replenish') }}">
                    Replenish Petty Cash Fund
                </a>
            @endif

        </div>


        <div class="space-y-1" x-data="{ open: false }">
            <button class="group flex w-full items-center rounded-md py-2 pr-2 text-left text-sm font-medium text-primary-600 hover:bg-primary-50 hover:text-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500" type="button" aria-controls="sub-menu-1" aria-expanded="false" x-on:click="open=!open">
                <!-- Expanded: "text-primary-400 rotate-90", Collapsed: "text-primary-300" -->
                <svg class="" aria-hidden="true" :class="open ?
                    'rotate-90 flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400' :
                    'flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400'" viewBox="0 0 20 20">
                    <path d="M6 6L14 10L6 14V6Z" fill="currentColor" />
                </svg>
                Motorpool
            </button>
            @php
                $is_motorpool_head = auth()->user()->employee_information->office_id == 32 && auth()->user()->employee_information->position_id == 12;
            @endphp
            <!-- Expandable link section, show/hide based on state. -->
            <div class="space-y-1" class="origin-top-left" id="sub-menu-1" x-show='open' x-transition:enter='transition ease-out duration-300' x-transition:enter-start='opacity-0 scale-95' x-transition:enter-end='opacity-100 scale-100' x-transition:leave='transition ease-in duration-300' x-transition:leave-start='opacity-100 scale-100' x-transition:leave-end='opacity-0 scale-95'>
                <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="{{ route('requisitioner.motorpool.index') }}">
                    Request Vehicle
                </a>
                @if ($is_motorpool_head)
                    {{-- <a class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900"
                   href="{{ route('motorpool.vehicle.index') }}">
                    Vehicles
                </a> --}}
                    <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="{{ route('motorpool.request.index') }}">
                        Vehicle Requests
                    </a>

                @endif

                <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="{{ route('motorpool.view-schedule') }}">
                    Vehicle Schedules
                </a>
                @if ($is_motorpool_head)
                <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="{{ route('motorpool.request.fuel-requisition') }}">
                    Fuel Slips
                </a>
                @endif
                @if (auth()->user()->id == 64)
                    <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="{{ route('signatory.motorpool.for-signature') }}">
                        For Signature
                    </a>
                    <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="{{ route('signatory.motorpool.signed') }}">
                        Signed
                    </a>
                @endif
            </div>
        </div>
        <div class="space-y-1" x-data="{ open: false }">
            <button class="group flex w-full items-center rounded-md py-2 pr-2 text-left text-sm font-medium text-primary-600 hover:bg-primary-50 hover:text-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500" type="button" aria-controls="sub-menu-1" aria-expanded="false" x-on:click="open=!open">
                <!-- Expanded: "text-primary-400 rotate-90", Collapsed: "text-primary-300" -->
                <svg class="" aria-hidden="true" :class="open ?
                    'rotate-90 flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400' :
                    'flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400'" viewBox="0 0 20 20">
                    <path d="M6 6L14 10L6 14V6Z" fill="currentColor" />
                </svg>
                Work & Financial Plan
            </button>
            @php
                $is_motorpool_head = auth()->user()->employee_information->office_id == 32 && auth()->user()->employee_information->position_id == 12;
                $isOfficeHead = auth()->user()->employee_information->office?->head_employee?->id == auth()->user()->employee_information->id;
                $headOfficeId = auth()->user()->employee_information->office?->id;
                $costCenterExist = DB::table('cost_centers')->where('office_id', $headOfficeId)->exists();
                $isAssignedPersonnel = DB::table('wpf_personnels')
                    ->where('user_id', auth()->user()->id)
                    ->exists();
                $isSupplyChief = auth()->user()->employee_information->office_id == 49 && auth()->user()->employee_information->position_id == 15;
                $isSupply = auth()->user()->employee_information->office_id == 49;
                $isFinance = auth()->user()->employee_information->office_id == 25 && (auth()->user()->employee_information->position_id == 12 || auth()->user()->employee_information->position_id == 38);
                $isPresident = auth()->user()->employee_information->office_id == 51 && auth()->user()->employee_information->position_id == 34;
                $is_reizza = auth()->user()->id == 49;
                $is_nolaila = auth()->user()->id == 467;
            @endphp
            <!-- Expandable link section, show/hide based on state. -->
            <div class="space-y-1" class="origin-top-left" id="sub-menu-1" x-show='open' x-transition:enter='transition ease-out duration-300' x-transition:enter-start='opacity-0 scale-95' x-transition:enter-end='opacity-100 scale-100' x-transition:leave='transition ease-in duration-300' x-transition:leave-start='opacity-100 scale-100' x-transition:leave-end='opacity-0 scale-95'>
                <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="{{ route('wfp.pricelist-document') }}">
                    Pricelist Document
                </a>
                @if ($isFinance)
                    <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="{{ route('wfp.wfp-types') }}">
                        WFP Period
                    </a>
                @endif
                @if ($isFinance || $isPresident)
                    <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="{{ route('wfp.fund-allocation', 1) }}">
                        Fund Allocation
                    </a>
                @endif
                @if ($isFinance || $is_reizza || $is_nolaila)
                    <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="{{ route('wfp.wfp-submissions', 1) }}">
                        WFP Submissions
                    </a>
                @endif
                @if ($isFinance)
                    <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="{{ route('wfp.generate-wfp-ppmp') }}">
                        Generate PPMP
                    </a>
                    <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="{{ route('wfp.generate-ppmp') }}">
                        Generate PRE
                    </a>

                    <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="{{ route('wfp.reported-supply-list') }}">
                        Reported Supplies
                    </a>
                @endif

                @if ($isAccountant)
                    <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="{{ route('wfp.accounting-requested-suppluies') }}">
                        Requested Supplies
                    </a>
                @endif
                @if ($isOfficeHead && $costCenterExist)
                    <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="{{ route('wfp.assign-personnel') }}">
                        Assign Personnel
                    </a>
                @endif
                @if (($isOfficeHead && $costCenterExist) || $isAssignedPersonnel)
                    <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="{{ route('wfp.select-wfp') }}">
                        Create WFP
                    </a>
                    <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="{{ route('wfp.request-supply-list') }}">
                        Request Supply
                    </a>
                    <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="{{ route('wfp.report-supply-list') }}">
                        Report Supply
                    </a>
                    <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="{{ route('wfp.wfp-history') }}">
                        WFP History
                    </a>
                @endif
                @if ($isSupply)
                    <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="{{ route('wfp.supply-requested-suppluies') }}">
                        Requested Supplies
                    </a>
                @endif

                {{-- @if (auth()->user()->id == 64)
                <a class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900" href="{{ route('signatory.motorpool.for-signature') }}">
                    For Signature
                </a>
                <a class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900" href="{{ route('signatory.motorpool.signed') }}">
                    Signed
                </a>
            @endif --}}
            </div>
        </div>
        <!-- Current: "bg-primary-100 text-primary-900", Default: "bg-white text-primary-600 hover:bg-primary-50 hover:text-primary-900" -->
        <a class="group flex w-full items-center rounded-md py-2 pr-2 text-left text-sm font-medium text-primary-600 hover:bg-primary-50 hover:text-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500" href="{{ config('app.v2_url') . '/activity-designs' }}" aria-controls="sub-menu-1" aria-expanded="false">
            <!-- Expanded: "text-primary-400 rotate-90", Collapsed: "text-primary-300" -->
            <svg class="" aria-hidden="true" :class="open ?
                'rotate-90 flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400' :
                'flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400'" viewBox="0 0 20 20">
                <path d="M6 6L14 10L6 14V6Z" fill="currentColor" />
            </svg>
            Activity Design
            {{-- @if ($dv_to_sign > 0)
                <span class="mx-auto inline-flex h-2 w-2 items-center justify-center rounded-full bg-primary-100 p-3 text-xs font-medium text-primary-600">
                    {{ $dv_to_sign }}
                </span>
            @endif --}}
        </a>
        @if (in_array(auth()->user()->employee_information->position_id, [24, 12, 15, 38]))
            <div class="space-y-1" x-data="{ open: false }">
                <button class="group flex w-full items-center rounded-md py-2 pr-2 text-left text-sm font-medium text-primary-600 hover:bg-primary-50 hover:text-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500" type="button" aria-controls="sub-menu-1" aria-expanded="false" x-on:click="open=!open">
                    <!-- Expanded: "text-primary-400 rotate-90", Collapsed: "text-primary-300" -->
                    <svg class="" aria-hidden="true" :class="open ?
                        'rotate-90 flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400' :
                        'flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400'" viewBox="0 0 20 20">
                        <path d="M6 6L14 10L6 14V6Z" fill="currentColor" />
                    </svg>
                    Archives
                </button>
                @php
                    $isAccountant = auth()->user()->employee_information->position_id == 15 && auth()->user()->employee_information->office_id == 3;
                @endphp
                <!-- Expandable link section, show/hide based on state. -->
                <div class="space-y-1" class="origin-top-left" id="sub-menu-1" x-show='open' x-transition:enter='transition ease-out duration-300' x-transition:enter-start='opacity-0 scale-95' x-transition:enter-end='opacity-100 scale-100' x-transition:leave='transition ease-in duration-300' x-transition:leave-start='opacity-100 scale-100' x-transition:leave-end='opacity-0 scale-95'>
                    @if (in_array(auth()->user()->employee_information->position_id, [24]) || auth()->user()->id == 19 || $isAccountant)
                        <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="{{ route('archiver.view-archives') }}">
                            Archived Documents
                        </a>
                        <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="{{ route('archiver.archive-doc.create') }}">
                            Upload Documents
                        </a>
                        <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="{{ route('archiver.archive-leg-doc.create') }}">
                            Upload Legacy Documents
                        </a>
                        <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="{{ route('archiver.archive-cheques.create') }}">
                            Upload Stale / Cancelled Cheques
                        </a>
                        <a class="group flex w-full items-center rounded-md py-2 pl-10 pr-2 text-sm font-medium text-primary-600 hover:bg-primary-100 hover:text-primary-900" href="{{ route('requisitioner.travel-orders.index') }}">
                            Assign Documents
                        </a>
                    @endif
                </div>
            </div>
        @endif

    </div>
</nav>
