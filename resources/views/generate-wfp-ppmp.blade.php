  @php
      $quarters = DB::table('supplemental_quarters')->get();
  @endphp
  <x-app-layout>
      <div x-data class="space-y-2">
          <div class="flex justify-between items-center">
              <h2 class="font-light capitalize text-primary-600">Generate PPMP</h2>

          </div>
          <div>
              <div class="mt-2 inline-flex flex-row">
                  <a href="{{ request()->fullUrlWithQuery(['supplementalQuarterId' => null, 'campusId' => null]) }}"
                      @class([
                          'mt-2 flex items-center gap-2 rounded-t-lg px-4 py-2 text-lg font-semibold hover:bg-primary-300',
                          'bg-white -mt-2 text-primary-600' => !request()->has(
                              'supplementalQuarterId'),
                      ])>
                      WFP
                  </a>
                  @foreach ($quarters as $item)
                      <a wire:key='{{ $item->id }}'
                          href="{{ request()->fullUrlWithQuery(['supplementalQuarterId' => $item->id, 'campusId' => null]) }}"
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
                              <a href="{{ request()->fullUrlWithQuery(['fundClusterWfpId' => $fund->id, 'sksuLabel' => $fund->name, 'campusId' => null]) }}"
                                  @class([
                                      'rounded-md px-3 py-2 text-sm text-start font-medium w-auto',
                                      'bg-green-500 text-white' =>
                                          request()->input('fundClusterWfpId') == $fund->id,
                                      'text-gray-800 hover:text-green-700' =>
                                          request()->input('fundClusterWfpId') != $fund->id,
                                  ])>
                                  Fund {{ $fund->name }}
                              </a>
                          @endforeach
                      </nav>
                  </div>
                  @if (request()->input('fundClusterWfpId') == 2)
                      <div class="p-4">
                          <div class="flex justify-center">
                              <a href="{{ request()->fullUrlWithQuery(['title' => 'Sultan Kudarat State University']) }}"
                                  class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-8 rounded-lg">
                                  SKSU {{ request()->input('sksuLabel') }}
                              </a>
                          </div>
                          @php
                              $campuses = App\Models\Campus::where('campus_code', '!=', 'ADMN')->get();
                          @endphp
                          <div class="grid grid-cols-7 space-x-4 mt-3">
                              @foreach ($campuses as $item)
                                  <a href="{{ request()->fullUrlWithQuery(['title' => 'Sultan Kudarat State University', 'campusId' => $item->id]) }}"
                                      class="bg-green-800 hover:bg-green-700 text-center text-white font-bold py-1.5 px-3 rounded-lg uppercase">
                                      {{ $item->name }}
                                  </a>
                              @endforeach
                          </div>
                      </div>
                  @else
                      <div class="p-4">
                          <div class="flex justify-center">
                              <a href="{{ request()->fullUrlWithQuery(['title' => 'Sultan Kudarat State University']) }}"
                                  class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-8 rounded-lg">
                                  SKSU {{ request()->input('sksuLabel') }}
                              </a>
                          </div>
                          <div class="flex justify-center space-x-4 mt-3">
                              <a href="{{ request()->fullUrlWithQuery(['mfoId' => 1, 'title' => 'General Admission and Support Services (GASS)']) }}"
                                  class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg">
                                  General Admission and Support Services (GASS)
                              </a>
                              <a href="{{ request()->fullUrlWithQuery(['mfoId' => 2, 'title' => 'Higher Education Services (HES)']) }}"
                                  class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg">
                                  Higher Education Services (HES)
                              </a>
                              <a href="{{ request()->fullUrlWithQuery(['mfoId' => 3, 'title' => 'Advanced Education Services (AES)']) }}"
                                  class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg">
                                  Advanced Education Services (AES)
                              </a>
                              <a href="{{ request()->fullUrlWithQuery(['mfoId' => 4, 'title' => 'Research and Development (RD)']) }}"
                                  class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg">
                                  Research and Development (RD)
                              </a>
                              <a href="{{ request()->fullUrlWithQuery(['mfoId' => 5, 'title' => 'Extension Services (ES)']) }}"
                                  class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg">
                                  Extension Services (ES)
                              </a>
                              <a href="{{ request()->fullUrlWithQuery(['mfoId' => 6, 'title' => 'Local Fund Projects (LFP)']) }}"
                                  class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg">
                                  Local Fund Projects (LFP)
                              </a>
                          </div>
                      </div>
                  @endif
                  @livewire('w-f-p.generate-wfp-ppmp')
              </div>
          </div>
          <script>
              function printOut(data) {
                  var mywindow = window.open('', '', 'height=1000,width=1000');
                  mywindow.document.write('<html><head>');
                  mywindow.document.write('<title></title>');
                  mywindow.document.write(`<link rel="stylesheet" href="{{ Vite::asset('resources/css/app.css') }}" />`);
                  mywindow.document.write('</head><body >');
                  mywindow.document.write(data);
                  mywindow.document.write('</body></html>');

                  mywindow.document.close();
                  mywindow.focus();
                  setTimeout(() => {
                      mywindow.print();
                      return true;
                  }, 1000);
              }

              function printDiv(divName) {
                  var printContents = document.getElementById(divName).innerHTML;
                  var originalContents = document.body.innerHTML;
                  document.body.innerHTML = printContents;
                  window.print();
                  document.body.innerHTML = originalContents;

              }
          </script>
      </div>
  </x-app-layout>
