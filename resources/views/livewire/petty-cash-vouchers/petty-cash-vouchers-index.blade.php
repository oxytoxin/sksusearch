<div class="space-y-4">
    <div class="flex">
        <h2 class="font-light capitalize text-primary-600">Petty Cash Vouchers / Petty Cash Vouchers List</h2>
    </div>
    <div class="flex gap-2">
        <div class="flex flex-1 p-4 bg-white rounded shadow justify-evenly">
            <div class="space-y-2">
                <h2>Petty Cash Fund</h2>
                <h3>Campus: {{ $petty_cash_fund->campus->name }}</h3>
            </div>
            <div class="space-y-2">
                <h3>Remaining Balance: <span
                          class="p-1 px-2 text-sm text-white rounded-full bg-primary-400">P{{ number_format($petty_cash_fund->latest_petty_cash_fund_record?->running_balance, 2) }}</span></h3>
                <h3>Voucher Limit: <span class="p-1 px-2 text-sm text-white rounded-full bg-primary-400">P{{ number_format($petty_cash_fund->voucher_limit, 2) }}</span></h3>
            </div>
        </div>
        <div>
            <a class="inline-block p-2 text-white rounded bg-primary-400" href="{{ route('pcv.create') }}">New Petty Cash Voucher Request</a>
        </div>
    </div>

    <div>
        {{ $this->table }}
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
