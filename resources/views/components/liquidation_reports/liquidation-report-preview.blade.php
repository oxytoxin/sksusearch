@php
    $ready = false;
    $to_reimburse = 0;
    try {
        Akaunting\Money\Money::PHP(collect($this->data['particulars'])->sum('amount'));
        Akaunting\Money\Money::PHP(collect($this->data['refund_particulars'])->sum('amount'));
        foreach ($this->data['particulars'] as $particular) {
            Akaunting\Money\Money::PHP($particular['amount'] ?? 0, true);
        }
        foreach ($this->data['refund_particulars'] as $particular) {
            Akaunting\Money\Money::PHP($refund_particular['amount'] ?? 0, true);
        }
        $to_reimburse = collect($this->data['particulars'])->sum('amount') - $this->disbursement_voucher?->total_amount;
        if ($this->disbursement_voucher) {
            $ready = true;
        }
    } catch (\Throwable $e) {
        $ready = false;
    }
    
@endphp
@if ($ready)
    <div class="w-[48rem] mx-auto font-serif border-2 border-black divide-y-2 divide-black">
        <div class="flex w-full gap-2 divide-x-2 divide-black">
            <div class="flex-1 p-2">
                <h2 class="text-lg font-semibold text-center">Liquidation Report</h2>
                <div class="flex justify-center gap-2 text-sm">
                    <h4>Period Covered</h4>
                    <h4 class="min-w-[12rem] border-b border-black">&nbsp;</h4>
                </div>
                <div class="mt-4 text-sm">
                    <div class="flex gap-2">
                        <h5 class="font-semibold w-28">Entity Name:</h5>
                        <p class="flex-1 border-b border-black">SKSU</p>
                    </div>
                    <div class="flex gap-2">
                        <h5 class="font-semibold w-28">Fund Cluster:</h5>
                        <p class="flex-1 border-b border-black">{{ $this->disbursement_voucher?->fund_cluster?->name }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="flex flex-col w-1/3 p-2">
                <div>
                    <div class="flex gap-2">
                        <h5>Serial Number:</h5>
                        <p class="flex-1 text-sm border-b border-black">&nbsp;</p>
                    </div>
                    <div class="flex gap-2">
                        <h5>Date:</h5>
                        <p class="flex-1 text-sm text-center border-b border-black">{{ today()->format('F d, Y') }}</p>
                    </div>
                </div>
                <div class="flex-1"></div>
                <div>
                    <div>
                        <h5>Responsibility Center Code:</h5>
                        <p class="flex-1 border-b border-black">&nbsp;</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex divide-x-2 divide-black">
            <h4 class="flex-1 text-center">PARTICULARS</h4>
            <h4 class="w-1/3 text-center">AMOUNT</h4>
        </div>
        <div>
            @foreach ($this->data['particulars'] as $particular)
                <div class="flex text-sm divide-x-2 divide-black">
                    <h4 class="flex-1 p-2 whitespace-pre-line">{{ $particular['purpose'] }}</h4>
                    <h4 class="flex items-end justify-end w-1/3 p-2 px-4">
                        {{ Akaunting\Money\Money::PHP($particular['amount'] ? $particular['amount'] : 0, true) }}
                    </h4>
                </div>
            @endforeach
        </div>

        <div class="flex text-sm divide-x-2 divide-black">
            <h4 class="flex-1 p-1">
                TOTAL AMOUNT SPENT
            </h4>
            <h4 class="w-1/3 p-1 px-4 text-right">
                {{ Akaunting\Money\Money::PHP(collect($this->data['particulars'])->sum('amount') ?? 0, true) }}
            </h4>
        </div>
        <div class="flex text-sm divide-x-2 divide-black">
            <h4 class="flex-1 p-1">AMOUNT OF CASH ADVANCE PER DV NO.
                <span class="border-b border-black">{{ $this->disbursement_voucher->dv_number }}</span>
                DTD.<span class="border-b border-black">{{ $this->disbursement_voucher->created_at->format('m/d/Y') }}</span>
            </h4>
            <h4 class="w-1/3 p-1 px-4 text-right">
                {{ Akaunting\Money\Money::PHP($this->disbursement_voucher->total_amount, true) }}
            </h4>
        </div>
        @foreach ($this->data['refund_particulars'] as $refund_particular)
            <div class="flex text-sm divide-x-2 divide-black">
                <h4 class="flex-1 p-1">AMOUNT REFUNDED PER OR NO.
                    <span class="border-b border-black">{{ $refund_particular['or_number'] }}</span>
                    DTD.<span class="border-b border-black">{{ date_create($refund_particular['date'])->format('m/d/Y') }}</span>
                </h4>
                <h4 class="w-1/3 p-1 pl-12 text-left">
                    {{ Akaunting\Money\Money::PHP($refund_particular['amount'] ? $refund_particular['amount'] : 0, true) }}
                </h4>
            </div>
        @endforeach
        <div class="flex text-sm divide-x-2 divide-black">
            <h4 class="flex-1 p-1">
                TOTAL AMOUNT REFUNDED
            </h4>
            <h4 class="w-1/3 p-1 pl-12 text-left">
                {{ Akaunting\Money\Money::PHP(collect($this->data['refund_particulars'])->sum('amount') ?? 0, true) }}
            </h4>
        </div>

        <div class="flex text-sm divide-x-2 divide-black">
            <h4 class="flex-1 p-1">
                AMOUNT TO BE REIMBURSED
            </h4>
            @if ($to_reimburse > 0)
                <h4 class="w-1/3 p-1 px-4 text-right">
                    {{ $this->data['reimbursement_waived'] ? 'WAIVED' : Akaunting\Money\Money::PHP(abs($to_reimburse), true) }}
                </h4>
            @else
                <h4 class="w-1/3 p-1 px-4 text-right">
                    {{ Akaunting\Money\Money::PHP(0, true) }}
                </h4>
            @endif

        </div>
        <div class="flex text-xs divide-x-2 divide-black">
            <div class="flex flex-col w-1/3 h-48 pb-1">
                <div>
                    <span class="p-1 border-b-2 border-r-2 border-black">A</span>
                    <span class="text-sm">Certified: Correctness of the above data</span>
                </div>
                <div class="flex flex-col items-center justify-center flex-1 px-4">
                    <p class="w-full text-center border-b border-black">
                        {{ App\Models\EmployeeInformation::firstWhere('user_id', auth()->id())?->full_name }}</p>
                    </p>
                    <p>Signature over Printed Name</p>
                    <p>Claimant</p>
                </div>
                <div class="flex gap-2 px-4">
                    <p>Date:</p>
                    <p class="flex-1 text-center border-b border-black">
                    </p>
                </div>
            </div>
            <div class="flex flex-col w-1/3 h-48 pb-1">
                <div>
                    <span class="p-1 border-b-2 border-r-2 border-black">B</span>
                    <span class="text-sm">Certified: Purpose of travel / cash advance duly accomplished</span>
                </div>
                <div class="flex flex-col items-center justify-center flex-1 px-4">
                    <p class="w-full text-center border-b border-black">
                        {{ App\Models\EmployeeInformation::firstWhere('user_id', $this->data['signatory_id'])?->full_name }}
                    </p>
                    <p>Signature over Printed Name</p>
                    <p>Immediate Supervisor</p>
                </div>
                <div class="flex gap-2 px-4">
                    <p>Date:</p>
                    <p class="flex-1 text-center border-b border-black">
                    </p>
                </div>
            </div>
            <div class="flex flex-col w-1/3 h-48 pb-1">
                <div>
                    <span class="p-1 border-b-2 border-r-2 border-black">C</span>
                    <span class="text-sm">Certified: Supporting documents complete and proper</span>
                </div>
                <div class="flex flex-col items-center justify-center flex-1 px-4">
                    <p class="w-full text-center border-b border-black">JESHER Y. PALOMARIA</p>
                    <p>Signature over Printed Name</p>
                    <p>Head, Accounting Division Unit</p>
                </div>
                <div class="flex gap-2 px-4">
                    <p>Date:</p>
                    <p class="flex-1 text-center border-b border-black">
                    </p>
                </div>
            </div>
        </div>
    </div>
@endif
