<div class="p-4">
    {{-- ON-SCREEN TOOLBAR --}}
    <div class="print:hidden">
        <div class="flex flex-wrap items-start justify-between gap-4 mb-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Aging Schedule of Unliquidated Cash Advances</h1>
                <p class="text-sm text-gray-500">
                    COA-style report of cash advances past their liquidation deadline.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <button type="button"
                    onclick="printOutData(document.getElementById('agingPrint').outerHTML, 'Aging of Cash Advances')"
                    class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-500 rounded shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4H7v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Print
                </button>

                <button type="button" wire:click="exportExcel"
                    class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-primary-700 bg-white border border-primary-600 hover:bg-primary-50 rounded shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4"/>
                    </svg>
                    Export Excel
                </button>

                <button type="button" onclick="window.history.back()"
                    class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded shadow-sm">
                    Back
                </button>
            </div>
        </div>

        {{-- Filter row --}}
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 mb-4">
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1">As of date</label>
                <input type="date" wire:model="asOfDate"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm" />
            </div>

            <div class="md:col-span-3">
                <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1">Fund cluster</label>
                <select wire:model="fundClusterId"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                    <option value="">All funds</option>
                    @foreach ($fundClusters as $fc)
                        <option value="{{ $fc->id }}">{{ $fc->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-4">
                <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1">Aging bucket</label>
                <div class="inline-flex flex-wrap gap-1 rounded-md border border-gray-200 p-1 bg-white">
                    @php
                        $buckets = [
                            null      => 'All',
                            '30'      => '30',
                            '31-90'   => '31–90',
                            '91-365'  => '91–365',
                            '1-2y'    => '>1y',
                            '2y+'     => '>2y',
                        ];
                    @endphp
                    @foreach ($buckets as $key => $label)
                        <button type="button"
                            wire:click="setBucket(@js($key))"
                            class="px-3 py-1.5 text-xs font-semibold rounded
                                {{ $bucketFilter === $key
                                    ? 'bg-primary-600 text-white shadow'
                                    : 'text-gray-700 hover:bg-primary-50' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="md:col-span-3">
                <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1">Search</label>
                <input type="text" wire:model.debounce.400ms="search" placeholder="DV no, name, office…"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm" />
            </div>
        </div>

        {{-- Summary cards --}}
        <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-6">
            @php
                $cardLabels = [
                    '30'     => '30 days',
                    '31-90'  => '31 – 90 days',
                    '91-365' => '91 – 365 days',
                    '1-2y'   => 'Over 1 year',
                    '2y+'    => 'Over 2 years',
                ];
                $cardShades = [
                    '30'     => 'border-primary-300 bg-primary-50 text-primary-800',
                    '31-90'  => 'border-primary-400 bg-primary-100 text-primary-800',
                    '91-365' => 'border-primary-500 bg-primary-200 text-primary-900',
                    '1-2y'   => 'border-primary-600 bg-primary-300 text-primary-900',
                    '2y+'    => 'border-primary-700 bg-primary-500 text-white',
                ];
            @endphp
            @foreach ($cardLabels as $key => $label)
                <button type="button" wire:click="setBucket(@js($key))"
                    class="text-left rounded-lg border-2 p-3 transition
                        {{ $cardShades[$key] }}
                        {{ $bucketFilter === $key ? 'ring-2 ring-offset-2 ring-primary-600' : '' }}">
                    <p class="text-[11px] uppercase tracking-wide font-semibold opacity-80">{{ $label }}</p>
                    <p class="text-2xl font-bold mt-1">{{ $bucketCounts[$key]['count'] ?? 0 }}</p>
                    <p class="text-xs mt-1">₱ {{ number_format($bucketCounts[$key]['total'] ?? 0, 2) }}</p>
                </button>
            @endforeach
        </div>
    </div>

    {{-- Cap warning (only when rendered set was capped) --}}
    @if ($totalMatchingCount > $rowLimit)
        <div class="print:hidden mb-3 px-3 py-2 rounded border-l-4 border-amber-500 bg-amber-50 text-amber-800 text-sm">
            Showing the first <strong>{{ number_format($rowLimit) }}</strong> of
            <strong>{{ number_format($totalMatchingCount) }}</strong> matching records.
            Narrow your filters (bucket / search / as-of date) or use
            <strong>Export Excel</strong> to get the full list.
        </div>
    @endif

    {{-- PRINTABLE COA REPORT --}}
    <div id="agingPrint" class="bg-white text-gray-800">
        <style>
            @page { size: A4 landscape; margin: 8mm; }
            @media print {
                html, body { margin: 0 !important; padding: 0 !important; }
                #agingPrint { width: 100% !important; padding: 0 !important; }
            }
            #agingPrint table { border-collapse: collapse; width: 100%; font-size: 9pt; }
            #agingPrint th, #agingPrint td {
                border: 1px solid #555;
                padding: 4px 6px;
                vertical-align: top;
            }
            #agingPrint thead th {
                background-color: #e6f4e6;
                color: #0c6600;
                font-weight: 700;
                text-align: center;
                font-size: 8.5pt;
                text-transform: uppercase;
            }
            #agingPrint tfoot td {
                font-weight: 700;
                background-color: #f3faf3;
            }
            #agingPrint .num { text-align: right; font-variant-numeric: tabular-nums; }
            #agingPrint .center { text-align: center; }
            #agingPrint .muted { color: #777; }
        </style>

        {{-- Letterhead --}}
        <div class="px-2 pt-2 pb-3 border-b-2" style="border-color:#0c6600;">
            <div class="flex items-center justify-center gap-3">
                <img src="{{ asset('images/bagong-pilipinas-logo.png') }}" alt="" style="width:60px;height:60px;object-fit:contain;">
                <img src="{{ asset('images/sksulogo.png') }}" alt="" style="width:60px;height:60px;object-fit:contain;">
                <div class="leading-tight text-center">
                    <p class="text-xs font-bold uppercase text-gray-700">Republic of the Philippines</p>
                    <p class="text-base font-bold uppercase" style="color:#0c6600;">Sultan Kudarat State University</p>
                    <p class="text-xs text-gray-700">EJC Montilla, City of Tacurong, 9800</p>
                    <p class="text-xs text-gray-700">Province of Sultan Kudarat</p>
                </div>
            </div>
        </div>

        {{-- Title --}}
        <div class="text-center my-4">
            <p class="text-base font-bold uppercase tracking-wide" style="color:#0c6600;">
                Aging Schedule of Unliquidated Cash Advances
            </p>
            <p class="text-sm text-gray-700">As of {{ $asOfDisplay }}</p>
        </div>

        {{-- Table --}}
        <table>
            <thead>
                <tr>
                    <th rowspan="2" style="width:3%;">#</th>
                    <th rowspan="2" style="width:13%;">Name of Accountable Officer</th>
                    <th rowspan="2" style="width:10%;">Position</th>
                    <th rowspan="2" style="width:10%;">Office</th>
                    <th rowspan="2" style="width:14%;">Purpose</th>
                    <th rowspan="2" style="width:7%;">Date Granted</th>
                    <th rowspan="2" style="width:7%;">Check No.</th>
                    <th rowspan="2" style="width:8%;">Amount Granted</th>
                    <th rowspan="2" style="width:5%;">Days Overdue</th>
                    <th colspan="5">Past Due (Amount)</th>
                </tr>
                <tr>
                    <th style="width:5%;">30</th>
                    <th style="width:5%;">31–90</th>
                    <th style="width:6%;">91–365</th>
                    <th style="width:6%;">&gt; 1 Yr</th>
                    <th style="width:6%;">&gt; 2 Yrs</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rows as $i => $step)
                    @php
                        $dv       = $step->disbursementVoucher;
                        $owner    = $dv?->user;
                        $info     = $owner?->employee_information;
                        $position = $info?->position?->description ?? '';
                        $office   = $info?->office?->name ?? '';
                        $first    = $dv?->disbursement_voucher_particulars?->first();
                        $purpose  = $first?->purpose ?? ($dv?->payee ?? '');
                        $amount   = (float) ($dv?->totalSum ?? 0);
                        $days     = (int) ($step->days_overdue ?? 0);
                        $bucket   = $this->bucketFor($days);
                    @endphp
                    <tr>
                        <td class="center">{{ $i + 1 }}</td>
                        <td>{{ $owner?->name ?? '—' }}</td>
                        <td>{{ $position }}</td>
                        <td>{{ $office }}</td>
                        <td>{{ $purpose }}</td>
                        <td class="center">
                            {{ $dv?->created_at ? \Carbon\Carbon::parse($dv->created_at)->format('M d, Y') : '' }}
                        </td>
                        <td class="center">{{ $dv?->cheque_number ?? '' }}</td>
                        <td class="num">{{ number_format($amount, 2) }}</td>
                        <td class="num">{{ $days }}</td>
                        <td class="num">{{ $bucket === '30'     ? number_format($amount, 2) : '' }}</td>
                        <td class="num">{{ $bucket === '31-90'  ? number_format($amount, 2) : '' }}</td>
                        <td class="num">{{ $bucket === '91-365' ? number_format($amount, 2) : '' }}</td>
                        <td class="num">{{ $bucket === '1-2y'   ? number_format($amount, 2) : '' }}</td>
                        <td class="num">{{ $bucket === '2y+'    ? number_format($amount, 2) : '' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="14" class="center muted" style="padding:14px;">
                            No unliquidated cash advances as of {{ $asOfDisplay }}.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            @if ($rows->count())
                <tfoot>
                    <tr>
                        <td colspan="7" class="center">GRAND TOTAL</td>
                        <td class="num">{{ number_format($grandTotal, 2) }}</td>
                        <td></td>
                        <td class="num">{{ number_format($bucketTotals['30']     ?? 0, 2) }}</td>
                        <td class="num">{{ number_format($bucketTotals['31-90']  ?? 0, 2) }}</td>
                        <td class="num">{{ number_format($bucketTotals['91-365'] ?? 0, 2) }}</td>
                        <td class="num">{{ number_format($bucketTotals['1-2y']   ?? 0, 2) }}</td>
                        <td class="num">{{ number_format($bucketTotals['2y+']    ?? 0, 2) }}</td>
                    </tr>
                </tfoot>
            @endif
        </table>

    </div>
</div>
