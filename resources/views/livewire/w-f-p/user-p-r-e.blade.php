<div x-data class="space-y-2">
    <div class="flex justify-between items-center">
        <h2 class="font-light capitalize text-primary-600">PRE Report</h2>
        <div class="flex space-x-3">
            <button @click="printOut($refs.printContainer.outerHTML);" type="button"
                class="flex hover:bg-yellow-500 p-2 bg-yellow-600 rounded-md font-light capitalize text-white text-sm">
                Print
            </button>
            <button onclick="window.history.back()"
                class="flex hover:bg-gray-500 p-2 bg-gray-600 rounded-md font-light capitalize text-white text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5 mr-3">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M7.49 12 3.74 8.248m0 0 3.75-3.75m-3.75 3.75h16.5V19.5" />
                </svg>
                Back
            </button>
        </div>
    </div>
    <div x-ref="printContainer" class="w-full bg-gray-50 px-2 py-4 rounded-md">
        <div class="text-center">
            <p class="text-2xl font-medium">
                Program of Receipts and Expenditures (PRE)
            </p>
            <p class="text-md font-normal">{{ $cost_center->office->name }} - {{ $cost_center->name }}</p>
            <p class="text-md font-normal">{{ $title }} - {{ $record->fund_description }}</p>
        </div>
        <div>

            @if (in_array($record->fundClusterWfp->id, [1, 3, 9]))
                <table class="w-full mt-4">
                    <thead>
                        <tr>
                            <th colspan="{{ request('isSupplemental') == 1 ? 4 : 2 }}"
                                class="border border-black bg-gray-300">Receipts</th>
                            <th colspan="3" class="border border-black bg-gray-300">Expenditure</th>
                            <th class="border border-black bg-gray-300">Balance</th>
                            {{-- <th colspan="2" class="border border-black bg-gray-300">Corresponding Account Codes</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        <thead>
                            <tr>
                                <th class="border border-black">Category Group</th>
                                <th class="border border-black">Allocation</th>
                                @if (request('isSupplemental') == 1)
                                    <th class="border border-black">Forwared Balance</th>
                                    <th class="border border-black">Total Allocation</th>
                                @endif
                                <th class="border border-black">UACS Code</th>
                                <th class="border border-black">Account Title - Budget</th>
                                <th class="border border-black">Programmed</th>
                                <th class="border border-black"></th>
                                {{-- <th class="border border-black">UACS Code</th>
                                <th class="border border-black">Account Title</th> --}}
                            </tr>
                        </thead>
                        {{-- @dump($fund_allocation) --}}
                        @forelse($fund_allocation as $item)
                            <tr>
                                <td class="border border-black px-2">{{ $item->categoryGroup->name }}</td>
                                <td class="border border-black px-2">
                                    <div class="flex justify-between">
                                        <span>₱</span>
                                        @if (request('isSupplemental') == 1)
                                            @if ($item->is_supplemental == 1)
                                                <span>{{ number_format($item->initial_amount, 2) }}</span>
                                            @else
                                                <span>0.00</span>
                                            @endif
                                        @else
                                            <span>{{ number_format($item->initial_amount, 2) }}</span>
                                        @endif
                                    </div>
                                </td>
                                @if (request('isSupplemental') == 1)
                                    <td class="border border-black px-2">
                                        @foreach ($forwarded_ppmp_details->where('category_group_id', $item->category_group_id) as $forwarded_ppmp_detail)
                                            <ul>
                                                <div class="flex justify-between">
                                                    <span>{{ $forwarded_ppmp_detail->budget_name }} </span>
                                                    <span>₱
                                                        {{ $item->initial_amount == 0 ? '0.00' : number_format($forwarded_ppmp_detail->initial_amount - $sub_forwared_balance->where('category_group_id', $item->category_group_id)->sum('balance_amount'), 2) }}</span>
                                                </div>
                                            </ul>
                                        @endforeach
                                    </td>
                                @endif
                                @if (request('isSupplemental') == 1)
                                    <td class="border border-black px-2">
                                        @foreach ($forwarded_ppmp_details->where('category_group_id', $item->category_group_id) as $forwarded_ppmp_detail)
                                            <ul>
                                                <div class="flex justify-between">
                                                    <span>{{ $forwarded_ppmp_detail->budget_name }} </span>
                                                    <span>₱
                                                        {{ $item->initial_amount == 0 ? '0.00' : number_format($item->initial_amount + ($forwarded_ppmp_detail->initial_amount - $sub_forwared_balance->where('category_group_id', $item->category_group_id)->sum('balance_amount')), 2) }}</span>
                                                </div>
                                            </ul>
                                        @endforeach
                                    </td>
                                @endif
                                <td class="border border-black px-2">
                                    @foreach ($ppmp_details->where('category_group_id', $item->category_group_id) as $ppmp)
                                        <ul>
                                            <li>
                                                {{ $ppmp->budget_uacs ?? $ppmp->uacs }}
                                            </li>
                                        </ul>
                                    @endforeach
                                </td>
                                <td class="border border-black px-2">
                                    @foreach ($ppmp_details->where('category_group_id', $item->category_group_id) as $ppmp)
                                        <ul>
                                            <div class="flex justify-between">
                                                <span>{{ $ppmp->budget_name }} </span>
                                                <span>₱ {{ number_format($ppmp->total_budget_per_uacs, 2) }}</span>
                                            </div>
                                        </ul>
                                    @endforeach
                                </td>
                                <td class="border border-black px-2">
                                    <div class="flex justify-between">
                                        <span>₱</span>
                                        <span>{{ number_format($ppmp_details->where('category_group_id', $item->category_group_id)->sum('total_budget'), 2) }}</span>
                                    </div>
                                </td>
                                <td class="border border-black px-2">
                                    <div class="flex justify-between">
                                        <span>₱ </span>
                                        @if (request('isSupplemental') == 1)
                                            @if ($item->is_supplemental == 1)
                                                <span>{{ $item->initial_amount == 0 ? number_format($ppmp_details->where('category_group_id', $item->category_group_id)->sum('total_budget')) : number_format($item->initial_amount + ($forwarded_ppmp_details->where('category_group_id', $item->category_group_id)->sum('initial_amount') - $sub_forwared_balance->where('category_group_id', $item->category_group_id)->sum('balance_amount')) - $ppmp_details->where('category_group_id', $item->category_group_id)->sum('total_budget'), 2) }}</span>
                                            @else
                                                <span>0.00</span>
                                            @endif
                                        @else
                                            <span>{{ number_format($item->initial_amount - $ppmp_details->where('category_group_id', $item->category_group_id)->sum('total_budget'), 2) }}</span>
                                        @endif
                                    </div>
                                </td>
                                {{-- <td class="border border-black px-2">
                                @foreach ($ppmp_details->where('category_group_id', $item->category_group_id) as $ppmp)
                                <ul>
                                    <li>
                                        {{$ppmp->uacs}}
                                    </li>
                                </ul>
                                @endforeach
                            </td>
                            <td class="border border-black px-2">
                                @foreach ($ppmp_details->where('category_group_id', $item->category_group_id) as $ppmp)
                                <ul>
                                    <li>
                                        {{$ppmp->budget_name}}
                                    </li>
                                </ul>
                                @endforeach
                            </td> --}}
                            </tr>
                        @empty
                            <tr>
                                <td class="border border-black text-center py-3 italic" colspan="3">No data available
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tr>
                        <td class="border border-black text-left font-semibold p-1" colspan="1">Grand Total</td>
                        <td class="border border-black text-right font-semibold px-2">
                            <div class="flex justify-between">
                                <span>₱</span>
                                <span>{{ $total_allocated === null ? 0 : number_format($total_allocated, 2) }}</span>
                            </div>
                        </td>
                        @if (request('isSupplemental') == 1)
                            <td class="border border-black text-right font-semibold px-2">
                                <div class="flex justify-between">
                                    <span>₱</span>
                                    <span>{{ $total_programmed === null ? 0 : number_format($forwarded_balance, 2) }}</span>
                                </div>
                            </td>
                        @endif
                        @if (request('isSupplemental') == 1)
                            <td class="border border-black text-right font-semibold px-2">
                                <div class="flex justify-between">
                                    <span>₱</span>
                                    <span>{{ $total_programmed === null ? 0 : number_format($total_allocated + $forwarded_balance, 2) }}</span>
                                </div>
                            </td>
                        @endif
                        <td class="border border-black text-left font-semibold p-1" colspan="2"></td>
                        <td class="border border-black text-right font-semibold px-2">
                            <div class="flex justify-between">
                                <span>₱</span>
                                <span>{{ $total_programmed === null ? 0 : number_format($total_programmed->total_budget, 2) }}</span>
                            </div>
                        </td>
                        <td class="border border-black text-right font-semibold px-2">
                            <div class="flex justify-between">
                                <span>₱</span>
                                @if (request('isSupplemental') == 1)
                                    @php
                                        $sub = $total_programmed === null ? 0 : $total_programmed->total_budget;

                                        $total = $total_allocated + $forwarded_balance - $sub;
                                    @endphp
                                    <span>{{ $total_programmed === null ? 0 : number_format($total, 2) }}</span>
                                @else
                                    <span>{{ $total_programmed === null ? 0 : number_format($balance, 2) }}</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                </table>
            @else
                <table class="w-full mt-4">
                    <thead>
                        <tr>
                            <th colspan="{{ request('isSupplemental') == 1 ? 4 : 3 }}"
                                class="border border-black bg-gray-300">Receipts</th>
                            <th colspan="3" class="border border-black bg-gray-300">Expenditure</th>
                            <th class="border border-black bg-gray-300">Balance</th>
                            {{-- <th colspan="2" class="border border-black bg-gray-300">Corresponding Account Codes</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        <thead>
                            <tr>
                                <th class="border border-black">MFO Fee</th>
                                <th class="border border-black">Allocation</th>
                                @if (request('isSupplemental') == 1)
                                    <th class="border border-black">Forwarded Balance</th>
                                    <th class="border border-black">Total Allocation</th>
                                @endif
                                <th class="border border-black">UACS Code</th>
                                <th class="border border-black">Account Title - Budget</th>
                                <th class="border border-black">Programmed</th>
                                <th class="border border-black"></th>
                                {{-- <th class="border border-black">UACS Code</th>
                                <th class="border border-black">Account Title</th> --}}
                            </tr>
                        </thead>
                        @forelse($fund_allocation as $item)
                            <tr>
                                <td class="border border-black px-2">{{ $item->costCenter->mfoFee?->name }}</td>
                                <td class="border border-black px-2">
                                    <div class="flex justify-between">
                                        <span>₱</span>
                                        <span>{{ number_format($item->initial_amount, 2) }}</span>
                                    </div>
                                </td>
                                @if (request('isSupplemental') == 1)
                                    <td class="border border-black px-2">
                                        <div class="flex justify-between">
                                            <span>₱</span>
                                            <span>{{ number_format($_164['balance'], 2) }}</span>
                                        </div>
                                    </td>
                                    <td class="border border-black px-2">
                                        <div class="flex justify-between">
                                            <span>₱</span>
                                            <span>{{ number_format($item->initial_amount + $_164['balance'], 2) }}</span>
                                        </div>
                                    </td>
                                @endif
                                <td class="border border-black px-2">
                                    @foreach ($ppmp_details as $ppmp)
                                        <ul>
                                            <li>
                                                {{ $ppmp->budget_uacs ?? $ppmp->uacs }}
                                            </li>
                                        </ul>
                                    @endforeach
                                </td>
                                <td class="border border-black px-2">
                                    @foreach ($ppmp_details as $ppmp)
                                        <ul>
                                            <li>
                                                <div class="flex justify-between">
                                                    <span>{{ $ppmp->budget_name }} </span>
                                                    <span>₱ {{ number_format($ppmp->total_budget_per_uacs, 2) }}</span>
                                                </div>
                                            </li>
                                        </ul>
                                    @endforeach
                                </td>
                                <td class="border border-black px-2">
                                    <div class="flex justify-between">
                                        <span>₱</span>
                                        <span>{{ number_format($ppmp_details->sum('total_budget'), 2) }}</span>
                                    </div>
                                </td>
                                <td class="border border-black px-2">
                                    @if (request('isSupplemental') == 1)
                                        <div class="flex justify-between">
                                            <span>₱</span>
                                            <span>{{ number_format($fund_allocation->sum('initial_amount') + $_164['balance'] - $ppmp_details->sum('total_budget'), 2) }}</span>
                                        </div>
                                    @else
                                        <div class="flex justify-between">
                                            <span>₱</span>
                                            <span>{{ number_format($item->initial_amount - $ppmp_details->sum('total_budget'), 2) }}</span>
                                        </div>
                                    @endif

                                </td>
                                {{-- <td class="border border-black px-2">
                                @foreach ($ppmp_details->where('category_group_id', $item->category_group_id) as $ppmp)
                                <ul>
                                    <li>
                                        {{$ppmp->uacs}}
                                    </li>
                                </ul>
                                @endforeach
                            </td>
                            <td class="border border-black px-2">
                                @foreach ($ppmp_details->where('category_group_id', $item->category_group_id) as $ppmp)
                                <ul>
                                    <li>
                                        {{$ppmp->budget_name}}
                                    </li>
                                </ul>
                                @endforeach
                            </td> --}}
                            </tr>
                        @empty
                            <tr>
                                <td class="border border-black text-center py-3 italic" colspan="3">No data available
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if (request('isSupplemental') == 1)
                        <tr>
                            <td class="border border-black text-left font-semibold p-1" colspan="1">Grand Total</td>
                            <td class="border border-black text-right font-semibold px-2">
                                <div class="flex justify-between">
                                    <span>₱</span>
                                    <span>{{ $total_allocated === null ? 0 : number_format($total_allocated, 2) }}</span>
                                </div>
                            </td>
                            <td class="border border-black text-left font-semibold p-1">
                                <div class="flex justify-between">
                                    <span>₱</span>
                                    <span>{{ number_format($_164['balance'], 2) }}</span>
                                </div>
                            </td>
                            <td class="border border-black text-left font-semibold p-1">
                                <div class="flex justify-between">
                                    <span>₱</span>
                                    <span>{{ number_format($fund_allocation->sum('initial_amount') + $_164['balance'], 2) }}</span>
                                </div>
                            </td>
                            <td class="border border-black text-left font-semibold p-1"></td>
                            <td class="border border-black text-left font-semibold p-1"></td>
                            <td class="border border-black text-right font-semibold px-2">
                                <div class="flex justify-between">
                                    <span>₱</span>
                                    <span>{{ $total_programmed === null ? 0 : number_format($ppmp_details->sum('total_budget'), 2) }}</span>
                                </div>
                            </td>
                            <td class="border border-black text-right font-semibold px-2">
                                <div class="flex justify-between">
                                    <span>₱</span>
                                    <span>{{ $total_programmed === null ? 0 : number_format($fund_allocation->sum('initial_amount') + $_164['balance'] - $ppmp_details->sum('total_budget'), 2) }}</span>
                                </div>
                            </td>
                        </tr>
                    @else
                        <td class="border border-black text-left font-semibold p-1" colspan="1">Grand Total</td>
                        <td class="border border-black text-right font-semibold px-2">
                            <div class="flex justify-between">
                                <span>₱</span>
                                <span>{{ $total_allocated === null ? 0 : number_format($total_allocated, 2) }}</span>
                            </div>
                        </td>
                        <td class="border border-black text-left font-semibold p-1" colspan="2"></td>
                        <td class="border border-black text-right font-semibold px-2">
                            <div class="flex justify-between">
                                <span>₱</span>
                                <span>{{ $total_programmed === null ? 0 : number_format($total_programmed->total_budget, 2) }}</span>
                            </div>
                        </td>
                        <td class="border border-black text-right font-semibold px-2">
                            <div class="flex justify-between">
                                <span>₱</span>
                                <span>{{ $total_programmed === null ? 0 : number_format($balance, 2) }}</span>
                            </div>
                        </td>
                        </tr>
                    @endif

                </table>
            @endif

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
            {{ $record->costCenter->office->head_employee?->full_name }}
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
                        {{ $budget->full_name }}
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
                        {{ $vp_finance->full_name }}
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
                        {{ $president->full_name }}
                    </div>
                    <div class="flex justify-center">
                        University President
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
