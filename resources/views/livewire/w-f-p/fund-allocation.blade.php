{{-- <div class="space-y-2">
        <div class="flex justify-between items-center">
            <h2 class="font-light capitalize text-primary-600">Fund Allocation</h2>
        </div>

        <div x-data="{ tab: 'wfp' }" x-cloak>
        <div class="mt-2 inline-flex flex-row">
            <button class="mt-2 flex items-center gap-2 rounded-t-lg px-4 py-2 text-lg font-semibold hover:bg-primary-300" @click="tab = 'wfp'" :class="tab == 'wfp' && 'bg-white -mt-2 text-primary-600'">
                WFP
            </button>
            <button class="mt-2 rounded-t-lg px-4 py-2 text-lg font-semibold hover:bg-primary-300" @click="tab = 'q1'" :class="tab == 'q1' && 'bg-white -mt-2 text-primary-600'">
                Supplemental Q1
            </button>
        </div>
        <div class="origin-top-left bg-white p-4" x-show="tab === 'wfp'" :class="tab == 'wfp' && 'rounded-b-lg rounded-r-lg'" x-transition:enter='transform ease-out duration-200' x-transition:enter-start='scale-0' x-transition:enter-end='scale-100'>
            <div x-show="tab === 'wfp'" x-transition:enter='transition fade-in duration-700' x-transition:enter-start='opacity-0' x-transition:enter-end='opacity-100'>
            <div>
            @if ($wfp_type > 0)
            <div
            @if($fund_cluster)
            x-data="{ selectedTab: '{{ $fund_cluster }}' }"
            @else
             x-data="{ selectedTab: '1' }"
            @endif
            >

                <div class="sm:hidden">
                    <label for="tabs" class="sr-only">Select a tab</label>
                    <select id="tabs" name="tabs" class="block w-full rounded-md border-gray-300 focus:border-green-500 focus:ring-green-500"
                        x-model="selectedTab">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                        <option>6</option>
                        <option>7</option>
                    </select>
                </div>
                @php
                    $fund = App\Models\FundClusterWFP::get();
                @endphp
                <div class="hidden sm:block">
                    <nav class="flex space-x-4" aria-label="Tabs">
                        <a wire:click="filter(1)" href="#"
                           class="rounded-md px-3 py-2 text-sm font-medium"
                           :class="{
                               'bg-green-500 text-white': selectedTab === '1',
                               'text-gray-800 hover:text-green-700': selectedTab !== '1'
                           }"
                           @click.prevent="selectedTab = '1'">
                           Fund {{$fund->where('id', 1)->first()->name}}
                        </a>

                        <a wire:click="filter(2)" href="#"
                           class="rounded-md px-3 py-2 text-sm font-medium"
                           :class="{
                               'bg-green-500 text-white': selectedTab === '2',
                               'text-gray-800 hover:text-green-700': selectedTab !== '2'
                           }"
                           @click.prevent="selectedTab = '2'">
                           Fund {{$fund->where('id', 2)->first()->name}}
                        </a>

                        <a wire:click="filter(3)" href="#"
                           class="rounded-md px-3 py-2 text-sm font-medium"
                           :class="{
                               'bg-green-500 text-white': selectedTab === '3',
                               'text-gray-800 hover:text-green-700': selectedTab !== '3'
                           }"
                           @click.prevent="selectedTab = '3'">
                           Fund {{$fund->where('id', 3)->first()->name}}
                        </a>

                        <a wire:click="filter(4)" href="#"
                           class="rounded-md px-3 py-2 text-sm font-medium"
                           :class="{
                               'bg-green-500 text-white': selectedTab === '4',
                               'text-gray-800 hover:text-green-700': selectedTab !== '4'
                           }"
                           @click.prevent="selectedTab = '4'">
                           Fund {{$fund->where('id', 4)->first()->name}}
                        </a>

                        <a wire:click="filter(7)" href="#"
                        class="rounded-md px-3 py-2 text-sm font-medium"
                        :class="{
                            'bg-green-500 text-white': selectedTab === '7',
                            'text-gray-800 hover:text-green-700': selectedTab !== '7'
                        }"
                        @click.prevent="selectedTab = '7'">
                        Fund {{$fund->where('id', 7)->first()->name}}
                        </a>

                        <a wire:click="filter(5)" href="#"
                           class="rounded-md px-3 py-2 text-sm font-medium"
                           :class="{
                               'bg-green-500 text-white': selectedTab === '5',
                               'text-gray-800 hover:text-green-700': selectedTab !== '5'
                           }"
                           @click.prevent="selectedTab = '5'">
                           Fund {{$fund->where('id', 5)->first()->name}}
                        </a>
                        <a wire:click="filter(6)" href="#"
                           class="rounded-md px-3 py-2 text-sm font-medium"
                           :class="{
                               'bg-green-500 text-white': selectedTab === '6',
                               'text-gray-800 hover:text-green-700': selectedTab !== '6'
                           }"
                           @click.prevent="selectedTab = '6'">
                           Fund {{$fund->where('id', 6)->first()->name}}
                        </a>
                    </nav>
                </div>
            </div>
            <div class="mt-4">
                {{ $this->table }}
            </div>
            @else
            <div class="flex justify-center items-center h-64">
                <h2 class="font-light text-gray-500">-- No WFP Period Added --</h2>
            </div>
            @endif
            </div>
        </div>
        </div>

        <div class="origin-[10%_0] bg-white p-4" x-show="tab === 'q1'" :class="tab == 'q1' && 'rounded-b-lg rounded-r-lg'" x-transition:enter='transform ease-out duration-200' x-transition:enter-start='scale-0' x-transition:enter-end='scale-100'>
            <div x-show="tab === 'q1'" x-transition:enter='transition fade-in duration-700' x-transition:enter-start='opacity-0' x-transition:enter-end='opacity-100'>
                <livewire:w-f-p.fund-allocation-q1 />
            </div>
        </div>
    </div>
