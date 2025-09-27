<div>
    @if ($wfp_type > 0)
        <div x-data="{ selectedTab: '101' }">
            <div class="sm:hidden">
                <label for="tabs" class="sr-only">Select a tab</label>
                <select id="tabs" name="tabs"
                        class="block w-full rounded-md border-gray-300 focus:border-green-500 focus:ring-green-500"
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
                $funds = App\Models\FundCluster::where('position', '!=', 0)->orderBy('position', 'asc')->get();
            @endphp
            <div class="hidden sm:block">
                <nav class="flex space-x-4" aria-label="Tabs">
                    @foreach ($funds as $fund)
                        <a wire:click="filter({{ $fund->id }})" href="#"
                           class="rounded-md px-3 py-2 text-sm font-medium"
                           :class="{
                                   'bg-green-500 text-white': selectedTab === '{{ $fund->name }}',
                                   'text-gray-800 hover:text-green-700': selectedTab !== '{{ $fund->name }}'
                               }"
                           @click.prevent="selectedTab = '{{ $fund->name }}'">
                            Fund {{ $fund->name }}
                        </a>
                    @endforeach
                    {{--
                                 <a wire:click="filter(2)" href="#"
                                     class="rounded-md px-3 py-2 text-sm font-medium"
                                     :class="{
                                         'bg-green-500 text-white': selectedTab === '161',
                                         'text-gray-800 hover:text-green-700': selectedTab !== '161'
                                     }"
                                     @click.prevent="selectedTab = '161'">
                                     Fund {{ $fund->where('id', 2)->first()->name }}
                                 </a>

                                 <a wire:click="filter(3)" href="#"
                                     class="rounded-md px-3 py-2 text-sm font-medium"
                                     :class="{
                                         'bg-green-500 text-white': selectedTab === '163',
                                         'text-gray-800 hover:text-green-700': selectedTab !== '163'
                                     }"
                                     @click.prevent="selectedTab = '163'">
                                     Fund {{ $fund->where('id', 3)->first()->name }}
                                 </a>

                                 <a wire:click="filter(4)" href="#"
                                     class="rounded-md px-3 py-2 text-sm font-medium"
                                     :class="{
                                         'bg-green-500 text-white': selectedTab === '164T / FHE',
                                         'text-gray-800 hover:text-green-700': selectedTab !== '164T / FHE'
                                     }"
                                     @click.prevent="selectedTab = '164T / FHE'">
                                     Fund {{ $fund->where('id', 4)->first()->name }}
                                 </a>

                                 <a wire:click="filter(7)" href="#"
                                     class="rounded-md px-3 py-2 text-sm font-medium"
                                     :class="{
                                         'bg-green-500 text-white': selectedTab === '164T / Non-FHE',
                                         'text-gray-800 hover:text-green-700': selectedTab !== '164T / Non-FHE'
                                     }"
                                     @click.prevent="selectedTab = '164T / Non-FHE'">
                                     Fund {{ $fund->where('id', 7)->first()->name }}
                                 </a>


                                 <a wire:click="filter(5)" href="#"
                                     class="rounded-md px-3 py-2 text-sm font-medium"
                                     :class="{
                                         'bg-green-500 text-white': selectedTab === '164OSF',
                                         'text-gray-800 hover:text-green-700': selectedTab !== '164OSF'
                                     }"
                                     @click.prevent="selectedTab = '164OSF'">
                                     Fund {{ $fund->where('id', 5)->first()->name }}
                                 </a>
                                 <a wire:click="filter(6)" href="#"
                                     class="rounded-md px-3 py-2 text-sm font-medium"
                                     :class="{
                                         'bg-green-500 text-white': selectedTab === '164MF',
                                         'text-gray-800 hover:text-green-700': selectedTab !== '164MF'
                                     }"
                                     @click.prevent="selectedTab = '164MF'">
                                     Fund {{ $fund->where('id', 6)->first()->name }}
                                 </a> --}}
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
