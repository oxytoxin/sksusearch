<div>

    <div class="flex justify-start w-full mb-4 ">
        <button onclick="printDiv('printableDiv')" class="px-4 py-2 bg-primary-500 text-white rounded text-sm">
            Print Document
        </button>
    </div>
    <div class="flex mx-auto w-full justify-center">
        <div class="document bg-white">
            <div id="printableDiv" class="p-6 bg-white shadow-md border border-gray-300 mx-auto max-w-3xl">
                <x-sksu-header />
                <h1 class="text-xl font-bold pt-1 mt-2 text-center">Office of the President</h1>

                <div class="text-xs text-gray-800">
                    <p class="mt-4 font-bold">Memorandum No. <span class="underline">
                            {{ $record->cash_advance_reminder->memorandum_number ?? '' }}</p>

                    <div class="mt-4">
                        <div class="flex font-bold">
                            <span class="min-w-12">To:</span>
                            <span>{{ $record?->user?->name }}</span>
                        </div>
                        <div class="flex font-bold">
                            <span class="min-w-12">From:</span>
                            <span>{{ App\Models\EmployeeInformation::presidentUser()->full_name }}</span>
                        </div>
                        <div class="flex font-bold">
                            <span class="min-w-12">Re:</span>
                            <span>SHOW CAUSE ORDER</span>
                        </div>
                        <div class="flex font-bold">
                            <span class="min-w-12">Date:</span>
                            <span>{{ $record?->cash_advance_reminder?->sco_date ? \Carbon\Carbon::parse($record->cash_advance_reminder->sco_date)->format('F d, Y') : '' }}</span>
                        </div>
                    </div>

                    <p class="mt-4">
                        Official records show that you have been granted the following cash advance:
                    </p>

                    <x-disbursement-voucher-table :record="$record" />
                    <div>
                        <div class="mt-4 text-xs text-gray-800 leading-relaxed">
                            <h1>Purpose:</h1>
                            <ul class="list-disc pl-6 mt-2">
                                @foreach ($record->disbursement_voucher_particulars as $particular)
                                    <li>{{ $particular->purpose }}</li>
                                @endforeach
                            </ul>
                            <div class="mt-4 text-xs text-gray-800 leading-relaxed">
                                <p>
                                    Additionally, accounting documents reveal that you were issued a prior reminder
                                    through FMR No.
                                    {{ $record?->cash_advance_reminder?->fmr_number ?? '' }} and a subsequent demand
                                    through FMD No. {{ $record?->cash_advance_reminder->fmd_number ?? '' }}, but no
                                    substantial compliance has been
                                    made by you in relation thereto. This already constitutes wilful negligence on your
                                    part with
                                    respect to a reasonable official order and an outright refusal by you to perform an
                                    obligation
                                    required by law.
                                </p>

                                <p class="mt-4">
                                    Now therefore, pursuant to Section 5 of the <em>Sanctions for Violations of Rules
                                        and Regulations
                                        Related to the Liquidation of Cash Advances</em> (hereinafter referred to as
                                    <strong>Sanctions</strong>), as adopted through BOR Resolution No. 56, s. 2024, you
                                    are
                                    <strong>DIRECTED TO LIQUIDATE</strong> your cash advance with the following
                                    alternatives:
                                </p>

                                <ol class=" pl-6 mt-2">
                                    <li>(1) Liquidate the cash advance in full;</li>
                                    <li>(2) Execute a Waiver for Deduction Against Personnel Pay for the outstanding
                                        balance of the cash
                                        advance; or</li>
                                    <li>(3) Make partial liquidation of the cash advance and execute a Waiver for
                                        Deduction Against
                                        Personnel Pay for the remainder.</li>
                                </ol>

                                <p class="mt-4">
                                    Furthermore, pursuant to Section 7 of the same <strong>Sanctions</strong>, you are
                                    hereby
                                    <strong>ORDERED TO SHOW CAUSE</strong> in writing as to why you should not be cited
                                    for
                                    <strong>ADMINISTRATIVE</strong> offenses under Section 5 of CSC Memorandum Circular
                                    No. 23, s. 2019 in relation to Section 53, Rule 10 of the RRACCS and for
                                    <strong>CRIMINAL</strong> offenses under the penal provisions of Paragraph 9.3.3 of
                                    COA Circular No. 97-002 in relation to Sections 89
                                    and 128 of PD 1445, and under Article 218 of The Revised Penal Code, carrying
                                    penalties of imprisonment or
                                    fine, or both, according to the discretion of the Court, for your failure to
                                    liquidate the subject cash advance.
                                </p>

                                <p class="mt-4">
                                    Your separate and concurrent obligations to liquidate and to show cause are due
                                    within three (3)
                                    working days from receipt of this notice. Legal action shall ensue upon your failure
                                    to comply.
                                </p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>

        <livewire:requisitioner.message-reply-section :disbursement_voucher="$record" />

    </div>

    <script>
        function printDiv(divName) {
            const printContents = document.getElementById(divName).innerHTML;
            const originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
</div>
