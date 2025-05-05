<table class="w-full mt-4">
    <thead>
        <tr>
            <th colspan="2" class="border border-black bg-gray-300">Receipts</th>
            <th colspan="3" class="border border-black bg-gray-300">Expenditure</th>
            <th class="border border-black bg-gray-300">Balance</th>
        </tr>
        <tr>
            <th class="border border-black">Category Group</th>
            <th class="border border-black">Allocation</th>
            <th class="border border-black">UACS Code</th>
            <th class="border border-black">Account Title</th>
            <th class="border border-black">Allocated Budget</th>
            <th class="border border-black">Programmed</th>
            <th class="border border-black"></th>
        </tr>
    </thead>
    <tbody>
        @forelse($fund_allocation as $item)
            @php
                $ppmp_group = $ppmp_details->where('category_group_id', $item->category_group_id);
                $ppmp_count = $ppmp_group->count();
                $first = true;
            @endphp
            @foreach($ppmp_group as $ppmp)
                <tr>
                    @if($first)
                        <td class="border border-black px-2" rowspan="{{ $ppmp_count }}">{{$item->categoryGroup?->name}}</td>
                        <td class="border border-black px-2" rowspan="{{ $ppmp_count }}">
                            <div class="flex justify-between">
                                <span>₱</span>
                                <span>{{number_format($item->total_allocated, 2)}}</span>
                            </div>
                        </td>
                        @php $first = false; @endphp
                    @endif
                    <td class="border border-black px-2">{{$ppmp->budget_uacs ?? $ppmp->uacs}}</td>
                    <td class="border border-black px-2">{{$ppmp->budget_name}}</td>
                    <td class="border border-black px-2">
                        <div class="flex justify-between">
                            <span>₱</span>
                            <span>{{number_format($ppmp->total_budget_per_uacs, 2)}}</span>
                        </div>
                    </td>
                    @if($loop->first)
                        <td class="border border-black px-2" rowspan="{{ $ppmp_count }}">
                            <div class="flex justify-between">
                                <span>₱</span>
                                <span>{{number_format($ppmp_group->sum('total_budget'), 2)}}</span>
                            </div>
                        </td>
                        <td class="border border-black px-2" rowspan="{{ $ppmp_count }}">
                            <div class="flex justify-between">
                                <span>₱</span>
                                <span>{{number_format($item->total_allocated - $ppmp_group->sum('total_budget'), 2)}}</span>
                            </div>
                        </td>
                    @endif
                </tr>
            @endforeach
        @empty
            <tr>
                <td class="border border-black text-center py-3 italic" colspan="7">No data available</td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
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
    </tfoot>
</table>
