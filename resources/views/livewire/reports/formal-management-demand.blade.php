<div>
        <div class="flex justify-start w-full mb-4 ">
        <button onclick="printDiv('printableDiv')" class="px-4 py-2 bg-primary-500 text-white rounded text-sm">
            Print Document
        </button>
    </div>


<div class="flex mx-auto w-full justify-center">
    <div class="document bg-white">



        <div id="printableDiv" class="p-6 bg-white border border-gray-300 max-w-3xl flex-1">
            <x-sksu-header />

            <div class="mb-4 text-gray-800 my-6">
                <h2 class="text-xl font-bold text-center">Formal Management Demand</h2>
                <p class="text-center">No. {{ $record?->cash_advance_reminder?->fmd_number ?? '' }}</p>
            </div>
            <div class="border-b pb-2 border-black text-gray-800 text-xs">
                <div class="flex justify-start font-bold">
                    <p class="label min-w-12"> To:</p>
                    <div class=""> {{ $record->user->name }}</div>
                </div>
                <div class="flex justify-start font-bold">
                    <p class="label min-w-12"> Re:</p>
                    <div class=""> Demand Liquidated Advance</div>
                </div>
                <div class="flex justify-start font-bold">
                    <p class="label min-w-12"> Date:</p>
                    <div class="">
                        {{ $record?->cash_advance_reminder?->fmd_date ? \Carbon\Carbon::parse($record->cash_advance_reminder->fmd_date)->format('F d, Y') : '' }}
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <p class="text-xs text-gray-800 leading-relaxed">
                    Pursuant to Section 3 of the Sanctions for Violations of Rules and Regulations Related to the
                    Liquidation of
                    Cash Advances, as adopted through BOR Resolution No. 56, s. {{ now()->format('Y') }}, demand is
                    hereby
                    issued for the
                    liquidation of the cash advance described below.
                </p>
            </div>

            <x-disbursement-voucher-table :record="$record" />

            <div class="mt-4 text-xs text-gray-800 leading-relaxed">
                <h1>Purpose:</h1>
                <ul class="list-disc pl-6 mt-2">
                    @foreach ($record->disbursement_voucher_particulars as $particular)
                        <li>{{ $particular->purpose }}</li>
                    @endforeach
                </ul>
                <div class="mt-4 text-xs text-gray-800 leading-relaxed">
                    <p>Records also show that a prior reminder contained in FMR No. xxxx-xxxx was issued to you on
                        &lt;Date&gt; in
                        relation hereto.</p>

                    <p class="mt-4">In view of the foregoing premises, you are hereby ordered to effect the immediate
                        liquidation of the cash advance described above. You are given the following alternatives, which
                        must be
                        duly acted upon within five <br>(5) working days upon receipt of this notice:</p>
                    <ol class="pl-6 mt-2">
                        <li>(1) Liquidate the cash advance in full;</li>
                        <li>(2) Execute a Waiver for Deduction Against Personnel Pay (WDAPP) for the outstanding balance
                            (OB) of
                            the cash advance; or</li>
                        <li>(3) Make partial liquidation of the cash advance and execute a Waiver for Deduction Against
                            Personnel Pay for the remainder.</li>
                    </ol>

                    <p class="mt-4">Failure on your part to comply with this directive may result in the filing of
                        proper
                        administrative<sup>1</sup> and criminal<sup>2</sup> charges against you. If partial or full
                        liquidation has already been made, please coordinate with the Accounting Office for validation
                        and
                        correction of records.</p>

                    <p class="mt-4">For your guidance and immediate compliance.</p>
                </div>
            </div>

            <div class="text-xs mt-4 text-gray-800">
                <div class="grid grid-cols-3">
                    <div class="col-span-1">
                        <div class="text-center">
                            <p class="font-bold">{{ App\Models\EmployeeInformation::accountantUser()->full_name }}</p>
                            <p>{{ App\Models\EmployeeInformation::accountantUser()?->position->description }}-{{ App\Models\EmployeeInformation::accountantUser()?->office->name }}
                            </p>
                        </div>

                        <div class="mt-6">
                            <hr class="border-t border-black mx-8">
                        </div>
                    </div>

                    <div class="col-span-2 flex justify-between ">
                        <div>
                            <p class="">I hereby express my intention to:</p>
                            <label class="flex items-center ">
                                <input type="checkbox" class="mr-2 w-2.5 h-3">
                                <span>Liquidate the cash advance in full</span>
                            </label>
                            <label class="flex items-center ">
                                <input type="checkbox" class="mr-2 w-2.5 h-3">
                                <span>Execute a WDAPP for the full OB</span>
                            </label>
                            <label class="flex items-center ">
                                <input type="checkbox" class="mr-2 w-2.5 h-3">
                                <span>Settle partially with liquidation and with WDAPP</span>
                            </label>
                        </div>
                        <div class="ml-2 text-right">
                            <p class="">By date:</p>
                            <hr class="border-t border-black w-32 mt-3">
                            <hr class="border-t border-black w-32 mt-3">
                            <hr class="border-t border-black w-32 mt-3">
                        </div>
                    </div>
                </div>

                <div class="mt-8 text-xs">
                    <p class="">
                        <sup>1</sup>Section 5 of CSC Memorandum Circular No. 23, s. 2019 | Section 53, Rule 10, RRACCS
                        <br>
                        <sup>2</sup>Paragraph 9.3.3, COA Circular No. 97-002 | Section 89 & 128, PD No. 1445 | Article
                        218,
                        Act No. 3815 (The Revised Penal Code), as amended
                    </p>
                </div>

                <p class="mt-4 text-xs ">
                    Note: If you received this FMD through personal service, please indicate your intended mode of
                    settlement by ticking the applicable checkbox in the lower right of this document. If you received
                    this
                    FMD via electronic service, you may indicate said mode in your reply. Non-indication of any
                    particular
                    mode shall give rise to the presumption that you elect to liquidate your cash advance in full.
                </p>
            </div>
        </div>

    </div>

        <livewire:requisitioner.message-reply-section :disbursement_voucher="$record" />

</div>



<script>
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
</div>
