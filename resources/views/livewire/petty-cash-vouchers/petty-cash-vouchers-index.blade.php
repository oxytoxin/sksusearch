<div class="space-y-4">
    <h1>Petty Cash Vouchers</h1>
    <div>
        <h2>Petty Cash Fund</h2>
        <h3>Campus: {{ $petty_cash_fund->campus->name }}</h3>
        <h3>Remaining Balance: P{{ number_format($petty_cash_fund->latest_petty_cash_fund_record?->running_balance, 2) }}</h3>
        <h3>Voucher Limit: P{{ number_format($petty_cash_fund->voucher_limit, 2) }}</h3>
    </div>
    <div>
        <a class="inline-block p-2 text-white rounded bg-primary-400" href="{{ route('pcv.create') }}">New Petty Cash Voucher Request</a>
    </div>
    {{ $this->table }}
</div>
