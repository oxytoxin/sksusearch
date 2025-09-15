  @php
      $quarters = DB::table('supplemental_quarters')->get();
  @endphp
  <x-app-layout>
      <div class="space-y-2">
          <div class="flex justify-between items-center">
              <h2 class="font-light capitalize text-primary-600">Generate PPMP</h2>
          </div>
          <div>
              <div class="mt-2 inline-flex flex-row">
                  <a href="{{ request()->fullUrlWithQuery(['supplementalQuarterId' => null]) }}"
                      @class([
                          'mt-2 flex items-center gap-2 rounded-t-lg px-4 py-2 text-lg font-semibold hover:bg-primary-300',
                          'bg-white -mt-2 text-primary-600' => !request()->has(
                              'supplementalQuarterId'),
                      ])>
                      WFP
                  </a>
                  @foreach ($quarters as $item)
                      <a wire:key='{{ $item->id }}'
                          href="{{ request()->fullUrlWithQuery(['supplementalQuarterId' => $item->id]) }}"
                          @class([
                              'mt-2 rounded-t-lg px-4 py-2 text-lg font-semibold hover:bg-primary-300 ',
                              'bg-white -mt-2 text-primary-600' =>
                                  $item->id == request()->input('supplementalQuarterId'),
                          ])>
                          {{ $item->name }}
                      </a>
                  @endforeach
              </div>
              @php
                  $fundClusterWfps = App\Models\FundClusterWFP::where('position', '!=', 0)
                      ->orderBy('position', 'asc')
                      ->get();
              @endphp
              <div class="origin-top-left bg-white p-4 rounded-b-lg rounded-r-lg">
                  <div class="hidden sm:block">
                      <nav class="flex space-x-4" aria-label="Tabs">
                          @foreach ($fundClusterWfps as $fund)
                              <a href="{{ request()->fullUrlWithQuery(['fundClusterWfpId' => $fund->id]) }}"
                                  @class([
                                      'rounded-md px-3 py-2 text-sm text-start font-medium w-auto',
                                      'bg-green-500 text-white' =>
                                          request()->input('fundClusterWfpId') == $fund->id,
                                      'text-gray-800 hover:text-green-700' =>
                                          request()->input('fundClusterWfpId') != $fund->id,
                                  ]) @click.prevent="selectedTab = '{{ $fund->id }}'">
                                  Fund {{ $fund->name }}
                                  {{ $fund->id }}
                              </a>
                          @endforeach
                      </nav>
                  </div>
              </div>
          </div>
      </div>
  </x-app-layout>
