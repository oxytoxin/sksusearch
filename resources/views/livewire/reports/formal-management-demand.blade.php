<div class="flex mx-auto w-full justify-center">
    <div class="document bg-white">

        {{--
        <div class="mx-auto max-w-3xl flex justify-start mb-4">
            <button onclick="printDiv('printableDiv')" class="px-4 py-2 bg-primary-500 text-white rounded text-sm">
                Print Document
            </button>
        </div> --}}


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
    <div class="w-1/3 bg-white border border-gray-300 ml-4 shadow-md rounded-lg overflow-hidden max-h-[80vh] flex flex-col">

        <h3 class="text-lg font-bold mb-2 flex items-center bg-gray-800 text-white p-4">
            <div class="flex items-center justify-center w-12 h-12 bg-primary-200 rounded-full mr-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 text-primary-500">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                </svg>
            </div>
            Message & Reply Section
        </h3>
        <div class="p-3 h-full overflow-y-auto flex-grow">
            <div class="text-xs text-gray-700">
                <div class="flex items-center mb-2">
                    <div class="flex items-center justify-center w-6 h-6 bg-gray-200 rounded-full mr-2">
                        <span class="text-sm font-bold text-primary-800">U1</span>
                    </div>
                    <div>
                        <p class="font-bold">User 1</p>
                        <p class="text-xs text-gray-500">{{ now()->subDays(1)->format('F d, Y h:i A') }} <span class="text-gray-400">(2 days ago)</span></p>
                    </div>
                </div>
                <p class="mb-2">Please clarify the deadline for submission.</p>
                <div class="ml-4 mt-2 border-l-2 pl-3 border-gray-300 bg-gray-50 rounded-lg">
                    <div class="flex items-center mb-2">
                        <div class="flex items-center justify-center w-6 h-6 bg-gray-200 rounded-full mr-2">
                            <span class="text-sm font-bold text-primary-800">U1</span>
                        </div>
                        <div>
                            <p class="font-bold">User 2</p>
                            <p class="text-xs text-gray-500">{{ now()->format('F d, Y h:i A') }} <span class="text-gray-400">(Just now)</span></p>
                        </div>
                    </div>
                    <p class="mb-2">The deadline is five working days from receipt.</p>
                    <button class="mt-1 px-2 py-1 text-primary-500 rounded text-xs font-bold">Reply</button>
                    <button class="mt-1 px-2 py-1 text-red-500 rounded text-xs font-bold">Delete</button>
                </div>
                <!-- More messages can be added here -->
            </div>
        </div>
        <div class="mt-2 p-3">
            <textarea class="w-full p-2 border rounded-lg focus:ring focus:ring-primary-200 transition duration-200 ease-in-out" placeholder="Type your message..."></textarea>
            <button class="mt-2 px-4 py-2 bg-primary-500 text-white rounded hover:bg-primary-600 transition duration-200 ease-in-out">Send</button>
        </div>
    </div>
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
