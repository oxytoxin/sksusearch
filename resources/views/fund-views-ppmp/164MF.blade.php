<div x-data>
    <div class="p-4">
        <div class="flex justify-center">
            <button @click="showPrintable = true" wire:click="sksuPpmp164MF" class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-8 rounded-lg">
                SKSU 164MF
            </button>
        </div>
        <div class="flex justify-center space-x-4 mt-3">
            <button @click="showPrintable = true" wire:click="gasPpmp164MF" class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg">
                General Admission and Support Services (GASS)
            </button>
            <button @click="showPrintable = true" wire:click="hesPpmp164MF" class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg">
                Higher Education Services (HES)
            </button>
            <button @click="showPrintable = true" wire:click="aesPpmp164MF" class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg">
                Advanced Education Services (AES)
            </button>
            <button @click="showPrintable = true" wire:click="rdPpmp164MF" class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg">
                Research and Development (RD)
            </button>
            <button @click="showPrintable = true" wire:click="extensionPpmp164MF" class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg">
                Extension Services (ES)
            </button>
            <button @click="showPrintable = true" wire:click="lfPpmp164MF" class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg">
                Local Fund Projects (LFP)
            </button>
        </div>
    </div>
    <div x-show="showPrintable" class="bg-gray-50">
        @if($is_active)
        <div class="flex justify-end p-4">
            <button @click="printOut($refs.printContainer.outerHTML);" type="button" class="flex hover:bg-yellow-500 p-2 bg-yellow-600 rounded-md font-light capitalize text-white text-sm">
                Print PPMP
             </button>
        </div>
        <div id="printarea" class="w-full bg-gray-50 px-2 py-4 rounded-md">
            <div class="text-center">
                <p class="text-2xl font-medium">
                    Project Procurement Management Plan (PPMP)
                </p>
                <p class="text-xl font-medium">
                    Fund 164MF
                </p>
                <p class="text-md font-normal">
                    {{$title}}

                </p>
            </div>
            <div class="my-2 overflow-x-auto sm:-mx-6 lg:-mx-2">
                <div class="min-w-full py-2 align-middle sm:px-6 lg:px-2">
                  <table class="min-w-full">
                    <thead class="bg-gray-400">
                        <tr class="border-t border-gray-200">
                          <th colspan="22" scope="colgroup" class="bg-green-700 py-2 pl-4 pr-3 text-left text-sm font-semibold text-gray-50 sm:pl-3 h-10"></th>
                        </tr>
                    </thead>
                    <thead class="bg-white">
                      <tr>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">UACS Code</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Account Title</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Particulars</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Supply Code</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Qty</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">UOM</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Unit Cost (₱)</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Estimated Budget (₱)</th>
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
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-l border-gray-400">Dec</th>
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
                          @forelse ($record->where('budget_category_id', 1) as $item)
                          <tr class="border-t border-gray-300">
                            <td class="whitespace-nowrap py-2 pl-2 pr-3 text-sm font-medium text-gray-900 sm:pl-3">{{$item->uacs_code}}</td>
                            <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->categoryItem->name}}</td>
                            <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->supply->particulars}}</td>
                            <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->supply->supply_code}}</td>
                            <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->total_quantity}}</td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">{{$item->uom}}</td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-right text-gray-500">{{number_format($item->cost_per_unit, 2)}}</td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-right text-gray-500">{{number_format((float)($item->cost_per_unit * $item->total_quantity), 2, '.', ',') }}</td>
                            @foreach ($item->merged_quantities as $quantity)
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l border-gray-400">{{$quantity}}</td>
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
                          @forelse ($record->where('budget_category_id', 2) as $item)
                          <tr class="border-t border-gray-300">
                              <td class="whitespace-nowrap py-2 pl-2 pr-3 text-sm font-medium text-gray-900 sm:pl-3">{{$item->uacs_code}}</td>
                              <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->categoryItem->name}}</td>
                              <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->supply->particulars}}</td>
                              <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->supply->supply_code}}</td>
                              <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->total_quantity}}</td>
                              <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">{{$item->uom}}</td>
                              <td class="whitespace-nowrap px-3 py-2 text-sm text-right text-gray-500">{{number_format($item->cost_per_unit, 2)}}</td>
                              <td class="whitespace-nowrap px-3 py-2 text-sm text-right text-gray-500">{{number_format((float)($item->cost_per_unit * $item->total_quantity), 2, '.', ',') }}</td>
                              @foreach ($item->merged_quantities as $quantity)
                              <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l border-gray-400">{{$quantity}}</td>
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
                          @forelse ($record->where('budget_category_id', 3) as $item)
                          <tr class="border-t border-gray-300">
                              <td class="whitespace-nowrap py-2 pl-2 pr-3 text-sm font-medium text-gray-900 sm:pl-3">{{$item->uacs_code}}</td>
                              <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->categoryItem->name}}</td>
                              <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->supply->particulars}}</td>
                              <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->supply->supply_code}}</td>
                              <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->total_quantity}}</td>
                              <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">{{$item->uom}}</td>
                              <td class="whitespace-nowrap px-3 py-2 text-sm text-right text-gray-500">{{number_format($item->cost_per_unit, 2)}}</td>
                              <td class="whitespace-nowrap px-3 py-2 text-sm text-right text-gray-500">{{number_format((float)($item->cost_per_unit * $item->total_quantity), 2, '.', ',') }}</td>
                              @foreach ($item->merged_quantities as $quantity)
                              <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l border-gray-400">{{$quantity}}</td>
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
                          @forelse ($record->where('budget_category_id', 4) as $item)
                          <tr class="border-t border-gray-300">
                              <td class="whitespace-nowrap py-2 pl-2 pr-3 text-sm font-medium text-gray-900 sm:pl-3">{{$item->uacs_code}}</td>
                              <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->categoryItem->name}}</td>
                              <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->supply->particulars}}</td>
                              <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->supply->supply_code}}</td>
                              <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->total_quantity}}</td>
                              <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">{{$item->uom}}</td>
                              <td class="whitespace-nowrap px-3 py-2 text-sm text-right text-gray-500">{{number_format($item->cost_per_unit, 2)}}</td>
                              <td class="whitespace-nowrap px-3 py-2 text-sm text-right text-gray-500">{{number_format((float)($item->cost_per_unit * $item->total_quantity), 2, '.', ',') }}</td>
                              @foreach ($item->merged_quantities as $quantity)
                              <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l border-gray-400">{{$quantity}}</td>
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
                          @forelse ($record->where('budget_category_id', 5) as $item)
                          <tr class="border-t border-gray-300">
                              <td class="whitespace-nowrap py-2 pl-2 pr-3 text-sm font-medium text-gray-900 sm:pl-3">{{$item->uacs_code}}</td>
                              <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->categoryItem->name}}</td>
                              <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->supply->particulars}}</td>
                              <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->supply->supply_code}}</td>
                              <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->total_quantity}}</td>
                              <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">{{$item->uom}}</td>
                              <td class="whitespace-nowrap px-3 py-2 text-sm text-right text-gray-500">{{number_format($item->cost_per_unit, 2)}}</td>
                              <td class="whitespace-nowrap px-3 py-2 text-sm text-right text-gray-500">{{number_format((float)($item->cost_per_unit * $item->total_quantity), 2, '.', ',') }}</td>
                              @foreach ($item->merged_quantities as $quantity)
                              <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l border-gray-400">{{$quantity}}</td>
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
                          @forelse ($record->where('budget_category_id', 6) as $item)
                          <tr class="border-t border-gray-300">
                              <td class="whitespace-nowrap py-2 pl-2 pr-3 text-sm font-medium text-gray-900 sm:pl-3">{{$item->uacs_code}}</td>
                              <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->categoryItem->name}}</td>
                              <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->supply->particulars}}</td>
                              <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->supply->supply_code}}</td>
                              <td class="px-3 py-2 text-sm text-gray-500 text-wrap">{{$item->total_quantity}}</td>
                              <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">{{$item->uom}}</td>
                              <td class="whitespace-nowrap px-3 py-2 text-sm text-right text-gray-500">{{number_format($item->cost_per_unit, 2)}}</td>
                              <td class="whitespace-nowrap px-3 py-2 text-sm text-right text-gray-500">{{number_format((float)($item->cost_per_unit * $item->total_quantity), 2, '.', ',') }}</td>
                              @foreach ($item->merged_quantities as $quantity)
                              <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l border-gray-400">{{$quantity}}</td>
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
                                <span>Total Program: </span><span>₱ {{number_format($total, 2)}}</span>
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
        @endif
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
