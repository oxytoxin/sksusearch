<div x-data>
    <div class="flex">
        <h2 class="font-light capitalize text-primary-600">Petty Cash Vouchers / Petty Cash Fund Record</h2>
    </div>
    <div class="p-4 mt-4 bg-white rounded shadow">
        {{ $this->form }}
        <div class="flex justify-end mt-4">
            <x-filament::button @click="printOut($refs.printContainer.outerHTML);">Print</x-filament::button>
        </div>
    </div>
    <div class="p-4 mt-4 bg-white" style="font-family: 'Times New Roman', Times, serif" x-ref="printContainer">
        <h1 class="text-lg font-semibold text-center">PETTY CASH FUND RECORD</h1>
        <div class="mt-8 font-bold">
            <h3>Entity Name: {{ $data['entity_name'] }}</h3>
            <h3>Fund Cluster: {{ $fund_clusters->find($data['fund_cluster_id'])?->name }}</h3>
        </div>
        <div class="flex mt-4 border-t border-l border-r border-black divide-x divide-black">
            <div class="flex-1 px-8 pt-4 text-center">
                <div class="min-w-[8rem] flex items-end justify-center text-sm border-b whitespace-nowrap border-black min-h-[2rem]">
                    {{ $petty_cash_fund->custodian?->employee_information?->full_name }}
                </div>
                <p class="text-xs">Petty Cash Fund Custodian</p>
            </div>
            <div class="flex-1 px-8 pt-4 text-center">
                <div class="min-w-[8rem] flex items-end justify-center text-sm border-b whitespace-nowrap border-black min-h-[2rem]">
                    <p>{{ $petty_cash_fund->custodian?->employee_information?->designation }}</p>
                </div>
                <p class="text-xs">Official Designation</p>
            </div>
            <div class="flex-1 px-8 pt-4 text-center ">
                <div class="min-w-[8rem] text-sm border-b flex items-end justify-center border-black min-h-[2rem]">
                </div>
                <p class="text-xs">Station</p>
            </div>
        </div>
        <div>
            <table class="w-full text-sm table-auto print:text-xs">
                <thead class="">
                    <tr class="border border-collapse border-black divide-x divide-black">
                        <th class="px-2">Date</th>
                        <th class="px-2">Reference / Check / PCV No.</th>
                        <th class="px-2">Payee</th>
                        <th class="px-2">Nature of Payment</th>
                        <th class="px-2">Cash Advance / Replenishments Received</th>
                        <th class="px-2">Disbursements</th>
                        <th class="px-2">Cash Advance Balance</th>
                    </tr>
                </thead>
                <tbody class="">
                    @forelse ($petty_cash_fund_records as $record)
                        @php
                            $isPcv = $record->recordable_type == App\Models\PettyCashVoucher::class;
                        @endphp
                        <tr class="border border-collapse border-black divide-x divide-black">
                            <td class="px-2 text-left whitespace-nowrap">{{ $record->created_at->format('m/d/Y') }}</td>
                            <td class="px-2 text-left whitespace-nowrap">
                                {{ $record->type == App\Models\PettyCashFundRecord::REPLENISHMENT ? $record->recordable->cheque_number : $record->recordable->pcv_number }}</td>
                            <td class="px-2 text-left whitespace-nowrap">{{ $record->recordable->payee }}</td>
                            <td class="px-2 text-left">{{ $record->nature_of_payment }}</td>
                            <td class="px-2 text-right">
                                {{ in_array($record->type, [App\Models\PettyCashFundRecord::REPLENISHMENT, App\Models\PettyCashFundRecord::REFUND]) ? number_format($record->amount, 2) : '' }}</td>
                            <td class="px-2 text-right">
                                {{ in_array($record->type, [App\Models\PettyCashFundRecord::DISBURSEMENT, App\Models\PettyCashFundRecord::REIMBURSEMENT]) ? number_format($record->amount, 2) : '' }}
                            </td>
                            <td class="px-2 text-right">{{ number_format($record->running_balance, 2) }}</td>
                        </tr>
                    @empty
                        <tr class="border border-collapse border-black divide-x divide-black">
                            <td class="px-2 text-center" colspan="7">No records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="max-w-3xl mx-auto mt-4 text-center break-inside-avoid">
                <h4 class="text-xl font-bold">CERTIFICATION</h4>
                <p>I hereby certify that the foregoing is a correct and complete record of all cash advances received and disbursements made by me in my capacity as Petty Cash Fund Custodian of
                    <span class="px-4 border-b border-black">{{ $petty_cash_fund->campus->name }} Campus</span> during the period from
                    <span class="px-4 border-b border-black">{{ date_format(date_create($data['date_from']), 'm/d/Y') }}</span> to <span class="px-4 border-b border-black">{{ date_format(date_create($data['date_to']), 'm/d/Y') }}</span>, inclusive,
                    as indicated in the corresponding columns.
                </p>
                <div class="flex flex-col items-center mt-16 space-y-8">
                    <div class="flex flex-col items-center">
                        <h4 class="px-4 border-b border-black">{{ $petty_cash_fund->custodian?->employee_information?->full_name }}</h4>
                        <h5 class="text-xs">Custodian</h5>
                    </div>
                    <div class="flex flex-col items-center">
                        <h4 class="px-4 border-b border-black">{{ today()->format('F j, Y') }}</h4>
                        <h5 class="text-xs">Date</h5>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script>
        function printOut(data) {
            var mywindow = window.open('', 'Report On Paid Petty Cash Vouchers', 'height=1000,width=1000');
            mywindow.document.write('<html><head>');
            mywindow.document.write('<title>Report On Paid Petty Cash Vouchers</title>');
            mywindow.document.write(`<link rel="stylesheet" href="{{ Vite::asset('resources/css/app.css') }}" />`);
            mywindow.document.write('</head><body >');
            mywindow.document.write(data);
            mywindow.document.write('</body></html>');

            mywindow.document.close();
            mywindow.focus();
            setTimeout(() => {
                mywindow.print();
            }, 1000);
            return true;
        }
    </script>
</div>
