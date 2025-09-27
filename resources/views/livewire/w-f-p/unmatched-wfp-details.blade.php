<div class="space-y-2">
     <div class="flex justify-between items-center">
        <h2 class="font-light capitalize text-primary-600">Unmatched WFP Details</h2>
        <div class="flex space-x-2">
        <a href="{{ route('wfp.wfp-submissions', 1) }}"
            class="hover:bg-primary-500 p-2 bg-primary-600 rounded-md font-light capitalize text-white text-sm">Back</a>
        <button
            x-on:confirm="{
                title : 'Are you sure you want to update these WFP details?',
                icon: 'warning',
                method: 'updateWfpDetails'
                }"
            class="hover:bg-blue-500 p-2 bg-blue-600 rounded-md font-light capitalize text-white text-sm">Update</button>
        </div>
    </div>
    <div>
    <div class="bg-white rounded-md border overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-gray-700">
                <tr class="text-left">
                    <th class="px-4 py-2 w-12">#</th>
                    <th class="px-4 py-2">Supply (Particulars)</th>
                    <th class="px-4 py-2">Supply Code</th>
                    <th class="px-4 py-2">Category Item</th>
                    <th class="px-4 py-2">UACS Code</th>
                    <th class="px-4 py-2">WFP Budget Category</th>
                    <th class="px-4 py-2">Actual Budget Category</th>
                    <th class="px-4 py-2">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($details as $i => $row)
                    @php
                        $mismatch = trim((string)$row->wfp_budget_category_name) !== trim((string)$row->actual_budget_category_name);
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 text-gray-500">{{ $i + 1 }}</td>
                        <td class="px-4 py-2 font-medium text-gray-900">
                            {{ $row->particulars ?? '—' }}
                        </td>
                        <td class="px-4 py-2 text-gray-700">
                            {{ $row->supply_code ?? '—' }}
                        </td>
                        <td class="px-4 py-2">
                            <div class="text-gray-900">{{ $row->category_item_name ?? '—' }}</div>
                        </td>
                        <td class="px-4 py-2 text-gray-700">
                            {{ $row->uacs_code ?? '—' }}
                        </td>
                        <td class="px-4 py-2">
                            <span class="inline-flex items-center px-2 py-0.5 text-xs
                                {{ $mismatch ? ' text-red-700' : ' text-emerald-700' }}">
                                {{ $row->wfp_budget_category_name ?? '—' }}
                            </span>
                        </td>
                        <td class="px-4 py-2">
                            <span class="inline-flex items-center px-2 py-0.5 text-xs  text-blue-700">
                                {{ $row->actual_budget_category_name ?? '—' }}
                            </span>
                        </td>
                        <td class="px-4 py-2">
                            @if($mismatch)
                                <span class="inline-flex items-center gap-1 text-xs font-medium text-red-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86l-7.4 12.84A2 2 0 004.53 20h14.94a2 2 0 001.64-3.3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                                    Mismatch
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-xs font-medium text-emerald-700">
                                    Match
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center text-gray-500">
                            No unmatched WFP Details 🎉
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    </div>
</div>