</div> --}}

<div class="space-y-2">
    <div class="flex justify-between items-center">
            <h2 class="font-light capitalize text-primary-600">Fund Allocation</h2>
        </div>
 <div>
            @if ($wfp_type > 0)
            <div
            @if($fund_cluster)
            x-data="{ selectedTab: '{{ $fund_cluster }}' }"
            @else
             x-data="{ selectedTab: '1' }"
            @endif
            >

                <div class="sm:hidden">
                    <label for="tabs" class="sr-only">Select a tab</label>
                    <select id="tabs" name="tabs" class="block w-full rounded-md border-gray-300 focus:border-green-500 focus:ring-green-500"
                        x-model="selectedTab">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                        <option>6</option>
                        <option>7</option>
                    </select>
                </div>
                @php
                    $fund = App\Models\FundClusterWFP::get();
                @endphp
                <div class="hidden sm:block">
                    <nav class="flex space-x-4" aria-label="Tabs">
                        <a wire:click="filter(1)" href="#"
                           class="rounded-md px-3 py-2 text-sm font-medium"
                           :class="{
                               'bg-green-500 text-white': selectedTab === '1',
                               'text-gray-800 hover:text-green-700': selectedTab !== '1'
                           }"
                           @click.prevent="selectedTab = '1'">
                           Fund {{$fund->where('id', 1)->first()->name}}
                        </a>

                        <a wire:click="filter(2)" href="#"
                           class="rounded-md px-3 py-2 text-sm font-medium"
                           :class="{
                               'bg-green-500 text-white': selectedTab === '2',
                               'text-gray-800 hover:text-green-700': selectedTab !== '2'
                           }"
                           @click.prevent="selectedTab = '2'">
                           Fund {{$fund->where('id', 2)->first()->name}}
                        </a>

                        <a wire:click="filter(3)" href="#"
                           class="rounded-md px-3 py-2 text-sm font-medium"
                           :class="{
                               'bg-green-500 text-white': selectedTab === '3',
                               'text-gray-800 hover:text-green-700': selectedTab !== '3'
                           }"
                           @click.prevent="selectedTab = '3'">
                           Fund {{$fund->where('id', 3)->first()->name}}
                        </a>

                        <a wire:click="filter(4)" href="#"
                           class="rounded-md px-3 py-2 text-sm font-medium"
                           :class="{
                               'bg-green-500 text-white': selectedTab === '4',
                               'text-gray-800 hover:text-green-700': selectedTab !== '4'
                           }"
                           @click.prevent="selectedTab = '4'">
                           Fund {{$fund->where('id', 4)->first()->name}}
                        </a>

                        <a wire:click="filter(7)" href="#"
                        class="rounded-md px-3 py-2 text-sm font-medium"
                        :class="{
                            'bg-green-500 text-white': selectedTab === '7',
                            'text-gray-800 hover:text-green-700': selectedTab !== '7'
                        }"
                        @click.prevent="selectedTab = '7'">
                        Fund {{$fund->where('id', 7)->first()->name}}
                        </a>

                        <a wire:click="filter(5)" href="#"
                           class="rounded-md px-3 py-2 text-sm font-medium"
                           :class="{
                               'bg-green-500 text-white': selectedTab === '5',
                               'text-gray-800 hover:text-green-700': selectedTab !== '5'
                           }"
                           @click.prevent="selectedTab = '5'">
                           Fund {{$fund->where('id', 5)->first()->name}}
                        </a>
                        <a wire:click="filter(6)" href="#"
                           class="rounded-md px-3 py-2 text-sm font-medium"
                           :class="{
                               'bg-green-500 text-white': selectedTab === '6',
                               'text-gray-800 hover:text-green-700': selectedTab !== '6'
                           }"
                           @click.prevent="selectedTab = '6'">
                           Fund {{$fund->where('id', 6)->first()->name}}
                        </a>
                    </nav>
                </div>
            </div>
            <div class="mt-4">
                {{ $this->table }}
            </div>
            @else
            <div class="flex justify-center items-center h-64">
                <h2 class="font-light text-gray-500">-- No WFP Period Added --</h2>
            </div>
            @endif
            </div>
</div>
