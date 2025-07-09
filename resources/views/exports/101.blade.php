<table class="w-full mt-4">
    <thead>
        <tr>
            @if (
                $is_q1 &&
                    in_array($activeButton, [
                        'sksuPpmp',
                        'gasPpmp',
                        'hesPpmp',
                        'aesPpmp',
                        'rdPpmp',
                        'extensionPpmp',
                        'lfPpmp',
                        'sksuPpmp161',
                        'gasPpmp161',
                        'hesPpmp161',
                        'aesPpmp161',
                        'rdPpmp161',
                        'extensionPpmp161',
                        'lfPpmp161',
                    ]))
                <th colspan="4" class="border border-black bg-gray-300" style="font-weight: bold">Receipts</th>
            @else
                <th colspan="2" class="border border-black bg-gray-300" style="font-weight: bold">Receipts</th>
            @endif
            <th colspan="3" class="border border-black bg-gray-300" style="font-weight: bold">Expenditure</th>
            <th class="border border-black bg-gray-300" style="font-weight: bold">Balance</th>
            {{-- <th colspan="2" class="border border-black bg-gray-300">Corresponding Account Codes</th> --}}
        </tr>
    </thead>
    <tbody>
        <thead>
            <tr>
                <th width="40" class="border border-black">Category Group</th>
                <th width="20" class="border border-black">Allocation</th>
                @if (
                    $is_q1 &&
                        in_array($activeButton, [
                            'sksuPpmp',
                            'gasPpmp',
                            'hesPpmp',
                            'aesPpmp',
                            'rdPpmp',
                            'extensionPpmp',
                            'lfPpmp',
                            'sksuPpmp161',
                            'gasPpmp161',
                            'hesPpmp161',
                            'aesPpmp161',
                            'rdPpmp161',
                            'extensionPpmp161',
                            'lfPpmp161',
                        ]))
                    <th width="20" class="border border-black">Forwarded Balance</th>
                    <th width="20" class="border border-black">Total Allocation</th>
                @endif
                <th width="20" class="border border-black">UACS Code</th>
                <th width="40" class="border border-black">Account Title - Budget</th>
                <th width="20" class="border border-black">Programmed</th>
                <th width="20" class="border border-black"></th>
            </tr>
        </thead>
        @forelse($fund_allocation as $item)
            <tr>
                <td class="border border-black px-2">{{ $item->name }}</td>
                <td class="border border-black px-2">
                    @if (
                        $is_q1 &&
                            in_array($activeButton, [
                                'sksuPpmp',
                                'gasPpmp',
                                'hesPpmp',
                                'aesPpmp',
                                'rdPpmp',
                                'extensionPpmp',
                                'lfPpmp',
                                'sksuPpmp161',
                                'gasPpmp161',
                                'hesPpmp161',
                                'aesPpmp161',
                                'rdPpmp161',
                                'extensionPpmp161',
                                'lfPpmp161',
                            ]))
                        <div class="flex justify-between">
                            <span></span>
                            <span>{{ $item->is_supplemental ? number_format($item->total_allocated, 2) : '0.00' }}</span>
                        </div>
                    @else
                        <div class="flex justify-between">
                            <span></span>
                            <span>{{ number_format($item->total_allocated, 2) }}</span>
                        </div>
                    @endif
                </td>
                @if (
                    $is_q1 &&
                        in_array($activeButton, [
                            'sksuPpmp',
                            'gasPpmp',
                            'hesPpmp',
                            'aesPpmp',
                            'rdPpmp',
                            'extensionPpmp',
                            'lfPpmp',
                            'sksuPpmp161',
                            'gasPpmp161',
                            'hesPpmp161',
                            'aesPpmp161',
                            'rdPpmp161',
                            'extensionPpmp161',
                            'lfPpmp161',
                        ]))
                    <td class="border border-black px-2">
                        @if ($item->is_supplemental == 1)
                            <div class="flex justify-between">
                                <span> </span>
                                <span>{{ number_format(
                                    $non_supplemental_fund_allocation->where('category_group_id', $item->category_group_id)->sum('total_allocated') -
                                        $forwarded_ppmp_details->where('category_group_id', $item->category_group_id)->sum('total_budget'),
                                    2,
                                ) }}</span>
                            </div>
                        @else
                            <div class="flex justify-between">
                                <span> </span>
                                <span>{{ number_format(
                                    $item->total_allocated -
                                        $forwarded_ppmp_details->where('category_group_id', $item->category_group_id)->sum('total_budget'),
                                    2,
                                ) }}</span>
                            </div>
                        @endif
                    </td>
                    <td class="border border-black px-2">
                        @if ($item->is_supplemental == 1)
                            <div class="flex justify-between">
                                <span> </span>
                                <span>{{ number_format(
                                    $non_supplemental_fund_allocation->where('category_group_id', $item->category_group_id)->sum('total_allocated') -
                                        $forwarded_ppmp_details->where('category_group_id', $item->category_group_id)->sum('total_budget') +
                                        $item->total_allocated,
                                    2,
                                ) }}</span>
                            </div>
                        @else
                            <div class="flex justify-between">
                                <span> </span>
                                <span>{{ number_format(
                                    $item->total_allocated -
                                        $forwarded_ppmp_details->where('category_group_id', $item->category_group_id)->sum('total_budget'),
                                    2,
                                ) }}</span>
                            </div>
                        @endif
                    </td>
                @endif
                <td></td>
                <td></td>
                <td></td>
                <td class="border border-black px-2">
                    <div class="flex justify-between">
                        <span> </span>
                        <span>{{ number_format($ppmp_details->where('category_group_id', $item->category_group_id)->sum('total_budget'), 2) }}</span>
                    </div>
                </td>
                <td class="border border-black px-2">
                    @if (
                        $is_q1 &&
                            in_array($activeButton, [
                                'sksuPpmp',
                                'gasPpmp',
                                'hesPpmp',
                                'aesPpmp',
                                'rdPpmp',
                                'extensionPpmp',
                                'lfPpmp',
                                'sksuPpmp161',
                                'gasPpmp161',
                                'hesPpmp161',
                                'aesPpmp161',
                                'rdPpmp161',
                                'extensionPpmp161',
                                'lfPpmp161',
                            ]))
                        @if ($item->is_supplemental == 1)
                            <div class="flex justify-between">
                                <span></span>
                                <span>{{ number_format(
                                    $item->total_allocated - $ppmp_details->where('category_group_id', $item->category_group_id)->sum('total_budget'),
                                    2,
                                ) }}</span>
                            </div>
                        @else
                            <div class="flex justify-between">
                                <span></span>
                                <span>0.00</span>
                            </div>
                        @endif
                    @else
                        <div class="flex justify-between">
                            <span></span>
                            <span>{{ number_format(
                                $item->total_allocated - $ppmp_details->where('category_group_id', $item->category_group_id)->sum('total_budget'),
                                2,
                            ) }}</span>
                        </div>
                    @endif
                </td>
            </tr>
            @foreach ($ppmp_details->where('category_group_id', $item->category_group_id) as $ppmp)
                <tr>
                    <td></td>
                    <td></td>
                    @if (
                        $is_q1 &&
                            in_array($activeButton, [
                                'sksuPpmp',
                                'gasPpmp',
                                'hesPpmp',
                                'aesPpmp',
                                'rdPpmp',
                                'extensionPpmp',
                                'lfPpmp',
                                'sksuPpmp161',
                                'gasPpmp161',
                                'hesPpmp161',
                                'aesPpmp161',
                                'rdPpmp161',
                                'extensionPpmp161',
                                'lfPpmp161',
                            ]))
                        <td></td>
                        <td></td>
                    @endif
                    <td class="border border-black px-2">
                        {{ $ppmp->budget_uacs ?? $ppmp->uacs }}
                    </td>
                    <td class="border border-black px-2">
                        <span>{{ $ppmp->budget_name }}</span>
                    </td>
                    <td class="border border-black px-2">
                        <span> {{ number_format($ppmp->total_budget_per_uacs, 2) }}</span>
                    </td>
                    <td></td>
                    <td></td>

                </tr>
            @endforeach

        @empty
            <tr>
                <td class="border border-black text-center py-3 italic" colspan="3">No data
                    available</td>
            </tr>
        @endforelse
    </tbody>
    <tr>
        <td class="border border-black text-left font-semibold p-1" colspan="1">Grand Total</td>
        <td class="border border-black text-right font-semibold px-2">
            <div class="flex justify-between">
                <span></span>
                <span>{{ $total_allocated === null ? 0 : number_format($total_allocated, 2) }}</span>
            </div>
        </td>
        @if (
            $is_q1 &&
                in_array($activeButton, [
                    'sksuPpmp',
                    'gasPpmp',
                    'hesPpmp',
                    'aesPpmp',
                    'rdPpmp',
                    'extensionPpmp',
                    'lfPpmp',
                    'sksuPpmp161',
                    'gasPpmp161',
                    'hesPpmp161',
                    'aesPpmp161',
                    'rdPpmp161',
                    'extensionPpmp161',
                    'lfPpmp161',
                ]))
            <td class="border border-black text-left font-semibold p-1">
                <div class="flex justify-between">
                    <span> </span>
                    <span>
                        {{ $non_supplemental_fund_allocation->sum('total_allocated') > 0
                            ? number_format(
                                $non_supplemental_fund_allocation->sum('total_allocated') - $forwarded_ppmp_details->sum('total_budget'),
                                2,
                            )
                            : number_format($forwarded_ppmp_details->sum('total_budget'), 2) }}</span>
                </div>
            </td>
            <td class="border border-black text-left font-semibold p-1">
                <div class="flex justify-between">
                    <span></span>
                    <span>
                        {{ $non_supplemental_fund_allocation->sum('total_allocated') > 0
                            ? number_format(
                                $non_supplemental_fund_allocation->sum('total_allocated') -
                                    $forwarded_ppmp_details->sum('total_budget') +
                                    ($total_allocated ?? 0),
                                2,
                            )
                            : number_format($forwarded_ppmp_details->sum('total_budget') + ($total_allocated ?? 0), 2) }}</span>
                </div>
            </td>
        @endif
        <td class="border border-black text-left font-semibold p-1"></td>
        <td class="border border-black text-left font-semibold p-1"></td>
        <td class="border border-black text-left font-semibold p-1"></td>
        <td class="border border-black text-right font-semibold px-2">
            <div class="flex justify-between">
                <span></span>
                <span>{{ $total_programmed === null ? 0 : number_format($total_programmed->total_budget, 2) }}</span>
            </div>
        </td>
        <td class="border border-black text-right font-semibold px-2">
            @if (
                $is_q1 &&
                    in_array($activeButton, [
                        'sksuPpmp',
                        'gasPpmp',
                        'hesPpmp',
                        'aesPpmp',
                        'rdPpmp',
                        'extensionPpmp',
                        'lfPpmp',
                        'sksuPpmp161',
                        'gasPpmp161',
                        'hesPpmp161',
                        'aesPpmp161',
                        'rdPpmp161',
                        'extensionPpmp161',
                        'lfPpmp161',
                    ]))
                <div class="flex justify-between">
                    <span></span>
                    <span>{{ $non_supplemental_fund_allocation->sum('total_allocated') > 0
                        ? number_format(
                            $non_supplemental_fund_allocation->sum('total_allocated') -
                                $forwarded_ppmp_details->sum('total_budget') +
                                ($total_allocated ?? 0) +
                                $total_programmed->total_budget,
                            2,
                        )
                        : number_format(
                            $forwarded_ppmp_details->sum('total_budget') + ($total_allocated ?? 0) + $total_programmed->total_budget,
                            2,
                        ) }}</span>
                </div>
            @else
                <div class="flex justify-between">
                    <span></span>
                    <span>{{ $total_programmed === null ? 0 : number_format($balance, 2) }}</span>
                </div>
            @endif
        </td>
    </tr>
</table>
