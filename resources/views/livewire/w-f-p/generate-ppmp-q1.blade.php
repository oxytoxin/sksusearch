<div>
    {{-- @if ($wfp_type > 0) --}}
    <div x-cloak x-data="{ selectedTab: '101', showPrintable: false }">

        <!-- Dropdown for small screens -->
        <div class="sm:hidden">
            <label for="tabs" class="sr-only">Select a tab</label>
            <select id="tabs" name="tabs"
                class="block w-full rounded-md border-gray-300 focus:border-green-500 focus:ring-green-500"
                x-model="selectedTab" @change="showPrintable = false">
                <option>101</option>
                <option>163</option>
                <option>161</option>
                <option>164T / FHE</option>
                <option>164T / Non-FHE</option>
                <option>164OSF</option>
                <option>164MF</option>
            </select>
        </div>

        @php
            $fund = App\Models\FundClusterWFP::where('position', '!=', 0)->orderBy('position', 'asc')->get();
        @endphp

        <!-- Tabs for larger screens -->
        <div class="hidden sm:block">
            <nav wire:click="resetPrintable" class="flex space-x-4" aria-label="Tabs">
                @foreach ($fund as $fund)
                    <button class="rounded-md px-3 py-2 text-sm text-start font-medium w-auto"
                        :class="{
                            'bg-green-500 text-white': selectedTab === '{{ $fund->name }}',
                            'text-gray-800 hover:text-green-700': selectedTab !== '{{ $fund->name }}'
                        }"
                        @click.prevent="selectedTab = '{{ $fund->name }}'; showPrintable = false">
                        Fund {{ $fund->name }}
                    </button>
                @endforeach
                {{-- <a href="#" class="rounded-md px-3 py-2 text-sm font-medium"
                    :class="{
                        'bg-green-500 text-white': selectedTab === '101',
                        'text-gray-800 hover:text-green-700': selectedTab !== '101'
                    }"
                    @click.prevent="selectedTab = '101'; showPrintable = false">
                    Fund {{ $fund->where('id', 1)->first()->name }}
                </a>

                <a href="#" class="rounded-md px-3 py-2 text-sm font-medium"
                    :class="{
                        'bg-green-500 text-white': selectedTab === '163',
                        'text-gray-800 hover:text-green-700': selectedTab !== '163'
                    }"
                    @click.prevent="selectedTab = '163'; showPrintable = false">
                    Fund {{ $fund->where('id', 2)->first()->name }}
                </a>

                <a href="#" class="rounded-md px-3 py-2 text-sm font-medium"
                    :class="{
                        'bg-green-500 text-white': selectedTab === '161',
                        'text-gray-800 hover:text-green-700': selectedTab !== '161'
                    }"
                    @click.prevent="selectedTab = '161'; showPrintable = false">
                    Fund {{ $fund->where('id', 3)->first()->name }}
                </a>

                <a href="#" class="rounded-md px-3 py-2 text-sm font-medium"
                    :class="{
                        'bg-green-500 text-white': selectedTab === '164T / FHE',
                        'text-gray-800 hover:text-green-700': selectedTab !== '164T / FHE'
                    }"
                    @click.prevent="selectedTab = '164T / FHE'; showPrintable = false">
                    Fund {{ $fund->where('id', 4)->first()->name }}
                </a>

                <a href="#" class="rounded-md px-3 py-2 text-sm font-medium"
                    :class="{
                        'bg-green-500 text-white': selectedTab === '164T / Non-FHE',
                        'text-gray-800 hover:text-green-700': selectedTab !== '164T / Non-FHE'
                    }"
                    @click.prevent="selectedTab = '164T / Non-FHE'; showPrintable = false">
                    Fund {{ $fund->where('id', 7)->first()->name }}
                </a>

                <a href="#" class="rounded-md px-3 py-2 text-sm font-medium"
                    :class="{
                        'bg-green-500 text-white': selectedTab === '164OSF',
                        'text-gray-800 hover:text-green-700': selectedTab !== '164OSF'
                    }"
                    @click.prevent="selectedTab = '164OSF'; showPrintable = false">
                    Fund {{ $fund->where('id', 5)->first()->name }}
                </a>

                <a href="#" class="rounded-md px-3 py-2 text-sm font-medium"
                    :class="{
                        'bg-green-500 text-white': selectedTab === '164MF',
                        'text-gray-800 hover:text-green-700': selectedTab !== '164MF'
                    }"
                    @click.prevent="selectedTab = '164MF'; showPrintable = false">
                    Fund {{ $fund->where('id', 6)->first()->name }}
                </a> --}}
            </nav>
        </div>
        <div class="mt-5">
            <div class="flex justify-start items-center">
                <select wire:model.defer="selectedType" id="wfp_type" name="wfp_type"
                    class="block w-1/2 rounded-md border-gray-300 focus:border-green-500 focus:ring-green-500">
                    <option value="0" disabled selected>Select WFP Type</option>
                    @foreach ($wfp_types as $type)
                        <option value="{{ $type->id }}">{{ $type->description }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Content for each tab -->
        <div x-cloak class="mt-4">
            <div wire:key='32131-101' x-show="selectedTab === '101'">
                @include('fund-views.101') <!-- Include the view for tab 101 -->
                <div class="flex justify-center mt-10">
                    <div wire:loading class="loader">
                    </div>
                </div>
            </div>
            <div wire:key='CON-101' x-show="selectedTab === '101 - Continuing'">
                @include('fund-views.101-continuing') <!-- Include the view for tab 101 -->
                <div class="flex justify-center mt-10">
                    <div wire:loading class="loader">
                    </div>
                </div>
            </div>
            <div x-show="selectedTab === '161'">
                @include('fund-views.161') <!-- Include the view for tab 161 -->
                <div class="flex justify-center mt-10">
                    <div wire:loading class="loader">
                    </div>
                </div>
            </div>
            <div x-show="selectedTab === '163'">
                @include('fund-views.163') <!-- Include the view for tab 163 -->
                <div class="flex justify-center mt-10">
                    <div wire:loading class="loader">
                    </div>
                </div>
            </div>
            <div x-show="selectedTab === '164 FHE / Tuition Fees'">
                @include('fund-views.164T') <!-- Include the view for tab 164T -->
                <div class="flex justify-center mt-10">
                    <div wire:loading class="loader">
                    </div>
                </div>
            </div>
            <div x-show="selectedTab === '164 Non-FHE / Tuition Fees'">
                @include('fund-views.164TNonFHE') <!-- Include the view for tab 164T -->
                <div class="flex justify-center mt-10">
                    <div wire:loading class="loader">
                    </div>
                </div>
            </div>
            <div x-show="selectedTab === '164 FHE / Fiduciary - Other School Fees'">
                @include('fund-views.164OSF') <!-- Include the view for tab 164OSF -->
                <div class="flex justify-center mt-10">
                    <div wire:loading class="loader">
                    </div>
                </div>
            </div>
            <div x-show="selectedTab === '164 Non-FHE / Fiduciary - Miscellaneous Fees'">
                @include('fund-views.164MF') <!-- Include the view for tab 164MF -->
                <div class="flex justify-center mt-10">
                    <div wire:loading class="loader">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-4">
        {{-- {{ $this->table }} --}}
    </div>
    {{-- @else
<div class="flex justify-center items-center h-64">
<h2 class="font-light text-gray-500">-- No WFP Period Added --</h2>
</div>
@endif --}}
</div>
