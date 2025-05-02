<div class="space-y-2">
    <div class="flex justify-between items-center">
        <h2 class="font-light capitalize text-primary-600">Select WFP</h2>
    </div>
    <div x-data="{ tab: 'wfp' }" x-cloak>
        <div class="mt-2 inline-flex flex-row">
            <button class="mt-2 flex items-center gap-2 rounded-t-lg px-4 py-2 text-lg font-semibold hover:bg-primary-300" @click="tab = 'wfp'" :class="tab == 'wfp' && 'bg-white -mt-2 text-primary-600'">
                WFP
            </button>
            <button class="mt-2 rounded-t-lg px-4 py-2 text-lg font-semibold hover:bg-primary-300" @click="tab = 'q1'" :class="tab == 'q1' && 'bg-white -mt-2 text-primary-600'">
                Supplemental Q1
            </button>
            {{-- <button class="mt-2 rounded-t-lg px-4 py-2 text-lg font-semibold hover:bg-primary-300" @click="tab = 'q1'" :class="tab == 'q1' && 'bg-white -mt-2 text-primary-600'">
                Supplemental Q2
            </button>
            <button class="mt-2 rounded-t-lg px-4 py-2 text-lg font-semibold hover:bg-primary-300" @click="tab = 'q1'" :class="tab == 'q1' && 'bg-white -mt-2 text-primary-600'">
                Supplemental Q3
            </button>
            <button class="mt-2 rounded-t-lg px-4 py-2 text-lg font-semibold hover:bg-primary-300" @click="tab = 'q1'" :class="tab == 'q1' && 'bg-white -mt-2 text-primary-600'">
                Supplemental Q4
            </button> --}}
        </div>
        <div class="origin-top-left bg-white p-4" x-show="tab === 'wfp'" :class="tab == 'wfp' && 'rounded-b-lg rounded-r-lg'" x-transition:enter='transform ease-out duration-200' x-transition:enter-start='scale-0' x-transition:enter-end='scale-100'>
            <div x-show="tab === 'wfp'" x-transition:enter='transition fade-in duration-700' x-transition:enter-start='opacity-0' x-transition:enter-end='opacity-100'>
                <div>
                    @if ($wfp_type > 0)
                    <div x-data="{ selectedTab: '101' }">

                        <div class="sm:hidden">
                            <label for="tabs" class="sr-only">Select a tab</label>
                            <select id="tabs" name="tabs" class="block w-full rounded-md border-gray-300 focus:border-green-500 focus:ring-green-500"
                                x-model="selectedTab">
                                <option>101</option>
                                <option>161</option>
                                <option>163</option>
                                <option>164T / FHE</option>
                                <option>164T / Non-FHE</option>
                                <option>164OSF</option>
                                <option>164MF</option>
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
                                       'bg-green-500 text-white': selectedTab === '101',
                                       'text-gray-800 hover:text-green-700': selectedTab !== '101'
                                   }"
                                   @click.prevent="selectedTab = '101'">
                                   Fund {{$fund->where('id', 1)->first()->name}}
                                </a>

                                <a wire:click="filter(2)" href="#"
                                   class="rounded-md px-3 py-2 text-sm font-medium"
                                   :class="{
                                       'bg-green-500 text-white': selectedTab === '161',
                                       'text-gray-800 hover:text-green-700': selectedTab !== '161'
                                   }"
                                   @click.prevent="selectedTab = '161'">
                                   Fund {{$fund->where('id', 2)->first()->name}}
                                </a>

                                <a wire:click="filter(3)" href="#"
                                   class="rounded-md px-3 py-2 text-sm font-medium"
                                   :class="{
                                       'bg-green-500 text-white': selectedTab === '163',
                                       'text-gray-800 hover:text-green-700': selectedTab !== '163'
                                   }"
                                   @click.prevent="selectedTab = '163'">
                                   Fund {{$fund->where('id', 3)->first()->name}}
                                </a>

                                <a wire:click="filter(4)" href="#"
                                   class="rounded-md px-3 py-2 text-sm font-medium"
                                   :class="{
                                       'bg-green-500 text-white': selectedTab === '164T / FHE',
                                       'text-gray-800 hover:text-green-700': selectedTab !== '164T / FHE'
                                   }"
                                   @click.prevent="selectedTab = '164T / FHE'">
                                   Fund {{$fund->where('id', 4)->first()->name}}
                                </a>

                                <a wire:click="filter(7)" href="#"
                                class="rounded-md px-3 py-2 text-sm font-medium"
                                :class="{
                                    'bg-green-500 text-white': selectedTab === '164T / Non-FHE',
                                    'text-gray-800 hover:text-green-700': selectedTab !== '164T / Non-FHE'
                                }"
                                @click.prevent="selectedTab = '164T / Non-FHE'">
                                Fund {{$fund->where('id', 7)->first()->name}}
                                </a>


                                <a wire:click="filter(5)" href="#"
                                   class="rounded-md px-3 py-2 text-sm font-medium"
                                   :class="{
                                       'bg-green-500 text-white': selectedTab === '164OSF',
                                       'text-gray-800 hover:text-green-700': selectedTab !== '164OSF'
                                   }"
                                   @click.prevent="selectedTab = '164OSF'">
                                   Fund {{$fund->where('id', 5)->first()->name}}
                                </a>
                                <a wire:click="filter(6)" href="#"
                                   class="rounded-md px-3 py-2 text-sm font-medium"
                                   :class="{
                                       'bg-green-500 text-white': selectedTab === '164MF',
                                       'text-gray-800 hover:text-green-700': selectedTab !== '164MF'
                                   }"
                                   @click.prevent="selectedTab = '164MF'">
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
                <livewire:w-f-p.select-wfp-type-q1 />
            </div>
        </div>

    </div>
</div>


{{-- <div>
    <div class="flex">
        <h2 class="font-light capitalize text-primary-600">Select WFP</h2>
    </div>
    <div class="flex mt-20 min-h-screen">
        <div>
            @if ($types->count() <= 0)
              <div class="text-center">
                <h1 class="text-2xl font-bold text-gray-700">You have no fund allocations yet.</h1>
              </div>
            @else

            <div class="flex space-x-5">
                <div class="grid grid-cols-4 space-x-4">
                    @foreach ($types as $item)
                    <a href="{{ route('wfp.create-wfp', $item->costCenters->first()->id) }}" class="col-span-1 my-3 block max-w-sm p-6 bg-green-800 border border-green-700 rounded-lg shadow-lg hover:bg-green-700 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-50"> Fund {{$item->name}}</h5>
                        <p class="font-normal text-gray-50 dark:text-gray-400">{{$wfp->description}}</p>
                        @if ($item->fundAllocations->first()->fund_cluster_w_f_p_s_id === 1 || $item->fundAllocations->first()->fund_cluster_w_f_p_s_id === 3)
                        <p class="font-normal text-gray-50 dark:text-gray-400">Amount: ₱ {{number_format($item->fundAllocations->where('cost_center_id',  $cost_center_id)->sum('initial_amount'), 2)}}</p>
                        @else
                        <p class="font-normal text-gray-50 dark:text-gray-400">Amount: ₱ {{number_format($item->fundAllocations->first()->initial_amount, 2)}}</p>
                        @endif
                    </a>
                    </a>

                    @endforeach
                </div>

              </div>

            @endif
          </div>
    </div>
  </div> --}}
