<div>
    <h1>Tracking Number: {{ $dv->tracking_number }}</h1>
    <h3>Cheque Number: {{ $dv->cheque_number }}</h3>
    <h3>Payee: {{ $dv->payee }}</h3>
    <h3>Amount: P{{ number_format($dv->total_amount, 2) }}</h3>
</div>
