<div x-data class="space-y-2">
    <div class="flex justify-between items-center">
        <h2 class="font-light capitalize text-primary-600">Wfp Report</h2>
        <div class="flex space-x-3">
            <button @click="printOut($refs.printContainer.outerHTML);" type="button" class="flex hover:bg-yellow-500 p-2 bg-yellow-600 rounded-md font-light capitalize text-white text-sm">
               Print
            </button>
            <button onclick="window.history.back()" href="{{ route('wfp.wfp-submissions', $record->fundClusterWFP->id) }}" class="flex hover:bg-gray-500 p-2 bg-gray-600 rounded-md font-light capitalize text-white text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.49 12 3.74 8.248m0 0 3.75-3.75m-3.75 3.75h16.5V19.5" />
                </svg>
                Back
            </button>
        </div>
    </div>
    <div x-ref="printContainer" class="w-full bg-gray-50 px-2 py-4 rounded-md">
        <div class="text-center">
            <p class="text-2xl font-medium">
                Work & Financial Plan (WFP)
            </p>
            <p class="text-md font-normal">{{$record->wfpType->description}}</p>
        </div>
        <div class="flex justify-between">
            <!-- Left Side -->
            <div class="p-6 flex-col text-sm w-1/4 font-medium divide-y-2 divide-gray-800">
                <div class="py-2">
                    <span class="text-left font-semibold">Fund Description:</span>
                    <span class="text-center">{{$record->fund_description}}</span>
                </div>
                <div class="py-2">
                    <span class="text-left font-semibold">Fund Cluster:</span>
                    <span class="text-center">{{$record->fundClusterWfp->name}}</span>
                </div>
                <div class="py-2">
                    <span class="text-left font-semibold">MFO:</span>
                    <span class="text-center">{{$record->costCenter->mfo->name}}</span>
                </div>
                @if ($record->fundClusterWfp->id > 3)
                <div class="py-2">
                    <span class="text-left font-semibold">Source of Fund: </span>
                    <span class="text-center">{{$record->source_fund}}</span>
                </div>
                <div class="py-2">
                    <span class="text-left font-semibold">If miscellaneous/fiduciary fee, please specify: </span>
                    <span class="text-center">{{$record->confirm_fund_source ?? 'N/A'}}</span>
                </div>
                @endif
                <div class="py-2">
                    <span class="text-left font-semibold">Cost Center: </span>
                    <span class="text-center">{{$record->costCenter->name}}</span>
                </div>
                <div class="py-2">
                    <span class="text-left font-semibold">Cost Center Head: </span>
                    <span class="text-center">{{$record->costCenter->office->head_employee?->full_name.' - '.$record->costCenter->office->name}}</span>
                </div>
            </div>

            <!-- Right Side -->
            <div class="p-6 flex-col justify-end text-sm w-1/4 font-medium divide-y-2 divide-gray-800">
                <!-- Add your content for the right side here -->
                <div class="py-2">
                    <span class="text-right font-semibold">Status:</span>
                    @if($record->is_approved === 0)
                    <span class="text-center">Pending</span>
                    @elseif($record->is_approved === 1)
                    <span class="text-center">Approved</span>
                    @else
                    <span class="text-center">For Modification</span>
                    @endif

                </div>

                <div class="py-2">
                    @if($record->is_approved === 1)
                    <span class="text-left font-semibold">Date Approved:</span>
                    <span class="text-center">{{Carbon\Carbon::parse($record->updated_at)->format('F d, Y h:i A')}}</span>
                    @endif
                </div>

                <!-- Add more fields as needed -->
            </div>
        </div>


        <div class="mt-2 flow-root">
            <div class="my-2 overflow-x-auto sm:-mx-6 lg:-mx-2">
              <div class=" min-w-full py-2 align-middle sm:px-6 lg:px-2">
                <table class="min-w-full">
                  <thead class="bg-gray-400">
                      <tr class="border-t border-gray-200">
                        <th colspan="22" scope="colgroup" class="bg-green-700 py-2 pl-4 pr-3 text-left text-sm font-semibold text-gray-50 sm:pl-3 h-10"></th>
                      </tr>
                  </thead>
                  <thead class="bg-white">
                    <tr>
                        <th scope="col" class="px-1 py-1 text-left text-sm font-semibold text-gray-900">UACS Code</th>
                        <th scope="col" class="px-1 py-1 text-left text-sm font-semibold text-gray-900">Account Title</th>
                        <th scope="col" class="px-1 py-1 text-left text-sm font-semibold text-gray-900">Particulars</th>
                        <th scope="col" class="px-1 py-1 text-left text-sm font-semibold text-gray-900">Supply Code</th>
                        <th scope="col" class="px-1 py-1 text-left text-sm font-semibold text-gray-900">Qty</th>
                        <th scope="col" class="px-1 py-1 text-left text-sm font-semibold text-gray-900">UOM</th>
                        <th scope="col" class="px-1 py-1 text-right text-sm font-semibold text-gray-900">Unit Cost (₱)</th>
                        <th scope="col" class="px-1 py-1 text-right text-sm font-semibold text-gray-900">Estimated Budget (₱)</th>
                        <th scope="col" class="px-1 py-1 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">Jan</th>
                        <th scope="col" class="px-1 py-1 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">Feb</th>
                        <th scope="col" class="px-1 py-1 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">Mar</th>
                        <th scope="col" class="px-1 py-1 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">Apr</th>
                        <th scope="col" class="px-1 py-1 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">May</th>
                        <th scope="col" class="px-1 py-1 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">Jun</th>
                        <th scope="col" class="px-1 py-1 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">Jul</th>
                        <th scope="col" class="px-1 py-1 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">Aug</th>
                        <th scope="col" class="px-1 py-1 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">Sep</th>
                        <th scope="col" class="px-1 py-1 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">Oct</th>
                        <th scope="col" class="px-1 py-1 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">Nov</th>
                        <th scope="col" class="px-1 py-1 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-l border-gray-400">Dec</th>
                      </tr>
                  </thead>
                  <tbody class="bg-white">
                        @php
                        $supply_name = App\Models\BudgetCategory::where('id', 1)->first()->name;
                        @endphp
                      <tr class="border-t border-gray-200">
                          <th colspan="21" scope="colgroup" class="bg-yellow-100 py-2 pl-4 pr-3
                          text-left text-sm font-semibold text-gray-900 sm:pl-3">{{$supply_name}}</th>
                        </tr>
                        @forelse ($record->wfpDetails->where('budget_category_id', 1) as $item)
                        <tr class="border-t border-gray-300">
                          <td class="whitespace-nowrap py-2 pl-2 pr-3 text-sm font-medium text-gray-900 sm:pl-3">{{$item->uacs_code ?? 'not added'}}</td>
                          <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->categoryItem->name}}</td>
                          <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->supply->particulars}}</td>
                          <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->supply->supply_code}}</td>
                          <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->total_quantity}}</td>
                          <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">{{$item->uom}}</td>
                          <td class="whitespace-nowrap px-3 py-2 text-sm text-right text-gray-500">{{number_format($item->cost_per_unit, 2)}}</td>
                          <td class="whitespace-nowrap px-3 py-2 text-sm text-right text-gray-500">{{number_format((float)($item->cost_per_unit * $item->total_quantity), 2, '.', ',') }}</td>
                          @foreach (json_decode($item->quantity_year) as $quantity)
                          <td class="whitespace-nowrap px-1 text-center py-2 text-sm text-gray-500 border-l border-gray-400">{{$quantity}}</td>
                          @endforeach
                        </tr>
                      @empty
                      <tr class="border-t border-gray-200">
                          <th colspan="21" scope="colgroup" class="bg-gray-100 py-2 pl-4 pr-3 text-center text-sm font-semibold text-gray-900 sm:pl-3">No Record</th>
                        </tr>
                      @endforelse
                      @php
                      $mooe_name = App\Models\BudgetCategory::where('id', 2)->first()->name;
                      @endphp
                      <tr class="border-t border-gray-200">
                          <th colspan="21" scope="colgroup" class="bg-yellow-100 py-2 pl-4 pr-3
                          text-left text-sm font-semibold text-gray-900 sm:pl-3">{{$mooe_name}}</th>
                        </tr>
                        @forelse ($record->wfpDetails->where('budget_category_id', 2) as $item)
                        <tr class="border-t border-gray-300">
                            <td class="whitespace-nowrap py-2 pl-2 pr-3 text-sm font-medium text-gray-900 sm:pl-3">{{$item->uacs_code}}</td>
                            <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->categoryItem->name}}</td>
                            <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->supply->particulars}}</td>
                            <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->supply->supply_code}}</td>
                            <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->total_quantity}}</td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">{{$item->uom}}</td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-right text-gray-500">{{number_format($item->cost_per_unit, 2)}}</td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-right text-gray-500">{{number_format((float)($item->cost_per_unit * $item->total_quantity), 2, '.', ',') }}</td>
                            @foreach (json_decode($item->quantity_year) as $quantity)
                            <td class="whitespace-nowrap px-1 text-center py-2 text-sm text-gray-500 border-l border-gray-400">{{$quantity}}</td>
                            @endforeach
                          </tr>
                      @empty
                      <tr class="border-t border-gray-200">
                          <th colspan="21" scope="colgroup" class="bg-gray-100 py-2 pl-4 pr-3 text-center text-sm font-semibold text-gray-900 sm:pl-3">No Record</th>
                        </tr>
                      @endforelse
                      @php
                      $training_name = App\Models\BudgetCategory::where('id', 3)->first()->name;
                      @endphp
                      <tr class="border-t border-gray-200">
                          <th colspan="21" scope="colgroup" class="bg-yellow-100 py-2 pl-4 pr-3
                          text-left text-sm font-semibold text-gray-900 sm:pl-3">{{$training_name}}</th>
                        </tr>
                        @forelse ($record->wfpDetails->where('budget_category_id', 3) as $item)
                        <tr class="border-t border-gray-300">
                            <td class="whitespace-nowrap py-2 pl-2 pr-3 text-sm font-medium text-gray-900 sm:pl-3">{{$item->uacs_code}}</td>
                            <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->categoryItem->name}}</td>
                            <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->supply->particulars}}</td>
                            <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->supply->supply_code}}</td>
                            <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->total_quantity}}</td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">{{$item->uom}}</td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-right text-gray-500">{{number_format($item->cost_per_unit, 2)}}</td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-right text-gray-500">{{number_format((float)($item->cost_per_unit * $item->total_quantity), 2, '.', ',') }}</td>
                            @foreach (json_decode($item->quantity_year) as $quantity)
                            <td class="whitespace-nowrap px-1 text-center py-2 text-sm text-gray-500 border-l border-gray-400">{{$quantity}}</td>
                            @endforeach
                          </tr>
                      @empty
                      <tr class="border-t border-gray-200">
                          <th colspan="21" scope="colgroup" class="bg-gray-100 py-2 pl-4 pr-3 text-center text-sm font-semibold text-gray-900 sm:pl-3">No Record</th>
                        </tr>
                      @endforelse
                      @php
                      $machine_name = App\Models\BudgetCategory::where('id', 4)->first()->name;
                      @endphp
                      <tr class="border-t border-gray-200">
                          <th colspan="21" scope="colgroup" class="bg-yellow-100 py-2 pl-4 pr-3
                          text-left text-sm font-semibold text-gray-900 sm:pl-3">{{$machine_name}}</th>
                        </tr>
                        @forelse ($record->wfpDetails->where('budget_category_id', 4) as $item)
                        <tr class="border-t border-gray-300">
                            <td class="whitespace-nowrap py-2 pl-2 pr-3 text-sm font-medium text-gray-900 sm:pl-3">{{$item->uacs_code}}</td>
                            <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->categoryItem->name}}</td>
                            <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->supply->particulars}}</td>
                            <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->supply->supply_code}}</td>
                            <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->total_quantity}}</td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">{{$item->uom}}</td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-right text-gray-500">{{number_format($item->cost_per_unit, 2)}}</td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-right text-gray-500">{{number_format((float)($item->cost_per_unit * $item->total_quantity), 2, '.', ',') }}</td>
                            @foreach (json_decode($item->quantity_year) as $quantity)
                            <td class="whitespace-nowrap px-1 text-center py-2 text-sm text-gray-500 border-l border-gray-400">{{$quantity}}</td>
                            @endforeach
                          </tr>
                      @empty
                      <tr class="border-t border-gray-200">
                          <th colspan="21" scope="colgroup" class="bg-gray-100 py-2 pl-4 pr-3 text-center text-sm font-semibold text-gray-900 sm:pl-3">No Record</th>
                        </tr>
                      @endforelse
                      @php
                      $building_name = App\Models\BudgetCategory::where('id', 5)->first()->name;
                      @endphp
                      <tr class="border-t border-gray-200">
                          <th colspan="21" scope="colgroup" class="bg-yellow-100 py-2 pl-4 pr-3
                          text-left text-sm font-semibold text-gray-900 sm:pl-3">{{$building_name}}</th>
                        </tr>
                        @forelse ($record->wfpDetails->where('budget_category_id', 5) as $item)
                        <tr class="border-t border-gray-300">
                            <td class="whitespace-nowrap py-2 pl-2 pr-3 text-sm font-medium text-gray-900 sm:pl-3">{{$item->uacs_code}}</td>
                            <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->categoryItem->name}}</td>
                            <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->supply->particulars}}</td>
                            <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->supply->supply_code}}</td>
                            <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->total_quantity}}</td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">{{$item->uom}}</td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-right text-gray-500">{{number_format($item->cost_per_unit, 2)}}</td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-right text-gray-500">{{number_format((float)($item->cost_per_unit * $item->total_quantity), 2, '.', ',') }}</td>
                            @foreach (json_decode($item->quantity_year) as $quantity)
                            <td class="whitespace-nowrap px-1 text-center py-2 text-sm text-gray-500 border-l border-gray-400">{{$quantity}}</td>
                            @endforeach
                          </tr>
                      @empty
                      <tr class="border-t border-gray-200">
                          <th colspan="21" scope="colgroup" class="bg-gray-100 py-2 pl-4 pr-3 text-center text-sm font-semibold text-gray-900 sm:pl-3">No Record</th>
                        </tr>
                      @endforelse
                      @php
                      $ps_name = App\Models\BudgetCategory::where('id', 6)->first()->name;
                      @endphp
                      <tr class="border-t border-gray-200">
                        <th colspan="21" scope="colgroup" class="bg-yellow-100 py-2 pl-4 pr-3
                        text-left text-sm font-semibold text-gray-900 sm:pl-3">{{$ps_name}}</th>
                      </tr>
                      @forelse ($record->wfpDetails->where('budget_category_id', 6) as $item)
                      <tr class="border-t border-gray-300">
                          <td class="whitespace-nowrap py-2 pl-2 pr-3 text-sm font-medium text-gray-900 sm:pl-3">{{$item->uacs_code}}</td>
                          <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->categoryItem->name}}</td>
                          <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->supply->particulars}}</td>
                          <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->supply->supply_code}}</td>
                          <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->total_quantity}}</td>
                          <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">{{$item->uom}}</td>
                          <td class="whitespace-nowrap px-3 py-2 text-sm text-right text-gray-500">{{number_format($item->cost_per_unit, 2)}}</td>
                          <td class="whitespace-nowrap px-3 py-2 text-sm text-right text-gray-500">{{number_format((float)($item->cost_per_unit * $item->total_quantity), 2, '.', ',') }}</td>
                          @foreach (json_decode($item->quantity_year) as $quantity)
                          <td class="whitespace-nowrap px-1 text-center py-2 text-sm text-gray-500 border-l border-gray-400">{{$quantity}}</td>
                          @endforeach
                        </tr>
                    @empty
                    <tr class="border-t border-gray-200">
                        <th colspan="21" scope="colgroup" class="bg-gray-100 py-2 pl-4 pr-3 text-center text-sm font-semibold text-gray-900 sm:pl-3">No Record</th>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
                <div class="grid grid-cols-3 space-x-3 mt-5">
                    <div class="col-span-1 text-gray-800 font-semibold">

                    </div>
                    <div class="col-span-1 text-gray-800 font-semibold">

                    </div>
                    <div class="col-span-1 text-gray-800 font-semibold flex justify-end">
                        <div>
                            <div class="flex justify-between space-x-3">
                                <span>Allocated Fund: </span><span>₱ {{number_format($record->total_allocated_fund, 2)}}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Program: </span><span>₱ {{number_format($program, 2)}}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Balance: </span><span>₱ {{number_format($balance, 2)}}</span>
                            </div>
                        </div>
                    </div>
                  </div>
                  {{-- signatories --}}
                  @php
                  $president = App\Models\EmployeeInformation::where('position_id', 34)->where('office_id', 51)->first();
                  $vp_finance = App\Models\EmployeeInformation::where('position_id', 29)->where('office_id', 8)->first();
                  $budget = App\Models\EmployeeInformation::where('position_id', 15)->where('office_id', 2)->first();
                  @endphp
                  <div class="flex justify-center mt-5">
                    Prepared by:
                  </div>
                  <div class="flex justify-center underline font-semibold">
                    {{$record->costCenter->office->head_employee?->full_name}}
                  </div>
                  <div class="flex justify-center">
                    Cost Center Manager
                  </div>
                    <div class="grid grid-cols-3 space-x-3 mt-5">
                        <div class="col-span-1">
                            <div class="">
                                <div class="flex justify-center mt-5">
                                    Noted by:
                                  </div>
                                  <div class="flex justify-center underline font-semibold">
                                    {{$budget->full_name}}
                                  </div>
                                  <div class="flex justify-center">
                                    Budget Officer
                                  </div>
                            </div>
                        </div>
                        <div class="col-span-1">
                            <div class="">
                                <div class="flex justify-center mt-5">
                                    Recommending Approval:
                                  </div>
                                  <div class="flex justify-center underline font-semibold">
                                    {{$vp_finance->full_name}}
                                  </div>
                                  <div class="flex justify-center">
                                    VP Finance
                                  </div>
                            </div>
                        </div>
                        <div class="col-span-1">
                            <div class="">
                                <div class="flex justify-center mt-5">
                                    Approved by:
                                  </div>
                                  <div class="flex justify-center underline font-semibold">
                                    {{$president->full_name}}
                                  </div>
                                  <div class="flex justify-center">
                                    University President
                                  </div>
                            </div>
                        </div>
              </div>
            </div>
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
