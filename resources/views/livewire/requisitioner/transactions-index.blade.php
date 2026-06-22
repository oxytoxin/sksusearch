<div x-data="{
    search: '',
    openTypes: {},
    autoOpened: {},
    toggle(id) {
        this.openTypes[id] = !this.openTypes[id];
        if (this.autoOpened[id]) delete this.autoOpened[id];
    },
    isOpen(id) { return !!this.openTypes[id] },
    autoOpen(id) {
        if (!this.openTypes[id]) {
            this.openTypes[id] = true;
            this.autoOpened[id] = true;
        }
    },
    matches(text) {
        if (!this.search.trim()) return true;
        return text.toLowerCase().includes(this.search.trim().toLowerCase());
    },
    typeVisible(typeName, subtypeNames) {
        if (!this.search.trim()) return true;
        const q = this.search.trim().toLowerCase();
        if (typeName.toLowerCase().includes(q)) return true;
        return subtypeNames.some(n => n.toLowerCase().includes(q));
    },
    hasResults: true,
    updateResults() {
        this.$nextTick(() => {
            this.hasResults = !!this.$root.querySelector('[data-voucher-card]:not([style*=\'display: none\'])')
        })
    },
    init() {
        this.$watch('search', (val) => {
            if (!val.trim()) {
                Object.keys(this.autoOpened).forEach(id => {
                    delete this.openTypes[id];
                });
                this.autoOpened = {};
            }
        });
    }
}">
    {{-- Sticky Header: Action Buttons + Search --}}
    <div class="sticky top-16 z-10 pt-1 pb-4 -mx-4 px-4 sm:-mx-6 sm:px-6 md:-mx-8 md:px-8 bg-gradient-to-b from-primary-100 via-primary-100 to-primary-100/95 backdrop-blur-sm">

    {{-- Quick Action Buttons --}}
    <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
        <a href="{{ route('requisitioner.travel-orders.create') }}"
           class="flex items-center gap-3 p-4 bg-white border border-gray-200 border-l-4 border-l-primary-500 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 group">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-primary-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-sm font-medium text-gray-700 group-hover:text-primary-700">Create Travel Order</span>
        </a>
        <a href="{{ route('requisitioner.itinerary.create') }}"
           class="flex items-center gap-3 p-4 bg-white border border-gray-200 border-l-4 border-l-primary-500 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 group">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-primary-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-sm font-medium text-gray-700 group-hover:text-primary-700">Create Itinerary</span>
        </a>
        <a href="{{ route('requisitioner.liquidation-reports.create') }}"
           class="flex items-center gap-3 p-4 bg-white border border-gray-200 border-l-4 border-l-primary-500 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 group">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-primary-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-sm font-medium text-gray-700 group-hover:text-primary-700">Create Liquidation Report</span>
        </a>
        <a href="#"
           class="flex items-center gap-3 p-4 bg-white border border-gray-200 border-l-4 border-l-primary-500 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 group">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-primary-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-sm font-medium text-gray-700 group-hover:text-primary-700">Create Communication</span>
        </a>
    </div>

    {{-- Search Bar --}}
    <div class="relative mt-6">
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
            </svg>
        </div>
        <input x-model.debounce.150ms="search" x-on:input="updateResults()" type="text"
               placeholder="Search voucher types..."
               class="block w-full py-2.5 pl-10 pr-10 text-sm placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-primary-500 focus:ring-1 focus:ring-primary-500" />
        <button x-show="search.length > 0" x-on:click="search = ''; updateResults()" x-cloak
                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
            </svg>
        </button>
    </div>

    </div>{{-- End Sticky Header --}}

    {{-- Section Header --}}
    <div class="mt-6 mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Disbursements</h3>
        <p class="text-xs text-gray-500 mt-0.5">Select a voucher type to create a new disbursement voucher</p>
    </div>

    {{-- Voucher Type Cards --}}
    <div class="space-y-3">
        @foreach ($voucher_types->sortBy('order_column') as $type)
            @php
                $subtypeNames = $type->voucher_subtypes->pluck('name')->map(fn($n) => strtolower(str_replace("'", "\\'", $n)))->toArray();
                $subtypeNamesJs = "['" . implode("','", $subtypeNames) . "']";
                $typeNameLower = strtolower(str_replace("'", "\\'", $type->name));
            @endphp

            @if ($type->voucher_subtypes->count() == 1)
                {{-- Single subtype: direct link --}}
                <a href="{{ route('requisitioner.disbursement-vouchers.create', ['voucher_subtype' => $type->voucher_subtypes->first()->id]) }}"
                   data-voucher-card
                   x-show="typeVisible('{{ $typeNameLower }}', {{ $subtypeNamesJs }})"
                   x-transition:enter="transition ease-out duration-200"
                   x-transition:enter-start="opacity-0 translate-y-1"
                   x-transition:enter-end="opacity-100 translate-y-0"
                   class="block">
                    <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-lg shadow-sm hover:border-primary-400 hover:shadow-md transition-all duration-200 group">
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center justify-center flex-shrink-0 rounded-lg w-9 h-9 bg-primary-100">
                                <svg class="w-5 h-5 text-primary-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M3 3.5A1.5 1.5 0 014.5 2h6.879a1.5 1.5 0 011.06.44l4.122 4.12A1.5 1.5 0 0117 7.622V16.5a1.5 1.5 0 01-1.5 1.5h-11A1.5 1.5 0 013 16.5v-13z" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-primary-700">{{ $type->name }}</span>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 transition-colors group-hover:text-primary-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </a>
            @else
                {{-- Multiple subtypes: expandable card --}}
                <div data-voucher-card
                     x-show="typeVisible('{{ $typeNameLower }}', {{ $subtypeNamesJs }})"
                     x-effect="if (search.trim() && {{ $subtypeNamesJs }}.some(n => n.includes(search.trim().toLowerCase()))) { autoOpen({{ $type->id }}) }"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="bg-white border border-gray-200 rounded-lg shadow-sm transition-all duration-200"
                     :class="isOpen({{ $type->id }}) ? 'border-primary-300 shadow-md' : ''">
                    <button x-on:click="toggle({{ $type->id }})" type="button"
                            class="flex items-center justify-between w-full p-4 text-left rounded-lg focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500">
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center justify-center flex-shrink-0 rounded-lg w-9 h-9 bg-primary-100">
                                <svg class="w-5 h-5 text-primary-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M3.75 3A1.75 1.75 0 002 4.75v3.26a3.235 3.235 0 011.75-.51h12.5c.644 0 1.245.188 1.75.51V6.75A1.75 1.75 0 0016.25 5h-4.836a.25.25 0 01-.177-.073L9.823 3.513A1.75 1.75 0 008.586 3H3.75zM3.75 9A1.75 1.75 0 002 10.75v4.5c0 .966.784 1.75 1.75 1.75h12.5A1.75 1.75 0 0018 15.25v-4.5A1.75 1.75 0 0016.25 9H3.75z" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700">{{ $type->name }}</span>
                            <span class="inline-flex items-center justify-center w-5 h-5 text-xs font-medium rounded-full text-primary-700 bg-primary-100"
                                  x-text="search.trim() ? {{ $subtypeNamesJs }}.filter(n => n.includes(search.trim().toLowerCase())).length || {{ $type->voucher_subtypes->count() }} : {{ $type->voucher_subtypes->count() }}"></span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                             class="w-5 h-5 text-gray-400 transition-transform duration-300"
                             :class="isOpen({{ $type->id }}) ? 'rotate-180' : ''">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div x-show="isOpen({{ $type->id }})" x-collapse x-cloak>
                        <div class="px-4 pb-4 border-t border-gray-100">
                            <div class="mt-2 ml-12 space-y-1">
                                @foreach ($type->voucher_subtypes as $subtype)
                                    @php
                                        $subtypeNameLower = strtolower(str_replace("'", "\\'", $subtype->name));
                                    @endphp
                                    @if ($subtype->id == 69)
                                        @if (auth()->user()->petty_cash_fund()->exists())
                                            <a href="{{ route('requisitioner.disbursement-vouchers.create', ['voucher_subtype' => $subtype->id]) }}"
                                               x-show="!search.trim() || matches('{{ $subtypeNameLower }}') || matches('{{ $typeNameLower }}')"
                                               class="block px-3 py-2 text-sm text-gray-600 rounded-md hover:bg-primary-50 hover:text-primary-700 transition-colors duration-150">
                                                {{ $subtype->name }}
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('requisitioner.disbursement-vouchers.create', ['voucher_subtype' => $subtype->id]) }}"
                                           x-show="!search.trim() || matches('{{ $subtypeNameLower }}') || matches('{{ $typeNameLower }}')"
                                           class="block px-3 py-2 text-sm text-gray-600 rounded-md hover:bg-primary-50 hover:text-primary-700 transition-colors duration-150">
                                            {{ $subtype->name }}
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach

        {{-- No Results Message --}}
        <div x-show="!hasResults && search.trim()" x-cloak
             class="py-8 text-center text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
            </svg>
            <p class="text-sm">No matching voucher types found.</p>
        </div>
    </div>
</div>
