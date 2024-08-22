    <div class="space-y-2">
        <div class="flex justify-between items-center">
            <h2 class="font-light capitalize text-primary-600">Fund Allocation</h2>
            {{-- <a href="{{ route('requisitioner.motorpool.create') }}"
                class="hover:bg-primary-500 p-2 bg-primary-600 rounded-md font-light capitalize text-white text-sm">New
                Request</a> --}}
        </div>
        <div>
            @if ($wfp_type > 0)
            <div x-data="{ selectedTab: 'My Account' }">

                <div class="sm:hidden">
                    <label for="tabs" class="sr-only">Select a tab</label>
                    <select id="tabs" name="tabs" class="block w-full rounded-md border-gray-300 focus:border-green-500 focus:ring-green-500"
                        x-model="selectedTab">
                        <option>My Account</option>
                        <option>Company</option>
                        <option>Team Members</option>
                        <option>Billing</option>
                        <option>Billings</option>
                    </select>
                </div>

                <div class="hidden sm:block">
                    <nav class="flex space-x-4" aria-label="Tabs">
                        <a wire:click="filter(1)" href="#"
                           class="rounded-md px-3 py-2 text-sm font-medium"
                           :class="{
                               'bg-green-500 text-white': selectedTab === 'My Account',
                               'text-gray-800 hover:text-green-700': selectedTab !== 'My Account'
                           }"
                           @click.prevent="selectedTab = 'My Account'">
                           Fund 101
                        </a>

                        <a wire:click="filter(2)" href="#"
                           class="rounded-md px-3 py-2 text-sm font-medium"
                           :class="{
                               'bg-green-500 text-white': selectedTab === 'Company',
                               'text-gray-800 hover:text-green-700': selectedTab !== 'Company'
                           }"
                           @click.prevent="selectedTab = 'Company'">
                           Fund 161
                        </a>

                        <a wire:click="filter(3)" href="#"
                           class="rounded-md px-3 py-2 text-sm font-medium"
                           :class="{
                               'bg-green-500 text-white': selectedTab === 'Team Members',
                               'text-gray-800 hover:text-green-700': selectedTab !== 'Team Members'
                           }"
                           @click.prevent="selectedTab = 'Team Members'">
                           Fund 163
                        </a>

                        <a wire:click="filter(4)" href="#"
                           class="rounded-md px-3 py-2 text-sm font-medium"
                           :class="{
                               'bg-green-500 text-white': selectedTab === 'Billing',
                               'text-gray-800 hover:text-green-700': selectedTab !== 'Billing'
                           }"
                           @click.prevent="selectedTab = 'Billing'">
                           Fund 164T
                        </a>

                        <a wire:click="filter(5)" href="#"
                           class="rounded-md px-3 py-2 text-sm font-medium"
                           :class="{
                               'bg-green-500 text-white': selectedTab === 'Billings',
                               'text-gray-800 hover:text-green-700': selectedTab !== 'Billings'
                           }"
                           @click.prevent="selectedTab = 'Billings'">
                           Fund 164F
                        </a>
                    </nav>
                </div>
            </div>
            <div class="mt-4">
                {{ $this->table }}
            </div>
            @else
            <div class="flex justify-center items-center h-64">
                <h2 class="font-light text-gray-500">-- No WFP Type Added --</h2>
            </div>
            @endif
        </div>
    </div>
