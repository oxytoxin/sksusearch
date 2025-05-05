<table class="w-full mt-4">
    <thead>
        <tr>
            <th colspan="2" class="border border-black bg-gray-300">Receipts</th>
            <th colspan="3" class="border border-black bg-gray-300">Expenditure</th>
            <th class="border border-black bg-gray-300">Balance</th>
        </tr>
    </thead>
    <tbody>
        <thead>
            <tr>
                <th class="border border-black">MFO Fee</th>
                <th class="border border-black">Allocation</th>
                <th class="border border-black">UACS Code</th>
                <th class="border border-black">Account Title</th>
                <th class="border border-black">Allocated Budget</th>
                <th class="border border-black">Programmed</th>
                <th class="border border-black"></th>
            </tr>
        </thead>
        @forelse($fund_allocation as $item)
        <tr>
            <td class="border border-black px-2">{{$item->name}}</td>
            <td class="border border-black px-2">
                <div class="flex justify-between">
                    <span>₱</span>
                    <span>{{number_format($item->total_allocated, 2)}}</span>
                </div>
            </td>
            <td class="border border-black px-2">
                @foreach ($ppmp_details->where('mfo_fee_id', $item->mfo_fee_id) as $ppmp)
                <ul>
                    <li>
                        {{$ppmp->budget_uacs ?? $ppmp->uacs}}
                    </li>
                </ul>
                @endforeach
            </td>
            <td class="border border-black px-2">
                @foreach ($ppmp_details->where('mfo_fee_id', $item->mfo_fee_id) as $ppmp)
                <ul>
                    <li>
                        <div class="flex justify-between">
                            <span>{{$ppmp->budget_name}}</span>
                        </div>
                    </li>
                </ul>
                @endforeach
            </td>
            <td class="border border-black px-2">
                @foreach ($ppmp_details->where('mfo_fee_id', $item->mfo_fee_id) as $ppmp)
                <ul>
                    <li>
                        <div class="flex justify-between">
                            <span>₱ {{number_format($ppmp->total_budget_per_uacs, 2)}}</span>
                        </div>
                    </li>
                </ul>
                @endforeach
            </td>
            <td class="border border-black px-2">
                <div class="flex justify-between">
                    <span>₱</span>
                    <span>{{number_format($ppmp_details->where('mfo_fee_id', $item->mfo_fee_id)->sum('total_budget'), 2)}}</span>
                </div>
            </td>
            <td class="border border-black px-2">
                <div class="flex justify-between">
                    <span>₱</span>
                    <span>{{number_format($item->total_allocated - $ppmp_details->where('mfo_fee_id', $item->mfo_fee_id)->sum('total_budget'), 2)}}</span>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td class="border border-black text-center py-3 italic" colspan="3">No data available</td>
        </tr>
        @endforelse
    </tbody>
    <tr>
        <td class="border border-black text-left font-semibold p-1" colspan="1">Grand Total</td>
        <td class="border border-black text-right font-semibold px-2">
            <div class="flex justify-between">
                <span>₱</span>
                <span>{{$total_allocated === null ? 0 : number_format($total_allocated, 2)}}</span>
            </div>
        </td>
        <td class="border border-black text-left font-semibold p-1" colspan="2"></td>
        <td class="border border-black text-right font-semibold px-2">
            <div class="flex justify-between">
                <span>₱</span>
                <span>{{$total_programmed === null ? 0 : number_format($total_programmed->total_budget, 2)}}</span>
            </div>
        </td>
        <td class="border border-black text-right font-semibold px-2">
            <div class="flex justify-between">
                <span>₱</span>
                <span>{{$total_programmed === null ? 0 : number_format($balance, 2)}}</span>
            </div>
        </td>
    </tr>
</table>
