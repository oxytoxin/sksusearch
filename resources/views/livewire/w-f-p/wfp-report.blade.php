<div x-data class="space-y-2">
    <div class="flex justify-between items-center">
        <h2 class="font-light capitalize text-primary-600">Wfp Report</h2>
        <div class="flex space-x-3">
            <button @click="printOut($refs.printContainer.outerHTML);" type="button" class="flex hover:bg-yellow-500 p-2 bg-yellow-600 rounded-md font-light capitalize text-white text-sm">
               Print
            </button>
            <button onclick="window.history.back()" class="flex hover:bg-gray-500 p-2 bg-gray-600 rounded-md font-light capitalize text-white text-sm">
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
                Work & Financial Plan
            </p>
            <p class="text-md font-normal">{{$record->wfpType->description}}</p>
        </div>
        <div>
            <div class="p-6 flex-col text-sm w-1/4 font-medium divide-y-2 divide-gray-800">
                <div class="py-2">
                  <span class="text-left font-semibold">Fund:</span>
                  <span class="text-center">{{$record->fundClusterWfp->name}} - {{$record->fund_description}}</span>
              </div>
                <div class="py-2">
                    <span class="text-left font-semibold">Souce of Fund: </span>
                    <span class="text-center">{{$record->source_fund}}</span>
                </div>
                <div class="py-2">
                    <span class="text-left font-semibold">if miscellaneous/fiduciary fee, please specify: </span>
                    <span class="text-center">{{$record->confirm_fund_source ?? 'N/A'}}</span>
                </div>
                <div class="py-2">
                    <span class="text-left font-semibold">Cost Center: </span>
                    <span class="text-center">{{$record->costCenter->name}}</span>
                </div>
                <div class="py-2">
                    <span class="text-left font-semibold">Cost Center Head: </span>
                    <span class="text-center">{{$record->costCenter->office->head_employee?->full_name.' - '.$record->costCenter->office->name}}</span>
                </div>
                <div></div>
            </div>
        </div>

        <div class="mt-6 flow-root">
            <div class="my-2 overflow-x-auto sm:-mx-6 lg:-mx-2">
              <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-2">
                <table class="min-w-full">
                  <thead class="bg-gray-400">
                      <tr class="border-t border-gray-200">
                        <th colspan="21" scope="colgroup" class="bg-green-700 py-2 pl-4 pr-3 text-left text-sm font-semibold text-gray-50 sm:pl-3 h-10"></th>
                      </tr>
                  </thead>
                  <thead class="bg-white">
                    <tr>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">UACS Code</th>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Account Title</th>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Particulars</th>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Qty</th>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">UOM</th>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Unit Cost</th>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Estimated Budget</th>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">Jan</th>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">Feb</th>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">Mar</th>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">Apr</th>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">May</th>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">Jun</th>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">Jul</th>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">Aug</th>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">Sep</th>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">Oct</th>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">Nov</th>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">Dec</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white">
                      <tr class="border-t border-gray-200">
                          <th colspan="20" scope="colgroup" class="bg-yellow-100 py-2 pl-4 pr-3
                          text-left text-sm font-semibold text-gray-900 sm:pl-3">Supplies & Semi-Expendables</th>
                        </tr>
                        @forelse ($record->wfpDetails->where('budget_category_id', 1) as $item)
                        <tr class="border-t border-gray-300">
                          <td class="whitespace-nowrap py-2 pl-2 pr-3 text-sm font-medium text-gray-900 sm:pl-3">{{$item->uacs_code}}</td>
                          <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->categoryItem->name}}</td>
                          <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->supply->particulars}}</td>
                          <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->total_quantity}}</td>
                          <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">{{$item->uom}}</td>
                          <td class="whitespace-nowrap px-3 py-2 text-sm text-right text-gray-500">₱ {{number_format($item->cost_per_unit, 2)}}</td>
                          <td class="whitespace-nowrap px-3 py-2 text-sm text-right text-gray-500">₱ {{number_format($item->estimated_budget, 2)}}</td>
                          @foreach (json_decode($item->quantity_year) as $quantity)
                          <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-x border-gray-400">{{$quantity}}</td>
                          @endforeach
                        </tr>
                      @empty
                      <tr class="border-t border-gray-200">
                          <th colspan="20" scope="colgroup" class="bg-gray-100 py-2 pl-4 pr-3 text-center text-sm font-semibold text-gray-900 sm:pl-3">No Record</th>
                        </tr>
                      @endforelse
                      <tr class="border-t border-gray-200">
                          <th colspan="20" scope="colgroup" class="bg-yellow-100 py-2 pl-4 pr-3
                          text-left text-sm font-semibold text-gray-900 sm:pl-3">MOOE</th>
                        </tr>
                        @forelse ($record->wfpDetails->where('budget_category_id', 2) as $item)
                        <tr class="border-t border-gray-300">
                            <td class="whitespace-nowrap py-2 pl-2 pr-3 text-sm font-medium text-gray-900 sm:pl-3">{{$item->uacs_code}}</td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->categoryItem->name}}</td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->supply->particulars}}</td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->total_quantity}}</td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">{{$item->uom}}</td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-right text-gray-500">₱ {{number_format($item->cost_per_unit, 2)}}</td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-right text-gray-500">₱ {{number_format($item->estimated_budget, 2)}}</td>
                            @foreach (json_decode($item->quantity_year) as $quantity)
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-x border-gray-400">{{$quantity}}</td>
                            @endforeach
                          </tr>
                      @empty
                      <tr class="border-t border-gray-200">
                          <th colspan="20" scope="colgroup" class="bg-gray-100 py-2 pl-4 pr-3 text-center text-sm font-semibold text-gray-900 sm:pl-3">No Record</th>
                        </tr>
                      @endforelse
                      <tr class="border-t border-gray-200">
                          <th colspan="20" scope="colgroup" class="bg-yellow-100 py-2 pl-4 pr-3
                          text-left text-sm font-semibold text-gray-900 sm:pl-3">Trainings</th>
                        </tr>
                        @forelse ($record->wfpDetails->where('budget_category_id', 3) as $item)
                        <tr class="border-t border-gray-300">
                            <td class="whitespace-nowrap py-2 pl-2 pr-3 text-sm font-medium text-gray-900 sm:pl-3">{{$item->uacs_code}}</td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->categoryItem->name}}</td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->supply->particulars}}</td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->total_quantity}}</td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">{{$item->uom}}</td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-right text-gray-500">₱ {{number_format($item->cost_per_unit, 2)}}</td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-right text-gray-500">₱ {{number_format($item->estimated_budget, 2)}}</td>
                            @foreach (json_decode($item->quantity_year) as $quantity)
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-x border-gray-400">{{$quantity}}</td>
                            @endforeach
                          </tr>
                      @empty
                      <tr class="border-t border-gray-200">
                          <th colspan="20" scope="colgroup" class="bg-gray-100 py-2 pl-4 pr-3 text-center text-sm font-semibold text-gray-900 sm:pl-3">No Record</th>
                        </tr>
                      @endforelse
                      <tr class="border-t border-gray-200">
                          <th colspan="20" scope="colgroup" class="bg-yellow-100 py-2 pl-4 pr-3
                          text-left text-sm font-semibold text-gray-900 sm:pl-3">Machine & Equipment / Furniture & Fixtures / Bio / Vehicles</th>
                        </tr>
                        @forelse ($record->wfpDetails->where('budget_category_id', 4) as $item)
                        <tr class="border-t border-gray-300">
                            <td class="whitespace-nowrap py-2 pl-2 pr-3 text-sm font-medium text-gray-900 sm:pl-3">{{$item->uacs_code}}</td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->categoryItem->name}}</td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->supply->particulars}}</td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->total_quantity}}</td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">{{$item->uom}}</td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-right text-gray-500">₱ {{number_format($item->cost_per_unit, 2)}}</td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-right text-gray-500">₱ {{number_format($item->estimated_budget, 2)}}</td>
                            @foreach (json_decode($item->quantity_year) as $quantity)
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-x border-gray-400">{{$quantity}}</td>
                            @endforeach
                          </tr>
                      @empty
                      <tr class="border-t border-gray-200">
                          <th colspan="20" scope="colgroup" class="bg-gray-100 py-2 pl-4 pr-3 text-center text-sm font-semibold text-gray-900 sm:pl-3">No Record</th>
                        </tr>
                      @endforelse
                      <tr class="border-t border-gray-200">
                          <th colspan="20" scope="colgroup" class="bg-yellow-100 py-2 pl-4 pr-3
                          text-left text-sm font-semibold text-gray-900 sm:pl-3">Building & Infrastructure</th>
                        </tr>
                        @forelse ($record->wfpDetails->where('budget_category_id', 5) as $item)
                        <tr class="border-t border-gray-300">
                            <td class="whitespace-nowrap py-2 pl-2 pr-3 text-sm font-medium text-gray-900 sm:pl-3">{{$item->uacs_code}}</td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->categoryItem->name}}</td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->supply->particulars}}</td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->total_quantity}}</td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">{{$item->uom}}</td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-right text-gray-500">₱ {{number_format($item->cost_per_unit, 2)}}</td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-right text-gray-500">₱ {{number_format($item->estimated_budget, 2)}}</td>
                            @foreach (json_decode($item->quantity_year) as $quantity)
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-x border-gray-400">{{$quantity}}</td>
                            @endforeach
                          </tr>
                      @empty
                      <tr class="border-t border-gray-200">
                          <th colspan="20" scope="colgroup" class="bg-gray-100 py-2 pl-4 pr-3 text-center text-sm font-semibold text-gray-900 sm:pl-3">No Record</th>
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
                                <span>Program: </span><span>₱ {{number_format($record->program_allocated, 2)}}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Balance: </span><span>₱ {{number_format($record->balance, 2)}}</span>
                            </div>
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
