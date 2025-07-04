<div>
    <div>
        @if ($wfp_type > 0)
            <div
                @if ($fund_cluster) x-data="{ selectedTab: '{{ $fund_cluster }}' }"
                            @else
                            x-data="{ selectedTab: '1' }" @endif>

                <div class="sm:hidden">
                    <label for="tabs" class="sr-only">Select a tab</label>
                    <select id="tabs" name="tabs"
                        class="block w-full rounded-md border-gray-300 focus:border-green-500 focus:ring-green-500"
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
                    $fundClusterWfps = App\Models\FundClusterWFP::where('position', '!=', 0)
                        ->orderBy('position', 'asc')
                        ->get();
                @endphp
                <div class="hidden sm:block">
                    <nav class="flex space-x-4" aria-label="Tabs">
                        @foreach ($fundClusterWfps as $fund)
                            <button wire:click="filter({{ $fund->id }})"
                                class="rounded-md px-3 py-2 text-sm text-start font-medium w-auto"
                                :class="{
                                    'bg-green-500 text-white': selectedTab === '{{ $fund->id }}',
                                    'text-gray-800 hover:text-green-700': selectedTab !== '{{ $fund->id }}'
                                }"
                                @click.prevent="selectedTab = '{{ $fund->id }}'">
                                Fund {{ $fund->name }}
                            </button>
                        @endforeach
                        {{-- <a wire:click="filter(1)" href="#"
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
</div>
