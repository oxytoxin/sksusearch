@php
    $particulars = collect($this->data['particulars']);
    $refund_particulars = collect($this->data['refund_particulars']);
    $cheque_amount = $this->disbursement_voucher->total_amount;
    $ready = false;
    if ($this->disbursement_voucher?->travel_order_id) {
        $expenses = collect($this->data['itinerary_entries'])->sum('data.total_expenses');
    }
    try {
        Akaunting\Money\Money::PHP($particulars->sum('amount'));
        Akaunting\Money\Money::PHP($refund_particulars->sum('amount'));
        if ($this->disbursement_voucher) {
            $ready = true;
        }
    } catch (\Throwable $e) {
        $ready = false;
    }
@endphp


@if ($ready)
    <div class="grid grid-cols-2 gap-4">
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
        @if ($cheque_amount > $particulars->sum('amount'))
            <div>
                <h3>Refunded Amount</h3>
                <p>
                    {{ Akaunting\Money\Money::PHP($refund_particulars->sum('amount') ?? 0, true) }}
                </p>
            </div>
        @endif

    </div>

@endif
