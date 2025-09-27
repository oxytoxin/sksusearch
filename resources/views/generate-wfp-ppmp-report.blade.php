    <table class="min-w-full">
        <thead class="bg-gray-400">
            <tr class="border-t border-gray-200">
                <th colspan="22" scope="colgroup"
                    class="bg-green-700 py-2 pl-4 pr-3 text-left text-sm font-semibold text-gray-50 sm:pl-3 h-10">
                </th>
            </tr>
        </thead>
        <thead class="bg-white">
            <tr>
                <th scope="col" class="px-1 py-1 text-left text-sm font-semibold text-gray-900">
                    UACS Code</th>
                <th scope="col" class="px-1 py-1 text-left text-sm font-semibold text-gray-900">
                    Account Title</th>
                <th scope="col" class="px-1 py-1 text-left text-sm font-semibold text-gray-900">
                    Particulars</th>
                <th scope="col" class="px-1 py-1 text-left text-sm font-semibold text-gray-900">
                    Supply Code</th>
                <th scope="col" class="px-1 py-1 text-left text-sm font-semibold text-gray-900">
                    Qty</th>
                <th scope="col" class="px-1 py-1 text-left text-sm font-semibold text-gray-900">
                    UOM</th>
                <th scope="col" class="px-1 py-1 text-right text-sm font-semibold text-gray-900">
                    Unit Cost (₱)</th>
                <th scope="col" class="px-1 py-1 text-right text-sm font-semibold text-gray-900">
                    Estimated Budget (₱)</th>
                <th scope="col"
                    class="px-1 py-1 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">
                    Jan</th>
                <th scope="col"
                    class="px-1 py-1 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">
                    Feb</th>
                <th scope="col"
                    class="px-1 py-1 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">
                    Mar</th>
                <th scope="col"
                    class="px-1 py-1 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">
                    Apr</th>
                <th scope="col"
                    class="px-1 py-1 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">
                    May</th>
                <th scope="col"
                    class="px-1 py-1 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">
                    Jun</th>
                <th scope="col"
                    class="px-1 py-1 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">
                    Jul</th>
                <th scope="col"
                    class="px-1 py-1 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">
                    Aug</th>
                <th scope="col"
                    class="px-1 py-1 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">
                    Sep</th>
                <th scope="col"
                    class="px-1 py-1 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">
                    Oct</th>
                <th scope="col"
                    class="px-1 py-1 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">
                    Nov</th>
                <th scope="col"
                    class="px-1 py-1 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-l border-gray-400">
                    Dec</th>
            </tr>
        </thead>
        <tbody class="bg-white">
            @php
                $supply_name = App\Models\BudgetCategory::where('id', 1)->first()->name;
            @endphp
            <tr class="border-t border-gray-200">
                <th colspan="21" scope="colgroup"
                    class="bg-yellow-100 py-2 pl-4 pr-3
                            text-left text-sm font-semibold text-gray-900 sm:pl-3">
                    {{ $supply_name }}</th>
            </tr>
            @forelse ($record->where('budget_category_id', 1) as $item)
                <tr class="border-t border-gray-300">
                    <td class="whitespace-nowrap py-2 pl-2 pr-3 text-sm font-medium text-gray-900 sm:pl-3">
                        {{ $item->uacs_code }}</td>
                    <td class="px-1 py-2 text-sm text-gray-500 text-wrap">
                        {{ $item->categoryItem->name }} {{ $item->budget_category_id }}</td>
                    <td class="px-1 py-2 text-sm text-gray-500 text-wrap">
                        {{ $item->supply->particulars }}</td>
                    <td class="px-1 py-2 text-sm text-gray-500 text-wrap">
                        {{ $item->supply->supply_code }}</td>
                    <td class="px-1 py-2 text-sm text-gray-500 text-wrap">
                        {{ $item->total_quantity }}</td>
                    <td class="whitespace-nowrap px-1 py-2 text-sm text-gray-500">
                        {{ $item->uom }}</td>
                    <td class="whitespace-nowrap px-1 py-2 text-sm text-right text-gray-500">
                        {{ number_format($item->cost_per_unit, 2) }}</td>
                    <td class="whitespace-nowrap px-1 py-2 text-sm text-right text-gray-500">
                        {{ number_format((float) ($item->cost_per_unit * $item->total_quantity), 2, '.', ',') }}
                    </td>
                    @foreach ($item->merged_quantities as $quantity)
                        <td
                            class="whitespace-nowrap px-1 py-2 text-sm text-center text-gray-500 border-l border-gray-400">
                            {{ $quantity }}</td>
                    @endforeach
                </tr>
            @empty
                <tr class="border-t border-gray-200">
                    <th colspan="21" scope="colgroup"
                        class="bg-gray-100 py-2 pl-4 pr-3 text-center text-sm font-semibold text-gray-900 sm:pl-3">
                        No Record</th>
                </tr>
            @endforelse
            @php
                $mooe_name = App\Models\BudgetCategory::where('id', 2)->first()->name;
            @endphp
            <tr class="border-t border-gray-200">
                <th colspan="21" scope="colgroup"
                    class="bg-yellow-100 py-2 pl-4 pr-3
                            text-left text-sm font-semibold text-gray-900 sm:pl-3">
                    {{ $mooe_name }}</th>
            </tr>
            @forelse ($record->where('budget_category_id', 2) as $item)
                <tr class="border-t border-gray-300">
                    <td class="whitespace-nowrap py-2 pl-2 pr-3 text-sm font-medium text-gray-900 sm:pl-3">
                        {{ $item->uacs_code }}</td>
                    <td class="px-1 py-2 text-sm text-gray-500 text-wrap">
                        {{ $item->categoryItem->name }} {{ $item->budget_category_id }}</td>
                    <td class="px-1 py-2 text-sm text-gray-500 text-wrap">
                        {{ $item->supply->particulars }}</td>
                    <td class="px-1 py-2 text-sm text-gray-500 text-wrap">
                        {{ $item->supply->supply_code }}</td>
                    <td class="px-1 py-2 text-sm text-gray-500 text-wrap">
                        {{ $item->total_quantity }}</td>
                    <td class="whitespace-nowrap px-1 py-2 text-sm text-gray-500">
                        {{ $item->uom }}</td>
                    <td class="whitespace-nowrap px-1 py-2 text-sm text-right text-gray-500">
                        {{ number_format($item->cost_per_unit, 2) }}</td>
                    <td class="whitespace-nowrap px-1 py-2 text-sm text-right text-gray-500">
                        {{ number_format((float) ($item->cost_per_unit * $item->total_quantity), 2, '.', ',') }}
                    </td>
                    @foreach ($item->merged_quantities as $quantity)
                        <td
                            class="whitespace-nowrap px-1 py-2 text-sm text-center text-gray-500 border-l border-gray-400">
                            {{ $quantity }}</td>
                    @endforeach
                </tr>
            @empty

                <tr class="border-t border-gray-200">
                    <th colspan="21" scope="colgroup"
                        class="bg-gray-100 py-2 pl-4 pr-3 text-center text-sm font-semibold text-gray-900 sm:pl-3">
                        No Record</th>
                </tr>
            @endforelse
            @php
                $training_name = App\Models\BudgetCategory::where('id', 3)->first()->name;
            @endphp
            <tr class="border-t border-gray-200">
                <th colspan="21" scope="colgroup"
                    class="bg-yellow-100 py-2 pl-4 pr-3
                            text-left text-sm font-semibold text-gray-900 sm:pl-3">
                    {{ $training_name }}</th>
            </tr>
            @forelse ($record->where('budget_category_id', 3) as $item)
                <tr class="border-t border-gray-300">
                    <td class="whitespace-nowrap py-2 pl-2 pr-3 text-sm font-medium text-gray-900 sm:pl-3">
                        {{ $item->uacs_code }}</td>
                    <td class="px-1 py-2 text-sm text-gray-500 text-wrap">
                        {{ $item->categoryItem->name }} </td>
                    <td class="px-1 py-2 text-sm text-gray-500 text-wrap">
                        {{ $item->supply->particulars }}</td>
                    <td class="px-1 py-2 text-sm text-gray-500 text-wrap">
                        {{ $item->supply->supply_code }}</td>
                    <td class="px-1 py-2 text-sm text-gray-500 text-wrap">
                        {{ $item->total_quantity }}</td>
                    <td class="px-1 py-2 text-sm text-gray-500">{{ $item->uom }}</td>
                    <td class="whitespace-nowrap px-1 py-2 text-sm text-right text-gray-500">
                        {{ number_format($item->cost_per_unit, 2) }}</td>
                    <td class="whitespace-nowrap px-1 py-2 text-sm text-right text-gray-500">
                        {{ number_format((float) ($item->cost_per_unit * $item->total_quantity), 2, '.', ',') }}
                    </td>
                    @foreach ($item->merged_quantities as $quantity)
                        <td
                            class="whitespace-nowrap px-1 py-2 text-sm text-center text-gray-500 border-l border-gray-400">
                            {{ $quantity }}</td>
                    @endforeach
                </tr>
            @empty
                <tr class="border-t border-gray-200">
                    <th colspan="21" scope="colgroup"
                        class="bg-gray-100 py-2 pl-4 pr-3 text-center text-sm font-semibold text-gray-900 sm:pl-3">
                        No Record</th>
                </tr>
            @endforelse
            @php
                $machine_name = App\Models\BudgetCategory::where('id', 4)->first()->name;
            @endphp
            <tr class="border-t border-gray-200">
                <th colspan="21" scope="colgroup"
                    class="bg-yellow-100 py-2 pl-4 pr-3
                            text-left text-sm font-semibold text-gray-900 sm:pl-3">
                    {{ $machine_name }}</th>
            </tr>
            @forelse ($record->where('budget_category_id', 4) as $item)
                <tr class="border-t border-gray-300">
                    <td class="whitespace-nowrap py-2 pl-2 pr-3 text-sm font-medium text-gray-900 sm:pl-3">
                        {{ $item->uacs_code }}</td>
                    <td class="px-1 py-2 text-sm text-gray-500 text-wrap">
                        {{ $item->categoryItem->name }} {{ $item->budget_category_id }}</td>
                    <td class="px-1 py-2 text-sm text-gray-500 text-wrap">
                        {{ $item->supply->particulars }}</td>
                    <td class="px-1 py-2 text-sm text-gray-500 text-wrap">
                        {{ $item->supply->supply_code }}</td>
                    <td class="px-1 py-2 text-sm text-gray-500 text-wrap">
                        {{ $item->total_quantity }}</td>
                    <td class="whitespace-nowrap px-1 py-2 text-sm text-gray-500">
                        {{ $item->uom }}</td>
                    <td class="whitespace-nowrap px-1 py-2 text-sm text-right text-gray-500">
                        {{ number_format($item->cost_per_unit, 2) }}</td>
                    <td class="whitespace-nowrap px-1 py-2 text-sm text-right text-gray-500">
                        {{ number_format((float) ($item->cost_per_unit * $item->total_quantity), 2, '.', ',') }}
                    </td>
                    @foreach ($item->merged_quantities as $quantity)
                        <td
                            class="whitespace-nowrap px-1 py-2 text-sm text-center text-gray-500 border-l border-gray-400">
                            {{ $quantity }}</td>
                    @endforeach
                </tr>
            @empty
                <tr class="border-t border-gray-200">
                    <th colspan="21" scope="colgroup"
                        class="bg-gray-100 py-2 pl-4 pr-3 text-center text-sm font-semibold text-gray-900 sm:pl-3">
                        No Record</th>
                </tr>
            @endforelse
            @php
                $building_name = App\Models\BudgetCategory::where('id', 5)->first()->name;
            @endphp
            <tr class="border-t border-gray-200">
                <th colspan="21" scope="colgroup"
                    class="bg-yellow-100 py-2 pl-4 pr-3
                            text-left text-sm font-semibold text-gray-900 sm:pl-3">
                    {{ $building_name }}</th>
            </tr>
            @forelse ($record->where('budget_category_id', 5) as $item)
                <tr class="border-t border-gray-300">
                    <td class="whitespace-nowrap py-2 pl-2 pr-3 text-sm font-medium text-gray-900 sm:pl-3">
                        {{ $item->uacs_code }}</td>
                    <td class="px-1 py-2 text-sm text-gray-500 text-wrap">
                        {{ $item->categoryItem->name }} {{ $item->budget_category_id }}</td>
                    <td class="px-1 py-2 text-sm text-gray-500 text-wrap">
                        {{ $item->supply->particulars }}</td>
                    <td class="px-1 py-2 text-sm text-gray-500 text-wrap">
                        {{ $item->supply->supply_code }}</td>
                    <td class="px-1 py-2 text-sm text-gray-500 text-wrap">
                        {{ $item->total_quantity }}</td>
                    <td class="whitespace-nowrap px-1 py-2 text-sm text-gray-500">
                        {{ $item->uom }}</td>
                    <td class="whitespace-nowrap px-1 py-2 text-sm text-right text-gray-500">
                        {{ number_format($item->cost_per_unit, 2) }}</td>
                    <td class="whitespace-nowrap px-1 py-2 text-sm text-right text-gray-500">
                        {{ number_format((float) ($item->cost_per_unit * $item->total_quantity), 2, '.', ',') }}
                    </td>
                    @foreach ($item->merged_quantities as $quantity)
                        <td
                            class="whitespace-nowrap px-1 py-2 text-sm text-center text-gray-500 border-l border-gray-400">
                            {{ $quantity }}</td>
                    @endforeach
                </tr>
            @empty
                <tr class="border-t border-gray-200">
                    <th colspan="21" scope="colgroup"
                        class="bg-gray-100 py-2 pl-4 pr-3 text-center text-sm font-semibold text-gray-900 sm:pl-3">
                        No Record</th>
                </tr>
            @endforelse
            @php
                $ps_name = App\Models\BudgetCategory::where('id', 6)->first()->name;
            @endphp
            <tr class="border-t border-gray-200">
                <th colspan="21" scope="colgroup"
                    class="bg-yellow-100 py-2 pl-4 pr-3
                            text-left text-sm font-semibold text-gray-900 sm:pl-3">
                    {{ $ps_name }}</th>
            </tr>
            @forelse ($record->where('budget_category_id', 6) as $item)
                <tr class="border-t border-gray-300">
                    <td class="whitespace-nowrap py-2 pl-2 pr-3 text-sm font-medium text-gray-900 sm:pl-3">
                        {{ $item->uacs_code }}</td>
                    <td class="px-1 py-2 text-sm text-gray-500 text-wrap">
                        {{ $item->categoryItem->name }} {{ $item->budget_category_id }}</td>
                    <td class="px-1 py-2 text-sm text-gray-500 text-wrap">
                        {{ $item->supply->particulars }}</td>
                    <td class="px-1 py-2 text-sm text-gray-500 text-wrap">
                        {{ $item->supply->supply_code }}</td>
                    <td class="px-1 py-2 text-sm text-gray-500 text-wrap">
                        {{ $item->total_quantity }}</td>
                    <td class="px-1 py-2 text-sm text-gray-500">{{ $item->uom }}</td>
                    <td class="whitespace-nowrap px-1 py-2 text-sm text-right text-gray-500">
                        {{ number_format($item->cost_per_unit, 2) }}</td>
                    <td class="whitespace-nowrap px-1 py-2 text-sm text-right text-gray-500">
                        {{ number_format((float) ($item->cost_per_unit * $item->total_quantity), 2, '.', ',') }}
                    </td>
                    @foreach ($item->merged_quantities as $quantity)
                        <td
                            class="whitespace-nowrap px-1 py-2 text-sm text-center text-gray-500 border-l border-gray-400">
                            {{ $quantity }}</td>
                    @endforeach
                </tr>
            @empty
                <tr class="border-t border-gray-200">
                    <th colspan="21" scope="colgroup"
                        class="bg-gray-100 py-2 pl-4 pr-3 text-center text-sm font-semibold text-gray-900 sm:pl-3">
                        No Record</th>
                </tr>
            @endforelse
        </tbody>
    </table>
