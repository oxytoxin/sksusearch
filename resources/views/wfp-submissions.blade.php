  @php
      $quarters = DB::table('supplemental_quarters')->get();
  @endphp
  <x-app-layout>
      <div class="space-y-2">
          <div class="flex justify-between items-center">
              <h2 class="font-light capitalize text-primary-600">WFP Submissions</h2>
              <a href="{{ route('wfp.deactivated-pricelists') }}"
                  class="hover:bg-primary-500 p-2 bg-primary-600 rounded-md font-light capitalize text-white text-sm">View
                  Deactivated Pricelists</a>
          </div>
          <div x-data="{ tab: 'wfp' }" x-cloak>
              <div class="mt-2 inline-flex flex-row">
                  <a href="/wfp/wfp-submissions/1" @class([
                      'mt-2 flex items-center gap-2 rounded-t-lg px-4 py-2 text-lg font-semibold hover:bg-primary-300',
                      'bg-white -mt-2 text-primary-600' => !request()->has(
                          'supplementalQuaterId'),
                  ])>
                      WFP
                  </a>
                  @foreach ($quarters as $item)
                      @if (config('features.VERSION') === '10.0.0.1')
                          <a wire:key='{{ $item->id }}'
                              href="/wfp/wfp-submissions/1?supplementalQuaterId={{ $item->id }}"
                              @class([
                                  'mt-2 rounded-t-lg px-4 py-2 text-lg font-semibold hover:bg-primary-300 ',
                                  'bg-white -mt-2 text-primary-600' =>
                                      $item->id == request()->input('supplementalQuaterId'),
                              ])>
                              {{ $item->name }}
                          </a>
                      @else
                          @if ($loop->first)
                              <a wire:key='{{ $item->id }}'
                                  href="/wfp/wfp-submissions/1?supplementalQuaterId={{ $item->id }}"
                                  @class([
                                      'mt-2 rounded-t-lg px-4 py-2 text-lg font-semibold hover:bg-primary-300 ',
                                      'bg-white -mt-2 text-primary-600' =>
                                          $item->id == request()->input('supplementalQuaterId'),
                                  ])>
                                  {{ $item->name }}
                              </a>
                          @endif
                      @endif
                  @endforeach

              </div>
              @if (!request()->has('supplementalQuaterId'))
                  <div class="origin-top-left bg-white p-4 rounded-b-lg rounded-r-lg">
                      @livewire('w-f-p.wfp-submissions', ['filter' => request()->input('filter')])
                  </div>
              @else
                  <div class="origin-[10%_0] bg-white p-4 rounded-b-lg rounded-r-lg">
                      @livewire('w-f-p.wfp-submissions-q1')
                  </div>
              @endif
          </div>
      </div>
  </x-app-layout>
