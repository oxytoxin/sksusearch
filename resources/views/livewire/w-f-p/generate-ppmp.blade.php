<div class="space-y-2">
    <div class="flex justify-between items-center">
        <h2 class="font-light capitalize text-primary-600">Generate PPMP</h2>
        {{-- <a href="{{ route('requisitioner.motorpool.create') }}"
            class="hover:bg-primary-500 p-2 bg-primary-600 rounded-md font-light capitalize text-white text-sm">New
            Request</a> --}}
    </div>
    <div>
        {{-- @if ($wfp_type > 0) --}}
        <div x-data="{ selectedTab: '101' }">

            <!-- Dropdown for small screens -->
            <div class="sm:hidden">
                <label for="tabs" class="sr-only">Select a tab</label>
                <select id="tabs" name="tabs" class="block w-full rounded-md border-gray-300 focus:border-green-500 focus:ring-green-500"
                        x-model="selectedTab">
                    <option>101</option>
                    <option>161</option>
                    <option>163</option>
                    <option>164T</option>
                    <option>164OSF</option>
                    <option>164MF</option>
                </select>
            </div>

            @php
                $fund = App\Models\FundClusterWFP::get();
            @endphp

            <!-- Tabs for larger screens -->
            <div class="hidden sm:block">
                <nav wire:click="resetPrintable" class="flex space-x-4" aria-label="Tabs">
                    <a href="#" class="rounded-md px-3 py-2 text-sm font-medium"
                       :class="{
                           'bg-green-500 text-white': selectedTab === '101',
                           'text-gray-800 hover:text-green-700': selectedTab !== '101'
                       }"
                       @click.prevent="selectedTab = '101'">
                       Fund {{$fund->where('id', 1)->first()->name}}
                    </a>

                    <a href="#" class="rounded-md px-3 py-2 text-sm font-medium"
                       :class="{
                           'bg-green-500 text-white': selectedTab === '161',
                           'text-gray-800 hover:text-green-700': selectedTab !== '161'
                       }"
                       @click.prevent="selectedTab = '161'">
                       Fund {{$fund->where('id', 2)->first()->name}}
                    </a>

                    <a href="#" class="rounded-md px-3 py-2 text-sm font-medium"
                       :class="{
                           'bg-green-500 text-white': selectedTab === '163',
                           'text-gray-800 hover:text-green-700': selectedTab !== '163'
                       }"
                       @click.prevent="selectedTab = '163'">
                       Fund {{$fund->where('id', 3)->first()->name}}
                    </a>

                    <a href="#" class="rounded-md px-3 py-2 text-sm font-medium"
                       :class="{
                           'bg-green-500 text-white': selectedTab === '164T',
                           'text-gray-800 hover:text-green-700': selectedTab !== '164T'
                       }"
                       @click.prevent="selectedTab = '164T'">
                       Fund {{$fund->where('id', 4)->first()->name}}
                    </a>

                    <a href="#" class="rounded-md px-3 py-2 text-sm font-medium"
                       :class="{
                           'bg-green-500 text-white': selectedTab === '164OSF',
                           'text-gray-800 hover:text-green-700': selectedTab !== '164OSF'
                       }"
                       @click.prevent="selectedTab = '164OSF'">
                       Fund {{$fund->where('id', 5)->first()->name}}
                    </a>

                    <a href="#" class="rounded-md px-3 py-2 text-sm font-medium"
                       :class="{
                           'bg-green-500 text-white': selectedTab === '164MF',
                           'text-gray-800 hover:text-green-700': selectedTab !== '164MF'
                       }"
                       @click.prevent="selectedTab = '164MF'">
                       Fund {{$fund->where('id', 6)->first()->name}}
                    </a>
                </nav>
            </div>

            <!-- Content for each tab -->
            <div x-cloak class="mt-4">
                <div x-show="selectedTab === '101'">
                    @include('fund-views.101') <!-- Include the view for tab 101 -->
                </div>
                <div x-show="selectedTab === '161'">
                    @include('fund-views.161') <!-- Include the view for tab 161 -->
                </div>
                <div x-show="selectedTab === '163'">
                    @include('fund-views.163') <!-- Include the view for tab 163 -->
                </div>
                <div x-show="selectedTab === '164T'">
                    @include('fund-views.164T') <!-- Include the view for tab 164T -->
                </div>
                <div x-show="selectedTab === '164OSF'">
                    @include('fund-views.164OSF') <!-- Include the view for tab 164OSF -->
                </div>
                <div x-show="selectedTab === '164MF'">
                    @include('fund-views.164MF') <!-- Include the view for tab 164MF -->
                </div>
            </div>
        </div>
        <div class="mt-4">
            {{-- {{ $this->table }} --}}
        </div>
        {{-- @else
        <div class="flex justify-center items-center h-64">
            <h2 class="font-light text-gray-500">-- No WFP Type Added --</h2>
        </div>
        @endif --}}
    </div>
</div>
