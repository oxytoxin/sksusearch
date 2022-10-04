<nav x-data x-cloak class="flex flex-col px-2 space-y-1 md:mt-5">

    {{-- <x-sidenav-link href="{{ route('requisitioner.dashboard') }}" :active="request()->routeIs('requisitioner.dashboard')">
        My Dashboard
    </x-sidenav-link>
    <x-sidenav-link href="{{ route('office.dashboard') }}" :active="request()->routeIs('office.dashboard')">
        Office Dashboard
    </x-sidenav-link> --}}
    <div class="space-y-1" x-data="{ open: false }">
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
        </button>
        <!-- Expandable link section, show/hide based on state. -->
        <div class="space-y-1" id="sub-menu-1" x-show='open' x-transition:enter='transition ease-out duration-300'
            x-transition:enter-start='opacity-0 scale-95' x-transition:enter-end='opacity-100 scale-100'
            x-transition:leave='transition ease-in duration-300' x-transition:leave-start='opacity-100 scale-100'
            x-transition:leave-end='opacity-0 scale-95' class="origin-top-left">
            <a href="{{ route('requisitioner.disbursement-vouchers.index') }}"
                class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900">
                Pending Disbursement Vouchers</a>
            <a href="#"
                class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900">
                Unliquidated Disbursement Vouchers</a>
            <a href="#"
                class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900">
                Closed Disbursement Vouchers</a>
            @if (in_array(auth()->user()->employee_information->position_id, [
                5,
                12,
                13,
                11,
                14,
                15,
                16,
                17,
                18,
                19,
                20,
                21,
                25,
            ]))
                <a href="{{ route('signatory.disbursement-vouchers.index') }}"
                    class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900">
                    Signatory Disbursement Vouchers
                </a>
            @endif
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
            <a href="{{ route('requisitioner.travel-orders.index') }}"
                class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900">
                My Travel Orders
            </a>
            @if (in_array(auth()->user()->employee_information->position_id, [
                5,
                12,
                13,
                11,
                14,
                15,
                16,
                17,
                18,
                19,
                20,
                21,
                25,
            ]))
                <a href="{{ route('signatory.travel-orders.index') }}"
                    class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900">
                    Signatory Travel orders
                </a>
            @endif

        </div>
    </div>
    @if (in_array(auth()->user()->employee_information->position_id, [24, 12]))
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
            <div class="space-y-1" id="sub-menu-1" x-show='open' x-transition:enter='transition ease-out duration-300'
                x-transition:enter-start='opacity-0 scale-95' x-transition:enter-end='opacity-100 scale-100'
                x-transition:leave='transition ease-in duration-300' x-transition:leave-start='opacity-100 scale-100'
                x-transition:leave-end='opacity-0 scale-95' class="origin-top-left">
                <a href="{{ route('archiver.view-archives') }}"
                    class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900">
                    Archived Documents
                </a>
                @if (in_array(auth()->user()->employee_information->position_id, [24]))
                    <a href="{{ route('archiver.archive-doc-create') }}"
                        class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900">
                        Upload Documents
                    </a>
                    {{-- <a href="{{ route('requisitioner.travel-orders.index') }}" --}}
                    <a href=""
                        class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900">
                        Assign Documents
                    </a>
                @endif
            </div>
        </div>
    @endif
</nav>
