@php
    $particulars = collect($this->data['particulars']);
    $cheque_amount = $this->disbursement_voucher->total_suggested_amount > 0 ? $this->disbursement_voucher->total_suggested_amount : $this->disbursement_voucher->total_amount;
    $ready = false;
    try {
        Akaunting\Money\Money::PHP(collect($this->data['particulars'])->sum('amount'));
        if ($this->disbursement_voucher) {
            $ready = true;
        }
    } catch (\Throwable $e) {
        $ready = false;
    }
@endphp


@if ($ready)
    <div class="grid grid-cols-2">
        <div>
            <h3>Cheque/ADA Amount</h3>
            <p>
                {{ Akaunting\Money\Money::PHP($cheque_amount, true) }}
            </p>
        </div>
        <div>
            <h3>Liquidated Amount</h3>
            <p>
                {{ Akaunting\Money\Money::PHP($particulars->sum('amount') ?? 0, true) }}
            </p>
        </div>
        <div>
            @if ($cheque_amount < $particulars->sum('amount'))
                <h4>Amount to be Reimbursed</h4>
                <p>
                    {{ Akaunting\Money\Money::PHP($particulars->sum('amount') - $cheque_amount, true) }}
                </p>
            @elseif ($cheque_amount > $particulars->sum('amount'))
                <h4>Amount to be Refunded</h4>
                <p>
                    {{ Akaunting\Money\Money::PHP($cheque_amount - $particulars->sum('amount'), true) }}
                </p>
            @endif
        </div>
    </div>

@endif
