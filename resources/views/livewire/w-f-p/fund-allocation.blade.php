  <div class="bg-white rounded-b-lg rounded-tr-lg">
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
                  <nav class="flex   p-3 flex-row gap-3" aria-label="Tabs">
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
                  </nav>
              </div>
          </div>
          <div class="p-4">
              {{ $this->table }}
          </div>
      @else
          <div class="flex justify-center items-center h-64">
              <h2 class="font-light text-gray-500">-- No WFP Period Added --</h2>
          </div>
      @endif
  </div>
