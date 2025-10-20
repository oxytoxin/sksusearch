<div>
    <div id="dvPrint">
        <div class="w-[48rem] bg-white mx-auto font-serif border-2 border-black divide-y-2 divide-black">
            <div class="flex w-full gap-2 divide-x-2 divide-black">
                <div class="flex-1 p-2">
                    <div class="flex gap-4 items-center justify-center mt-2">
                        <div>
                            <h2 class="text-lg font-semibold text-center">Liquidation Report</h2>
                            <div class="flex justify-center gap-2 text-sm">
                                <h4>Period Covered</h4>
                                <h4 class="min-w-[12rem] border-b border-black">&nbsp;</h4>
                            </div>
                        </div>
                        <div class="relative right-0">
                            <div class="m-auto flex justify-center items-center flex-col">
                                <img class="w-12"
                                     src="https://api.qrserver.com/v1/create-qr-code/?data={{ $liquidation_report->tracking_number }}&amp;size=100x100"
                                     title="" alt=""/>
                                <span class="font-xs flex justify-center text-[11px]">{{ $liquidation_report->tracking_number }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 text-sm">
                        <div class="flex gap-2">
                            <h5 class="font-semibold w-28">Entity Name:</h5>
                            <p class="flex-1 border-b border-black">SKSU</p>
                        </div>
                        <div class="flex gap-2">
                            <h5 class="font-semibold w-28">Fund Cluster:</h5>
                            <p class="flex-1 border-b border-black">
                                {{ $liquidation_report->disbursement_voucher->fund_cluster?->name }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col w-1/3 p-2">
                    <div>
                        <div class="flex gap-2">
                            <h5>Serial Number:</h5>
                            <p class="flex-1 text-sm border-b border-black">{{ $liquidation_report->lr_number }}</p>
                        </div>
                        <div class="flex gap-2">
                            <h5>Date:</h5>
                            <p class="flex-1 text-sm text-center border-b border-black">
                                {{ $liquidation_report->report_date->format('F d, Y') }}</p>
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
                @foreach ($liquidation_report->particulars as $particular)
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
                    {{ Akaunting\Money\Money::PHP(collect($liquidation_report->particulars)->sum('amount') ?? 0, true) }}
                </h4>
            </div>
            <div class="flex text-sm divide-x-2 divide-black">
                <h4 class="flex-1 p-1">AMOUNT OF CASH ADVANCE PER DV NO.
                    <span class="border-b border-black">{{ $liquidation_report->disbursement_voucher->dv_number }}</span>
                    DTD.<span
                            class="border-b border-black">{{ $liquidation_report->disbursement_voucher->created_at->format('m/d/Y') }}</span>
                </h4>
                <h4 class="w-1/3 p-1 px-4 text-right">
                    {{ Akaunting\Money\Money::PHP($liquidation_report->disbursement_voucher->total_amount, true) }}
                </h4>
            </div>
            @foreach ($liquidation_report->refund_particulars as $refund_particular)
                <div class="flex text-sm divide-x-2 divide-black">
                    <h4 class="flex-1 p-1">AMOUNT REFUNDED PER OR NO.
                        <span class="border-b border-black">{{ $refund_particular['or_number'] }}</span>
                        DTD.<span
                                class="border-b border-black">{{ date_create($refund_particular['date'])->format('m/d/Y') }}</span>
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
                    {{ Akaunting\Money\Money::PHP(collect($liquidation_report->refund_particulars)->sum('amount') ?? 0, true) }}
                </h4>
            </div>
            <div class="flex text-sm divide-x-2 divide-black">
                <h4 class="flex-1 p-1">
                    AMOUNT TO BE REIMBURSED
                </h4>
                @if ($to_reimburse > 0)
                    <h4 class="w-1/3 p-1 px-4 text-right">
                        {{ $liquidation_report->reimbursement_waived ? 'WAIVED' : Akaunting\Money\Money::PHP(abs($to_reimburse), true) }}
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
                            {{ $liquidation_report->requisitioner->employee_information->full_name }}
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
                            {{ $liquidation_report->signatory->employee_information->full_name }}</p>
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
            <div class="border-t col-span-8 text-xs text-center italic border-black w-full">
                <p>The original copy of this document appears in electronic form.</p>
            </div>
        </div>
    </div>

    <div class="mt-4" onclick="printDiv('dvPrint')">
        <x-filament-support::button>PRINT</x-filament-support::button>
    </div>

    <style>
        @page {
            size: auto;
            size: A4;
            margin: 0mm;
        }
    </style>
    @push('scripts')
        <script>
            function printDiv(divName) {
                var originalContents = document.body.innerHTML;
                var element = document.getElementById("toPrint");
                var printContents = document.getElementById(divName).innerHTML;
                document.body.innerHTML = printContents;
                window.print();
                document.body.innerHTML = originalContents;
            }
        </script>
    @endpush
</div>
