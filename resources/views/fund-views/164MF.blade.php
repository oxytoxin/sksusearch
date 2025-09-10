<div x-data>
    <div class="p-4">
        <div class="grid gap-2 justify-center">
            <button wire:click="sksuPre(6)"
                class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-8 rounded-lg">
                SKSU 164MF PRE
            </button>
            <button @click="showPrintable = true" wire:click="sksuPpmp164MF"
                class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-8 rounded-lg">
                SKSU 164MF
            </button>
        </div>
        <div class="flex justify-center space-x-4 mt-3">
            <button @click="showPrintable = true" wire:click="gasPpmp164MF"
                class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg">
                General Admission and Support Services (GASS)
            </button>
            <button @click="showPrintable = true" wire:click="hesPpmp164MF"
                class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg">
                Higher Education Services (HES)
            </button>
            <button @click="showPrintable = true" wire:click="aesPpmp164MF"
                class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg">
                Advanced Education Services (AES)
            </button>
            <button @click="showPrintable = true" wire:click="rdPpmp164MF"
                class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg">
                Research and Development (RD)
            </button>
            <button @click="showPrintable = true" wire:click="extensionPpmp164MF"
                class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg">
                Extension Services (ES)
            </button>
            <button @click="showPrintable = true" wire:click="lfPpmp164MF"
                class="bg-green-800 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg">
                Local Fund Projects (LFP)
            </button>
        </div>
    </div>
    <div x-show="showPrintable" class="bg-gray-50">
        @if ($is_active)
            <div class="flex justify-end p-4 space-x-4">
                <button @click="printOut($refs.printContainer.outerHTML);" type="button"
                    class="flex hover:bg-yellow-500 p-2 bg-yellow-600 rounded-md font-light capitalize text-white text-sm">
                    Print PRE
                </button>
                <button wire:click="export164MF" type="button"
                    class="flex hover:bg-green-600 p-2 bg-green-700 rounded-md font-light capitalize text-white text-sm">
                    Export Excel
                </button>
                <a type="button" target="_blank"
                    href="/export/cost-center?is_supplemental={{ $is_q1 ? 1 : 0 }}&fund_cluster_w_f_p_s_id=6&wfp_type_id={{ $selectedType }}&m_f_o_s_id={{ $mfosId }}&fileName={{ str_replace(' ', '_', $title) }}.xlsx"
                    class="flex hover:bg-green-600 p-2 bg-green-700 rounded-md font-light capitalize text-white text-sm">
                    Cost Center Export
                </a>
            </div>
            <div x-ref="printContainer" class="w-full bg-gray-50 px-2 py-4 rounded-md">
                <div class="text-center">
                    <p class="text-2xl font-medium">
                        Program of Receipts & Expenditures (PRE)
                    </p>
                    <p class="text-xl font-medium">
                        Fund 164MF
                    </p>
                    <p class="text-md font-normal">{{ $title }}</p>
                </div>
                <div>
                    <table class="w-full mt-4">
                        <thead>
                            <tr>
                                @if ($is_q1 && in_array($activeButton, ['sksuPre', 'generateSksuppmp', 'generateSksuppmpPerCostCenterMfo']))
                                    <th colspan="4" class="border border-black bg-gray-300">Receipts</th>
                                @else
                                    <th colspan="2" class="border border-black bg-gray-300">Receipts</th>
                                @endif
                                <th colspan="3" class="border border-black bg-gray-300">Expenditure</th>
                                <th class="border border-black bg-gray-300">Balance</th>
                                {{-- <th colspan="2" class="border border-black bg-gray-300">Corresponding Account Codes
                            </th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            <thead>
                                <tr>
                                    <th class="border border-black">MFO Fee</th>
                                    <th class="border border-black">Allocation</th>
                                    @if ($is_q1 && in_array($activeButton, ['sksuPre', 'generateSksuppmp', 'generateSksuppmpPerCostCenterMfo']))
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
                            @php
                                $mergedDetails = $ppmp_details
                                    ->groupBy('budget_uacs')
                                    ->map(function ($group) {
                                        return [
                                            'total_budget' => $group->sum('total_budget'),
                                            'budget_uacs' => $group->first()->budget_uacs,
                                            'budget_name' => $group->first()->budget_name,
                                            'mfo_fee_id' => null,
                                            'total_budget_per_uacs' => $group->sum('total_budget_per_uacs'),
                                        ];
                                    })
                                    ->sortByDesc('budget_uacs')
                                    ->values();
                            @endphp
                            @if ($showPre)
                                @foreach ($mergedDetails as $mergedDetail)
                                    <tr>
                                        @if ($loop->first)
                                            <td class="border border-black px-2">Total Receipts</td>
                                            <td class="border border-black px-2">
                                                <div class="flex justify-between">
                                                    <span>₱</span>
                                                    <span>{{ number_format($fund_allocation->sum('total_allocated'), 2) }}</span>
                                                </div>
                                            </td>
                                            @if ($is_q1 && in_array($activeButton, ['sksuPre', 'generateSksuppmp', 'generateSksuppmpPerCostCenterMfo']))
                                                <td class="border border-black px-2">
                                                    <div class="flex justify-between">
                                                        <span>₱</span>
                                                        <span>{{ number_format(
                                                            $non_supplemental_fund_allocation->sum('total_allocated') - $non_supplemental_total_programmed->total_budget,
                                                            2,
                                                        ) }}</span>
                                                    </div>
                                                </td>
                                                <td class="border border-black px-2">
                                                    <div class="flex justify-between">
                                                        <span>₱</span>
                                                        <span>{{ number_format(
                                                            $non_supplemental_fund_allocation->sum('total_allocated') -
                                                                $non_supplemental_total_programmed->total_budget +
                                                                $fund_allocation->sum('total_allocated'),
                                                            2,
                                                        ) }}</span>
                                                    </div>
                                                </td>
                                            @endif
                                        @else
                                            <td class="border border-black px-2"></td>
                                            <td class="border border-black px-2">
                                            </td>
                                            @if ($is_q1 && in_array($activeButton, ['sksuPre', 'generateSksuppmp', 'generateSksuppmpPerCostCenterMfo']))
                                                <td class="border border-black px-2">
                                                </td>
                                                <td class="border border-black px-2">
                                                </td>
                                            @endif
                                        @endif
                                        <td class="border border-black px-2">
                                            {{ $mergedDetail['budget_uacs'] }}
                                        </td>
                                        <td class="border border-black px-2">
                                            <div class="flex justify-between">
                                                <span> {{ $mergedDetail['budget_name'] }}</span>
                                                {{-- <span>₱
                                        {{ number_format($mergedDetail['total_budget_per_uacs'], 2) }}</span> --}}
                                            </div>

                                        </td>
                                        <td class="border border-black px-2">
                                            <div class="flex justify-between">
                                                <span>₱</span>
                                                <span>
                                                    {{ number_format($mergedDetail['total_budget_per_uacs'], 2) }}</span>
                                            </div>
                                        </td>
                                        <td class="border border-black px-2">
                                            <div class="flex justify-between">
                                                {{-- <span>₱</span> --}}
                                                <span>
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                @forelse($fund_allocation as $item)
                                    <tr>
                                        <td class="border border-black px-2">{{ $item->name }}</td>
                                        <td class="border border-black px-2">
                                            @if ($is_q1 && in_array($activeButton, ['sksuPre', 'generateSksuppmp', 'generateSksuppmpPerCostCenterMfo']))
                                                <div class="flex justify-between">
                                                    <span>₱</span>
                                                    <span>{{ $item->is_supplemental ? number_format($item->total_allocated, 2) : '0.00' }}</span>
                                                </div>
                                            @else
                                                <div class="flex justify-between">
                                                    <span>₱</span>
                                                    <span>{{ number_format($item->total_allocated, 2) }}</span>
                                                </div>
                                            @endif
                                        </td>
                                        @if ($is_q1 && in_array($activeButton, ['sksuPre', 'generateSksuppmp', 'generateSksuppmpPerCostCenterMfo']))
                                            <td class="border border-black px-2">
                                                @if ($item->is_supplemental == 1)
                                                    <div class="flex justify-between">
                                                        <span>₱ </span>
                                                        <span>{{ number_format(
                                                            $non_supplemental_fund_allocation->where('mfo_fee_id', $item->mfo_fee_id)->sum('total_allocated') -
                                                                $forwarded_ppmp_details->where('mfo_fee_id', $item->mfo_fee_id)->sum('total_budget'),
                                                            2,
                                                        ) }}</span>
                                                    </div>
                                                @else
                                                    <div class="flex justify-between">
                                                        <span>₱</span>
                                                        <span>{{ number_format(
                                                            $item->total_allocated - $forwarded_ppmp_details->where('mfo_fee_id', $item->mfo_fee_id)->sum('total_budget'),
                                                            2,
                                                        ) }}</span>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="border border-black px-2">
                                                @if ($item->is_supplemental == 1)
                                                    <div class="flex justify-between">
                                                        <span>₱ </span>
                                                        <span>{{ number_format(
                                                            $non_supplemental_fund_allocation->where('mfo_fee_id', $item->mfo_fee_id)->sum('total_allocated') -
                                                                $forwarded_ppmp_details->where('mfo_fee_id', $item->mfo_fee_id)->sum('total_budget') +
                                                                $item->total_allocated,
                                                            2,
                                                        ) }}</span>
                                                    </div>
                                                @else
                                                    <div class="flex justify-between">
                                                        <span>₱ </span>
                                                        <span>{{ number_format(
                                                            $item->total_allocated - $forwarded_ppmp_details->where('mfo_fee_id', $item->mfo_fee_id)->sum('total_budget'),
                                                            2,
                                                        ) }}</span>
                                                    </div>
                                                @endif
                                            </td>
                                        @endif
                                        <td class="border border-black px-2">
                                            @foreach ($ppmp_details->where('mfo_fee_id', $item->mfo_fee_id) as $ppmp)
                                                <ul>
                                                    <li>
                                                        {{ $ppmp->budget_uacs ?? $ppmp->uacs }}
                                                    </li>
                                                </ul>
                                            @endforeach
                                        </td>
                                        <td class="border border-black px-2">
                                            @foreach ($ppmp_details->where('mfo_fee_id', $item->mfo_fee_id) as $ppmp)
                                                <ul>
                                                    <div class="flex justify-between">
                                                        <span>{{ $ppmp->budget_name }}</span>
                                                        <span>₱
                                                            {{ number_format($ppmp->total_budget_per_uacs, 2) }}</span>
                                                    </div>
                                                </ul>
                                            @endforeach
                                        </td>
                                        <td class="border border-black px-2">
                                            <div class="flex justify-between">
                                                <span>₱ </span>
                                                <span>{{ number_format($ppmp_details->where('mfo_fee_id', $item->mfo_fee_id)->sum('total_budget'), 2) }}</span>
                                            </div>
                                        </td>
                                        <td class="border border-black px-2">
                                            @if ($is_q1 && in_array($activeButton, ['sksuPre', 'generateSksuppmp', 'generateSksuppmpPerCostCenterMfo']))
                                                @if ($item->is_supplemental == 1)
                                                    <div class="flex justify-between">
                                                        <span>₱</span>
                                                        <span>{{ number_format(
                                                            $item->total_allocated - $ppmp_details->where('mfo_fee_id', $item->mfo_fee_id)->sum('total_budget'),
                                                            2,
                                                        ) }}</span>
                                                    </div>
                                                @else
                                                    <div class="flex justify-between">
                                                        <span>₱</span>
                                                        <span>0.00</span>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="flex justify-between">
                                                    <span>₱</span>
                                                    <span>{{ number_format(
                                                        $item->total_allocated - $ppmp_details->where('mfo_fee_id', $item->mfo_fee_id)->sum('total_budget'),
                                                        2,
                                                    ) }}</span>
                                                </div>
                                            @endif
                                        </td>
                                        {{-- <td class="border border-black px-2">
                                @foreach ($ppmp_details->where('mfo_fee_id', $item->mfo_fee_id) as $ppmp)
                                <ul>
                                    <li>
                                        {{$ppmp->uacs}}
                                    </li>
                                </ul>
                                @endforeach
                            </td>
                            <td class="border border-black px-2">
                                @foreach ($ppmp_details->where('mfo_fee_id', $item->mfo_fee_id) as $ppmp)
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
                                        <td class="border border-black text-center py-3 italic" colspan="3">
                                            No data available
                                        </td>
                                    </tr>
                                @endforelse
                            @endif
                        </tbody>
                        <tr>
                            <td class="border border-black text-left font-semibold p-1" colspan="1">Grand Total
                            </td>
                            <td class="border border-black text-right font-semibold px-2">
                                <div class="flex justify-between">
                                    <span>₱</span>
                                    <span>{{ $total_allocated === null ? 0 : number_format($total_allocated, 2) }}</span>
                                </div>
                            </td>
                            @if ($is_q1 && in_array($activeButton, ['generateSksuppmp', 'generateSksuppmpPerCostCenterMfo']))
                                <td class="border border-black text-left font-semibold p-1">
                                    <div class="flex justify-between">
                                        <span>₱ </span>
                                        <span>
                                            {{ $non_supplemental_fund_allocation->sum('total_allocated') > 0
                                                ? number_format(
                                                    $non_supplemental_fund_allocation->sum('total_allocated') - $non_supplemental_total_programmed->total_budget,
                                                    2,
                                                )
                                                : number_format($non_supplemental_total_programmed->total_budget, 2) }}</span>
                                    </div>
                                </td>
                                <td class="border border-black text-left font-semibold p-1">
                                    <div class="flex justify-between">
                                        <span>₱</span>
                                        <span>
                                            {{ $non_supplemental_fund_allocation->sum('total_allocated') > 0
                                                ? number_format(
                                                    $non_supplemental_fund_allocation->sum('total_allocated') -
                                                        $non_supplemental_total_programmed->total_budget +
                                                        ($total_allocated ?? 0),
                                                    2,
                                                )
                                                : number_format($non_supplemental_total_programmed->total_budget + ($total_allocated ?? 0), 2) }}</span>
                                    </div>
                                </td>
                                <td></td>
                                <td></td>
                            @elseif ($is_q1 && in_array($activeButton, ['sksuPre']))
                                <td class="border border-black text-left font-semibold p-1">
                                    <div class="flex justify-between">
                                        <span>₱</span>
                                        <span>{{ number_format(
                                            $non_supplemental_fund_allocation->sum('total_allocated') - $non_supplemental_total_programmed->total_budget,
                                            2,
                                        ) }}</span>
                                    </div>
                                </td>
                                <td class="border border-black text-left font-semibold p-1">
                                    <div class="flex justify-between">
                                        <span>₱</span>
                                        <span>{{ number_format(
                                            $non_supplemental_fund_allocation->sum('total_allocated') -
                                                $non_supplemental_total_programmed->total_budget +
                                                $total_allocated,
                                            2,
                                        ) }}</span>
                                    </div>
                                </td>
                                <td></td>
                                <td></td>
                            @else
                                <td class="border border-black text-left font-semibold p-1"></td>
                                <td class="border border-black text-left font-semibold p-1"></td>
                            @endif

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
                    </table>


                    {{-- <table class="w-full mt-4">
                    <thead>
                        <tr>
                            <th class="border border-black">UACS Code</th>
                            <th class="border border-black">Account Title</th>
                            <th class="border border-black">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ppmp_details as $item)
                        <tr>
                            <td class="border border-black px-2">{{$item->categoryItem?->uacs_code}}</td>
                            <td class="border border-black px-2">{{$item->categoryItem?->name}}</td>
                            <td class="border border-black text-right px-2">₱ {{number_format($item->total_budget, 2)}}</td>
                        </tr>
                        @empty
                        <tr>
                            <td class="border border-black text-center py-3 italic" colspan="3">No data available</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tr>
                        <td class="border border-black text-left font-semibold p-1" colspan="2">Grand Total</td>
                        <td class="border border-black text-right font-semibold px-2">
                            <div class="flex justify-between">
                                <span>₱</span>
                                <span>{{$total === null ? 0 : number_format($total->total_budget, 2)}}</span>
                            </div>
                        </td>
                    </tr>
                </table> --}}
                </div>
                {{-- signatories --}}
                @php
                    $president = App\Models\EmployeeInformation::where('position_id', 34)
                        ->where('office_id', 51)
                        ->first();
                    $vp_finance = App\Models\EmployeeInformation::where('position_id', 29)
                        ->where('office_id', 8)
                        ->first();
                    $budget = App\Models\EmployeeInformation::where('position_id', 15)->where('office_id', 2)->first();
                @endphp
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
                            <div class="flex mt-8 justify-center underline font-semibold">
                                {{ $president->full_name }}
                            </div>
                            <div class="flex justify-center">
                                University President
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
