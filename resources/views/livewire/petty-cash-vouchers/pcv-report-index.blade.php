<div x-data>
    <div class="flex">
        <h2 class="font-light capitalize text-primary-600">Petty Cash Vouchers / Report on Paid Petty Cash Vouchers</h2>
    </div>
    <div class="p-4 mt-4 bg-white rounded shadow">
        {{ $this->form }}
        <div class="flex justify-end mt-4">
            <x-filament::button @click="printOut($refs.printContainer.outerHTML);">Print</x-filament::button>
        </div>
    </div>
    <div class="p-4 mt-4 bg-white" style="font-family: 'Times New Roman', Times, serif" x-ref="printContainer">
        <h1 class="text-lg font-semibold text-center">Report On Paid Petty Cash Vouchers</h1>
        <h3 class="text-sm font-bold text-center">Period Covered: {{ date_format(date_create($data['date_from']), 'm/d/Y') }} - {{ date_format(date_create($data['date_to']), 'm/d/Y') }}</h3>
        <div class="flex justify-between mt-8 text-sm font-bold">
            <div class="min-w-[16rem]">
                <h3>Entity Name: {{ $data['entity_name'] }}</h3>
                <h3>Fund Cluster: {{ $fund_clusters->find($data['fund_cluster_id'])?->name }}</h3>
            </div>
            <div class="min-w-[16rem]">
                <h3>Report No: {{ $data['report_no'] }}</h3>
                <h3>Sheet No: {{ $data['sheet_no'] }}</h3>
            </div>
        </div>
        <div class="mt-4">
            <table class="w-full text-sm table-auto print:text-xs">
                <thead class="">
                    <tr class="border border-collapse border-black divide-x divide-black">
                        <th class="px-2 text-left">Date</th>
                        <th class="px-2 text-left">Petty Cash Voucher No.</th>
                        <th class="px-2 ">Particulars</th>
                        <th class="px-2 ">Amount</th>
                    </tr>
                </thead>
                <tbody class="">
                    @forelse ($petty_cash_vouchers as $voucher)
                        <tr class="border border-collapse border-black divide-x divide-black">
                            <td class="px-2">{{ date_format(date_create($voucher->pcv_date), 'm/d/y') }}</td>
                            <td class="px-2">{{ $voucher->pcv_number }}</td>
                            <td class="px-2 text-center">
                                @foreach ($voucher->particulars as $particular)
                                    {{ $particular['name'] }}<br>
                                @endforeach
                            </td>
                            <td class="px-2 text-right">P{{ number_format($voucher->amount_paid, 2) }}</td>
                        </tr>
                    @empty
                        <tr class="border border-collapse border-black divide-x divide-black">
                            <td class="px-2 max-w-[4rem] text-center" colspan="4">No records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4 text-center break-inside-avoid">
                <h4 class="text-xl font-bold">CERTIFICATION</h4>
                <p>I hereby certify to the correctness of the above information.</p>
                <div class="flex justify-center mt-16 space-x-12">
                    <div class="flex flex-col items-center">
                        <h4 class="px-4 border-b border-black">{{ $petty_cash_fund->custodian?->employee_information?->full_name }}</h4>
                        <h5 class="text-xs">Petty Cash Custodian</h5>
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
