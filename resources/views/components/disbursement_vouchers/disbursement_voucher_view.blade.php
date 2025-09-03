<x-app-layout>
    <div>
        <div
            class="mx-auto flex max-w-[90%] border-collapse print:block print:h-[297mm] print:max-h-[297mm] print:w-[220mm] print:max-w-[220mm]"
            id="dvPrint">
            <div class="grid border-collapse grid-cols-8 border-4 border-black">
                <div class="col-span-6 border border-black">
                    <div class="flex min-w-full place-items-center justify-between">
                        <div class="ml-1 mt-1 flex">
                            <div class="my-auto flow-root">
                                <div class="mr-2 inline-block">
                                    <img class="mx-auto h-full w-14 object-scale-down"
                                         src="{{ asset('images/sksulogo.png') }}" alt="sksu logo">
                                    <span
                                        class="text-center text-xs text-black print:text-8">SKSU Works for Success!</span>
                                    {{-- <span class="text-xs font-bold text-center text-black"> ISO 9001:2015</span> --}}
                                </div>
                            </div>
                            <div class="flex place-items-center">
                                <div class="ext-left">
                                    <span class="block text-sm font-bold uppercase text-black">Republic of the Philippines</span>
                                    <span class="block text-sm font-bold uppercase text-green-600">SULTAN KUDARAT STATE UNIVERSITY</span>
                                    <span
                                        class="block text-sm text-black">ACCESS, EJC Montilla, 9800 City of Tacurong</span>
                                    <span class="block text-sm text-black">Province of Sultan Kudarat</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="m-3 text-center">

                                <img class="mx-auto h-auto w-12"
                                     src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ route('disbursement-vouchers.show-from-trn', ['disbursement_voucher' => $disbursement_voucher->tracking_number]) }}"
                                     alt="N/A">
                                <span
                                    class="flex justify-center text-xs font-normal">{{ $disbursement_voucher->tracking_number }}</span>
                            </div>

                        </div>
                    </div>
                    <div class="min-w-full border-t-4 border-black text-center">
                        <span class="text-md mx-auto font-serif font-extrabold uppercase text-black">
                            Disbursement Voucher</span>
                    </div>
                </div>

                <div class="col-span-2 grid grid-rows-2 border border-black">
                    <div class="row-span-1 border-b border-l border-black">
                        <span
                            class="mx-auto ml-1 font-serif text-xs font-extrabold capitalize text-black print:text-12">
                            fund cluster:
                        </span>
                    </div>
                    <div class="row-span-1 border-l border-black pb-6">
                        <p class="mx-auto ml-1 font-serif text-xs font-extrabold capitalize text-black print:text-12">
                            date <span class="ml-2"> {{ $disbursement_voucher->submitted_at->format('m/d/Y') }}</span>
                        </p>
                        <p class="mx-auto ml-1 font-serif text-xs font-extrabold text-black print:text-12">
                            DV No.
                        </p>
                    </div>
                </div>

                <div class="col-span-8 flex min-w-full items-start border-t-2 border-black font-serif">
                    <div class="flex h-full border-r-2 border-black px-2 py-1 text-center">
                        <span class="text-sm font-extrabold">Mode of Payment</span>
                    </div>
                    <div class="ml-10 flex space-x-2 py-1">
                        @foreach ($mops as $mop)
                            <div class="relative flex items-start">
                                <div class="flex h-5 items-center">
                                    @if ($mop->id == $disbursement_voucher->mop_id)
                                        <input class="h-4 w-4 border-black text-indigo-500 focus:ring-primary-500"
                                               id="comments" name="comments" type="checkbox"
                                               aria-describedby="comments-description" readonly disabled checked>
                                    @else
                                        <input class="h-4 w-4 border-black text-primary-500 focus:ring-primary-500"
                                               id="comments" name="comments" type="checkbox"
                                               aria-describedby="comments-description" readonly disabled>
                                    @endif
                                </div>
                                <div class="ml-1 text-sm">
                                    <label class="font-medium text-black">{{ $mop->name }}</label>
                                </div>
                            </div>
                        @endforeach

                        @if ($mop->id == 4)
                            <div class="relative flex items-start">
                                <div class="ml-1 text-sm">
                                    <span class="font-medium text-black"></span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-span-8 flex min-w-full items-start border-t-2 border-black font-serif">

                    <div class="flex h-full border-r-2 border-black px-2 py-1 text-center">
                        <span class="my-auto text-sm font-extrabold">Payee</span>
                    </div>
                    <div class="flex h-full w-1/2 border-r-2 border-black text-left">
                        <span class="text-serif my-auto flex pl-2 font-extrabold uppercase print:text-10">
                            {{ $disbursement_voucher->payee }} </span>
                    </div>
                    <div class="flex h-full w-64 border-r-2 border-black px-2 py-1 text-left">
                        <span class="pb-3 text-xs font-extrabold">TIN/Employee No.:</span>
                    </div>
                    <div class="flex h-full w-60 px-2 py-1 text-left">
                        <span
                            class="pb-3 text-xs font-extrabold">ORS/BURS No.: {{ $disbursement_voucher->ors_burs }}</span>
                    </div>

                </div>
                <div class="col-span-8 flex min-w-full items-start border-t-2 border-black font-serif">
                    <div class="flex h-full border-r-2 border-black px-2 py-1 text-center">
                        <span class="my-auto text-sm font-extrabold">Address</span>
                    </div>
                </div>
                {{-- Particulars Heading --}}
                <div class="col-span-8 flex min-w-full items-start border-t-2 border-black font-serif print:text-12">
                    <div class="h-auto w-1/2 border-r-2 border-black text-center">
                        Particulars
                    </div>
                    <div class="h-auto w-64 border-r-2 border-black text-center">
                        Responsibility Center
                    </div>
                    <div class="h-auto w-36 border-r-2 border-black text-center">
                        MFO/PAP
                    </div>
                    <div class="h-auto w-36 text-center">
                        Amount
                    </div>
                </div>
                <div class="col-span-8 flex min-w-full items-start border-t-2 border-black font-serif print:text-10">
                    <div class="h-44 w-1/2 border-r-2 border-black pl-2 text-left">
                        <div class="flex flex-col">
                            @foreach ($disbursement_voucher->disbursement_voucher_particulars as $particular)
                                <span>{{ $particular->purpose }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="h-44 w-64 border-r-2 border-black text-center">
                        <div class="flex flex-col">
                            @foreach ($disbursement_voucher->disbursement_voucher_particulars as $particular)
                                <span>{{ $particular->responsibility_center }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="h-44 w-36 border-r-2 border-black text-center">
                        <div class="flex flex-col">
                            @foreach ($disbursement_voucher->disbursement_voucher_particulars as $particular)
                                <span>{{ $particular->mfo_pap }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="h-44 w-36 text-right">
                        <div class="flex flex-col">
                            @foreach ($disbursement_voucher->disbursement_voucher_particulars as $particular)
                                <span>{{ number_format($particular->amount, 2) }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-span-8 flex min-w-full items-start border-black font-serif print:text-12">
                    <div class="h-auto w-1/2 border-r-2 border-t-2 border-black text-center">
                        Amount Due
                    </div>
                    <div class="flex h-auto w-64 border-r-2 border-black text-center">
                        &nbsp
                    </div>
                    <div class="h-auto w-36 border-r-2 border-black text-center">
                        &nbsp
                    </div>
                    <div class="h-auto w-36 border-t-4 border-double border-black text-right print:text-10">
                        {{ number_format($disbursement_voucher->disbursement_voucher_particulars->sum('final_amount'), 2) }}
                    </div>
                </div>
                <div class="col-span-8 flex min-w-full items-start border-t-2 border-black font-serif">
                    <div class="w-full">
                        <div class="row-span-1 flex">
                            <div class="border-b border-r border-black px-1 font-extrabold print:text-12">A.</div>
                            <span class="pl-1 font-extrabold print:text-12">Certified: Expenses/Cash Advance necessary, lawful and incurred under my direct supervision.</span>
                        </div>
                        <div class="row-span-1 mx-auto block text-center">
                            @php
                                $full_name = explode(',', $disbursement_voucher->signatory->employee_information->full_name)[0];
                            @endphp
                            <span class="font-extrabold uppercase underline print:text-10">
                                &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                {{ isset($full_name) ? $full_name : 'none' }}
                                &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</span>
                            <p class="font-extrabold capitalize print:text-10">
                                {{ $disbursement_voucher->signatory->employee_information->position?->description }}
                                , {{ $disbursement_voucher->signatory->employee_information->office?->name }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-span-8 flex min-w-full items-start border-t-2 border-black font-serif">
                    <div class="flex">
                        <div class="border-b border-r border-black px-1 font-extrabold print:text-12">B.</div>
                        <span class="pl-1 font-extrabold print:text-12">Accounting Entry:</span>
                    </div>
                </div>
                <div class="col-span-8 flex min-w-full items-start border-t-2 border-black font-serif print:text-12">
                    <div class="h-auto w-1/2 border-r-2 border-black text-center">
                        Account Title
                    </div>
                    <div class="h-auto w-72 border-r-2 border-black text-center">
                        UACS Code
                    </div>
                    <div class="h-auto w-28 border-r-2 border-black text-center">
                        Debit
                    </div>
                    <div class="h-auto w-28 text-center">
                        Credit
                    </div>
                </div>
                <div class="col-span-8 flex min-w-full items-start border-t-2 border-black font-serif print:text-12">
                    <div class="h-44 w-1/2 border-r-2 border-black text-center">
                        &nbsp
                    </div>
                    <div class="h-44 w-72 border-r-2 border-black text-center">
                        &nbsp
                    </div>
                    <div class="h-44 w-28 border-r-2 border-black text-center">
                        &nbsp
                    </div>
                    <div class="h-44 w-28 text-center">
                        &nbsp
                    </div>
                </div>
                <div class="col-span-8 flex min-w-full items-start border-t-2 border-black font-serif print:text-12">
                    <div class="w-1/2 border-r-2 border-black">
                        <div class="flex">
                            <div class="border-b border-r border-black px-1 font-extrabold print:text-12">C.</div>
                            <span class="pl-1 font-extrabold print:text-12">Certified:</span>
                        </div>
                    </div>
                    <div class="w-1/2 border-r-2 border-black">
                        <div class="flex">
                            <div class="border-b border-r border-black px-1 font-extrabold print:text-12">D.</div>
                            <span class="pl-1 font-extrabold print:text-12">Approved for Payment:</span>
                        </div>
                    </div>

                </div>
                <div class="col-span-8 flex min-w-full items-start border-t-2 border-black font-serif print:text-12">

                    <div class="w-1/2 space-y-1 border-r-2 border-black print:text-8">
                        <div class="mt-1 flex items-center">
                            <div class="mx-2 h-3 w-8 border border-black"></div>
                            <span>Cash available</span>
                        </div>
                        <div class="flex items-center">
                            <div class="mx-2 h-3 w-8 border border-black"></div>
                            <span>Subject to Authority to Debit Account (when applicable)</span>
                        </div>
                        <div class="mb-1 flex items-center">
                            <div class="mx-2 h-3 w-8 border border-black"></div>
                            <span>Supporting documents complete and amount claimed proper</span>
                        </div>
                    </div>
                </div>
                <div class="col-span-8 flex min-w-full items-start border-t-2 border-black font-serif print:text-12">
                    <div class="w-1/2 space-y-1 border-r-2 border-black print:text-8">
                        <div class="flex h-auto w-20 border-r border-black text-center print:h-8 print:w-16">
                            <span class="mx-auto my-auto flex print:text-12">Signature</span>
                        </div>
                    </div>
                    <div class="w-1/2 space-y-1 print:text-8">
                        <div class="flex h-auto w-20 border-r border-black text-center print:h-8 print:w-16">
                            <span class="mx-auto my-auto flex print:text-12">Signature</span>
                        </div>
                    </div>
                </div>
                @php
                    $president = App\Models\EmployeeInformation::where('position_id', 34)->where('office_id', 51)->first();
                    $accountant = App\Models\EmployeeInformation::where('position_id', 15)->where('office_id', 3)->first();
                @endphp
                <div class="col-span-8 flex min-w-full items-start border-t-2 border-black font-serif print:text-12">
                    <div class="flex w-1/2 items-center space-y-1 border-r-2 border-black text-center print:text-8">
                        <div class="flex h-auto w-20 border-r border-black text-center print:h-8 print:w-16">
                            <span class="w-full break-words print:text-12">Printed Name</span>
                        </div>
                        <span
                            class="mx-auto my-auto flex font-extrabold uppercase print:text-10">{{ $accountant->full_name }}</span>
                    </div>
                    <div class="flex w-1/2 items-center space-y-1 border-r-2 border-black text-center print:text-8">
                        <div class="flex h-auto w-20 border-r border-black text-center print:h-8 print:w-16">
                            <span class="w-full break-words print:text-12">Printed Name</span>
                        </div>
                        <span
                            class="mx-auto my-auto flex font-extrabold uppercase print:text-10">{{ $president->full_name }}</span>
                    </div>
                </div>
                <div class="col-span-8 flex min-w-full items-start border-t-2 border-black font-serif print:text-12">

                    <div class="flex w-1/2 space-y-1 border-r-2 border-black text-center print:text-8">
                        <div class="flex h-auto w-20 shrink-0 border-r border-black text-center print:h-8 print:w-16">
                            <span class="mx-auto my-auto flex print:text-12">Position</span>
                        </div>

                        <div class="h-auto w-full text-center print:h-8">
                            <div class="h-4 w-full border-b border-black">
                                <span class="mx-auto my-auto block text-xs font-extrabold uppercase print:text-8">University
                                    Accountant</span>
                            </div>
                            <div class="h-4 w-full">
                                <span class="mx-auto my-auto block text-xs font-extrabold uppercase print:text-8">Head,
                                    Accounting
                                    Unit/Authorized Representative</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex w-1/2 space-y-1 border-r-2 border-black text-center print:text-8">
                        <div class="flex h-auto w-20 shrink-0 border-r border-black text-center print:h-8 print:w-16">
                            <span class="mx-auto my-auto flex print:text-12">Position</span>
                        </div>
                        <div class="h-auto w-full text-center print:h-8">
                            <div class="h-4 w-full border-b border-black">
                                <span class="mx-auto my-auto block text-xs font-extrabold uppercase print:text-8">University
                                    President</span>
                            </div>
                            <div class="h-4 w-full">
                                <span class="mx-auto my-auto block text-xs font-extrabold uppercase print:text-8">Agency
                                    Head/Authorized
                                    Representative</span>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-span-8 flex min-w-full items-start border-t-2 border-black font-serif print:text-12">
                    <div class="w-1/2 space-y-1 border-r-2 border-black print:text-8">
                        <div class="flex h-auto w-20 border-r border-black text-center print:h-8 print:w-16">
                            <span class="mx-auto my-auto flex print:text-12">Date</span>

                        </div>
                    </div>
                    <div class="w-1/2 space-y-1 print:text-8">
                        <div class="flex h-auto w-20 border-r border-black text-center print:h-8 print:w-16">
                            <span class="mx-auto my-auto flex print:text-12">Date</span>
                        </div>
                    </div>
                </div>

                <div class="col-span-8 flex min-w-full items-start border-t-2 border-black font-serif print:text-12">
                    <div class="w-full flex-col">
                        <div class="flex w-full border-b-2 border-black">
                            <div class="border-r border-black px-1 font-extrabold print:text-12">E.</div>
                            <span class="pl-1 font-extrabold print:text-12">Receipt of Payment</span>
                        </div>
                        <div class="flex w-full flex-row border-b-2 border-black">
                            <div
                                class="h-auto w-20 shrink-0 border-r border-black px-1 text-xs font-extrabold print:w-20 print:text-10">
                                Check / ADA No.:
                            </div>
                            <div class="h-auto w-1/3 shrink-0 border-r border-black">
                                <div class="h-5"></div>
                            </div>
                            <div class="h-auto w-full border-r border-black px-1 text-xs font-extrabold print:text-10">
                                Date:
                            </div>
                            <div class="h-auto w-full border-r border-black px-1 text-xs font-extrabold print:text-10">
                                Bank Name & Account Number
                            </div>
                        </div>
                    </div>
                    <div class="float-left h-full w-1/6 shrink-0 border-l border-black">
                        <div class="flex text-left print:text-12">
                            JEV No.
                        </div>
                    </div>
                </div>
                <div class="col-span-8 flex min-w-full items-start border-t-2 border-black font-serif print:text-12">
                    <div class="w-full flex-col">
                        <div class="flex w-full flex-row border-b-2 border-black">
                            <div
                                class="h-auto w-20 shrink-0 border-r border-black px-1 text-xs font-extrabold print:w-20 print:text-10">
                                Signature
                            </div>
                            <div class="h-auto w-1/3 shrink-0 border-r border-black">
                                <div class="h-5"></div>
                            </div>
                            <div class="h-auto w-full border-r border-black px-1 text-xs font-extrabold print:text-10">
                                Date:
                            </div>
                            <div class="h-auto w-full border-r border-black px-1 text-xs font-extrabold print:text-10">
                                Printed Name:
                            </div>
                        </div>
                        <div class="w-full">
                            <span class="pl-1 font-extrabold print:text-12">Official Receipt No. & Date/Other
                                Documents</span>
                        </div>
                    </div>
                    <div class="float-left h-full w-1/6 shrink-0 border-l border-black">
                        <div class="flex text-left print:text-12">
                            Date
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="mx-auto mt-4 max-w-[90%]">
            <button
                class="mt-2 inline-flex items-center rounded-md border border-transparent bg-primary-500 px-4 py-2 text-xs font-medium text-white shadow-sm hover:bg-primary-300 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
                type="button" onclick="printDiv('dvPrint')">
                <!-- Heroicon name: mini/envelope -->
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                     fill="currentColor">
                    <path fill-rule="evenodd"
                          d="M7.875 1.5C6.839 1.5 6 2.34 6 3.375v2.99c-.426.053-.851.11-1.274.174-1.454.218-2.476 1.483-2.476 2.917v6.294a3 3 0 003 3h.27l-.155 1.705A1.875 1.875 0 007.232 22.5h9.536a1.875 1.875 0 001.867-2.045l-.155-1.705h.27a3 3 0 003-3V9.456c0-1.434-1.022-2.7-2.476-2.917A48.716 48.716 0 0018 6.366V3.375c0-1.036-.84-1.875-1.875-1.875h-8.25zM16.5 6.205v-2.83A.375.375 0 0016.125 3h-8.25a.375.375 0 00-.375.375v2.83a49.353 49.353 0 019 0zm-.217 8.265c.178.018.317.16.333.337l.526 5.784a.375.375 0 01-.374.409H7.232a.375.375 0 01-.374-.409l.526-5.784a.373.373 0 01.333-.337 41.741 41.741 0 018.566 0zm.807-3.97a.75.75 0 01.75-.75h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H18a.75.75 0 01-.75-.75V10.5zM15 9.75a.75.75 0 00-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 00.75-.75V10.5a.75.75 0 00-.75-.75H15z"
                          clip-rule="evenodd"/>
                </svg>
                Print Voucher
            </button>
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
</x-app-layout>
