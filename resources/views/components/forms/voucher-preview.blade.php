<x-forms::field-wrapper :id="$getId()" :label="$getLabel()" :label-sr-only="$isLabelHidden()" :helper-text="$getHelperText()" :hint="$getHint()" :hint-icon="$getHintIcon()" :required="$isRequired()" :state-path="$getStatePath()">

    <div x-data="{ state: $wire.entangle('{{ $getStatePath() }}').defer }">
        <div id="dvPrint" style="flex border-collapse  max-w-8xl print:block print:w-[220mm] print:h-[297mm] print:max-w-[220mm] print:max-h-[297mm]">
            <div class="grid grid-cols-8 border-4 border-collapse border-black">

                <div class="col-span-6 border border-black">
                    <div class="flex justify-between min-w-full place-items-center">
                        <div class="flex mt-1 ml-1">
                            <div class="flow-root my-auto">
                                <div class="inline-block mr-2">
                                    <img src="http://sksu.edu.ph/wp-content/uploads/2020/09/512x512-1.png" alt="sksu logo" class="object-scale-down h-full mx-auto w-14">
                                    <span class="text-xs text-center text-black print:text-8">SKSU Works for Success!</span>
                                    {{-- <span class="text-xs font-bold text-center text-black"> ISO 9001:2015</span> --}}
                                </div>
                            </div>
                            <div class="flex place-items-center">
                                <div class="ext-left">
                                    <span class="block text-sm font-bold text-black uppercase">Republic of the Philippines</span>
                                    <span class="block text-sm font-bold text-green-600 uppercase">SULTAN KUDARAT STATE
                                        UNIVERSITY</span>
                                    <span class="block text-sm text-black">ACCESS, EJC Montilla, 9800 City of
                                        Tacurong</span>
                                    <span class="block text-sm text-black">Province of Sultan Kudarat</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="m-3 text-center">

                                <img class="w-12 h-auto mx-auto" src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $evaluate(fn($get) => $get('tracking_number')) }}" alt="N/A">
                                <span class="flex justify-center text-xs font-normal">{{ $evaluate(fn($get) => $get('tracking_number')) }}</span>
                            </div>

                        </div>
                    </div>
                    <div class="min-w-full text-center border-t-4 border-black">
                        <span class="mx-auto font-serif font-extrabold text-black uppercase text-md">Disbursement
                            Voucher</span>
                    </div>
                </div>

                <div class="grid col-span-2 grid-rows-2 border border-black">
                    <div class="row-span-1 border-b border-l border-black">
                        <span class="mx-auto ml-1 font-serif text-xs font-extrabold text-black capitalize print:text-12">
                            fund cluster:
                        </span>
                    </div>
                    <div class="row-span-1 pb-6 border-l border-black">
                        <p class="mx-auto ml-1 font-serif text-xs font-extrabold text-black capitalize print:text-12">
                            date
                        </p>
                        <p class="mx-auto ml-1 font-serif text-xs font-extrabold text-black print:text-12">
                            DV No.
                        </p>
                    </div>
                </div>

                <div class="flex items-start min-w-full col-span-8 font-serif border-t-2 border-black">
                    <div class="flex h-full px-2 py-1 text-center border-r-2 border-black">
                        <span class="text-sm font-extrabold">Mode of Payment</span>
                    </div>
                    @php
                        $mop = $evaluate(fn($get) => $get('mode_of_payment'));
                        
                    @endphp
                    <div class="flex py-1 ml-10 space-x-2">
                        <div class="relative flex items-start">
                            <div class="flex items-center h-5">
                                @if ($mop == 'MD5')
                                    <input id="comments" aria-describedby="comments-description" name="comments" type="checkbox" class="w-4 h-4 border-black text-primary-500 focus:ring-primary-500" readonly disabled checked>
                                @else
                                    <input id="comments" aria-describedby="comments-description" name="comments" type="checkbox" class="w-4 h-4 border-black text-primary-500 focus:ring-primary-500" readonly disabled>
                                @endif

                            </div>
                            <div class="ml-1 text-sm">
                                <label class="font-medium text-black">MD5</label>

                            </div>
                        </div>
                        <div class="relative flex items-start">
                            <div class="flex items-center h-5">
                                @if ($mop == 'Commercial Check')
                                    <input id="candidates" aria-describedby="candidates-description" name="candidates" type="checkbox" class="w-4 h-4 border-black text-primary-500 focus:ring-primary-500" readonly disabled checked>
                                @else
                                    <input id="candidates" aria-describedby="candidates-description" name="candidates" type="checkbox" class="w-4 h-4 border-black text-primary-500 focus:ring-primary-500" readonly disabled>
                                @endif

                            </div>
                            <div class="ml-1 text-sm">
                                <label class="font-medium text-black">Commercial Check</label>

                            </div>
                        </div>
                        <div class="relative flex items-start">
                            <div class="flex items-center h-5">
                                @if ($mop == 'ADA')
                                    <input id="offers" aria-describedby="offers-description" name="offers" type="checkbox" class="w-4 h-4 border-black text-primary-500 focus:ring-primary-500" readonly disabled checked>
                                @else
                                    <input id="offers" aria-describedby="offers-description" name="offers" type="checkbox" class="w-4 h-4 border-black text-primary-500 focus:ring-primary-500" readonly disabled>
                                @endif

                            </div>
                            <div class="ml-1 text-sm">
                                <label class="font-medium text-black">ADA</label>

                            </div>
                        </div>
                        <div class="relative flex items-start">
                            <div class="flex items-center h-5">
                                @if ($mop == 'Others')
                                    <input id="offers" aria-describedby="offers-description" name="offers" type="checkbox" class="w-4 h-4 border-black text-primary-500 focus:ring-primary-500" readonly disabled checked>
                                @else
                                    <input id="offers" aria-describedby="offers-description" name="offers" type="checkbox" class="w-4 h-4 border-black text-primary-500 focus:ring-primary-500" readonly disabled>
                                @endif

                            </div>
                            <div class="ml-1 text-sm">
                                <label class="font-medium text-black">Others (Please Specify):</label>

                            </div>
                        </div>
                        @if ($mop == 'Others')
                            <div class="relative flex items-start">

                                <div class="ml-1 text-sm">
                                    <span class="font-medium text-black">{{ $evaluate(fn($get) => $get('other_reason')) }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex items-start min-w-full col-span-8 font-serif border-t-2 border-black">

                    <div class="flex h-full px-2 py-1 text-center border-r-2 border-black">
                        <span class="my-auto text-sm font-extrabold">Payee</span>
                    </div>
                    <div class="flex w-1/2 h-full text-left border-r-2 border-black">
                        <span class="flex pl-2 my-auto font-extrabold uppercase print:text-10 text-serif">
                            {{ $evaluate(fn($get) => $get('employee_id')) }} </span>
                    </div>
                    <div class="flex w-64 h-full px-2 py-1 text-left border-r-2 border-black">
                        <span class="pb-3 text-xs font-extrabold">TIN/Employee No.:</span>
                    </div>
                    <div class="flex h-full px-2 py-1 text-left w-60">
                        <span class="pb-3 text-xs font-extrabold">ORS/BURS No.:</span>
                    </div>

                </div>
                <div class="flex items-start min-w-full col-span-8 font-serif border-t-2 border-black">
                    <div class="flex h-full px-2 py-1 text-center border-r-2 border-black">
                        <span class="my-auto text-sm font-extrabold">Address</span>
                    </div>
                </div>
                {{-- Particulars Heading --}}
                <div class="flex items-start min-w-full col-span-8 font-serif border-t-2 border-black print:text-12">
                    <div class="w-1/2 h-auto text-center border-r-2 border-black">
                        Particulars
                    </div>
                    <div class="w-64 h-auto text-center border-r-2 border-black">
                        Responsibility Center
                    </div>
                    <div class="h-auto text-center border-r-2 border-black w-36">
                        MFO/PAP
                    </div>
                    <div class="h-auto text-center w-36">
                        Amount
                    </div>
                </div>
                <div class="flex items-start min-w-full col-span-8 font-serif border-t-2 border-black print:text-10">
                    <div class="w-1/2 text-center border-r-2 border-black h-44">
                        {{ $evaluate(fn($get) => $get('activity_name')) }}
                    </div>
                    <div class="w-64 text-center border-r-2 border-black h-44">
                        {{ $evaluate(fn($get) => $get('responsibility_center')) }}
                    </div>
                    <div class="text-center border-r-2 border-black h-44 w-36">
                        {{ $evaluate(fn($get) => $get('mfo_pap')) }}
                    </div>
                    <div class="text-right h-44 w-36">
                        {{ $evaluate(fn($get) => $get('amount')) }}
                    </div>
                </div>
                <div class="flex items-start min-w-full col-span-8 font-serif border-black print:text-12">
                    <div class="w-1/2 h-auto text-center border-t-2 border-r-2 border-black">
                        Amount Due
                    </div>
                    <div class="flex w-64 h-auto text-center border-r-2 border-black">
                        &nbsp
                    </div>
                    <div class="h-auto text-center border-r-2 border-black w-36">
                        &nbsp
                    </div>
                    <div class="h-auto text-right border-t-2 border-black print:text-10 w-36">
                        {{ $evaluate(fn($get) => $get('amount')) }}
                    </div>
                </div>
                <div class="flex items-start min-w-full col-span-8 font-serif border-t-2 border-black">
                    <div class="w-full">
                        <div class="flex row-span-1">
                            <div class="px-1 font-extrabold border-b border-r border-black print:text-12">A.</div>
                            <span class="pl-1 font-extrabold print:text-12">Certified: Expenses/Cash Advance necessary, lawful and incurred
                                under my direct supervision.</span>
                        </div>
                        <div class="block row-span-1 mx-auto text-center">
                            @php
                                $signatory_details;
                                $full_name;
                                if ($evaluate(fn($get) => $get('signatory_id')) != null) {
                                    $signatory_details = \App\Models\EmployeeInformation::firstWhere('user_id', $evaluate(fn($get) => $get('signatory_id')));
                                    $temp;
                                    if (isset($signatory_details)) {
                                        $temp = explode(',', $signatory_details->full_name);
                                    } else {
                                        $temp = ['none'];
                                    }
                                
                                    $full_name = $temp[0];
                                }
                            @endphp
                            <span
                                class="font-extrabold underline uppercase print:text-10">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                {{ isset($full_name) ? $full_name : 'none' }}
                                &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</span>
                            <p class="font-extrabold capitalize print:text-10">
                                {{ isset($signatory_details) ? $signatory_details->position->description : 'none' }} <span class="lowercase">of</span>
                                {{ isset($signatory_details) ? $signatory_details->office->name : 'none' }}</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-start min-w-full col-span-8 font-serif border-t-2 border-black">
                    <div class="flex">
                        <div class="px-1 font-extrabold border-b border-r border-black print:text-12">B.</div>
                        <span class="pl-1 font-extrabold print:text-12">Accounting Entry:</span>
                    </div>
                </div>
                <div class="flex items-start min-w-full col-span-8 font-serif border-t-2 border-black print:text-12">
                    <div class="w-1/2 h-auto text-center border-r-2 border-black">
                        Account Title
                    </div>
                    <div class="h-auto text-center border-r-2 border-black w-72">
                        UACS Code
                    </div>
                    <div class="h-auto text-center border-r-2 border-black w-28">
                        Debit
                    </div>
                    <div class="h-auto text-center w-28">
                        Credit
                    </div>
                </div>
                <div class="flex items-start min-w-full col-span-8 font-serif border-t-2 border-black print:text-12">
                    <div class="w-1/2 text-center border-r-2 border-black h-44">
                        &nbsp
                    </div>
                    <div class="text-center border-r-2 border-black h-44 w-72">
                        &nbsp
                    </div>
                    <div class="text-center border-r-2 border-black h-44 w-28">
                        &nbsp
                    </div>
                    <div class="text-center h-44 w-28">
                        &nbsp
                    </div>
                </div>
                <div class="flex items-start min-w-full col-span-8 font-serif border-t-2 border-black print:text-12">
                    <div class="w-1/2 border-r-2 border-black">
                        <div class="flex">
                            <div class="px-1 font-extrabold border-b border-r border-black print:text-12">C.</div>
                            <span class="pl-1 font-extrabold print:text-12">Certified:</span>
                        </div>
                    </div>
                    <div class="w-1/2 border-r-2 border-black">
                        <div class="flex">
                            <div class="px-1 font-extrabold border-b border-r border-black print:text-12">D.</div>
                            <span class="pl-1 font-extrabold print:text-12">Approved for Payment:</span>
                        </div>
                    </div>

                </div>
                <div class="flex items-start min-w-full col-span-8 font-serif border-t-2 border-black print:text-12">

                    <div class="w-1/2 space-y-1 border-r-2 border-black print:text-8">
                        <div class="flex items-center mt-1">
                            <div class="w-8 h-3 mx-2 border border-black"></div>
                            <span>Cash available</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-3 mx-2 border border-black"></div>
                            <span>Subject to Authority to Debit Account (when applicable)</span>
                        </div>
                        <div class="flex items-center mb-1">
                            <div class="w-8 h-3 mx-2 border border-black"></div>
                            <span>Supporting documents complete and amount claimed proper</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-start min-w-full col-span-8 font-serif border-t-2 border-black print:text-12">
                    <div class="w-1/2 space-y-1 border-r-2 border-black print:text-8">
                        <div class="flex w-20 h-auto text-center border-r border-black print:h-8 print:w-16">
                            <span class="flex mx-auto my-auto print:text-12">Signature</span>
                        </div>
                    </div>
                    <div class="w-1/2 space-y-1 print:text-8">
                        <div class="flex w-20 h-auto text-center border-r border-black print:h-8 print:w-16">
                            <span class="flex mx-auto my-auto print:text-12">Signature</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-start min-w-full col-span-8 font-serif border-t-2 border-black print:text-12">
                    <div class="flex items-center w-1/2 space-y-1 text-center border-r-2 border-black print:text-8">
                        <div class="flex w-20 h-auto text-center border-r border-black print:h-8 print:w-16">
                            <span class="w-full break-words print:text-12">Printed Name</span>
                        </div>
                        <span class="flex mx-auto my-auto font-extrabold uppercase print:text-10">JESHER Y. PALOMARIA</span>
                    </div>
                    <div class="flex items-center w-1/2 space-y-1 text-center border-r-2 border-black print:text-8">
                        <div class="flex w-20 h-auto text-center border-r border-black print:h-8 print:w-16">
                            <span class="w-full break-words print:text-12">Printed Name</span>
                        </div>
                        <span class="flex mx-auto my-auto font-extrabold uppercase print:text-10">SAMSON L. MOLAO</span>
                    </div>
                </div>
                <div class="flex items-start min-w-full col-span-8 font-serif border-t-2 border-black print:text-12">

                    <div class="flex w-1/2 space-y-1 text-center border-r-2 border-black print:text-8">
                        <div class="flex w-20 h-auto text-center border-r border-black shrink-0 print:h-8 print:w-16">
                            <span class="flex mx-auto my-auto print:text-12">Position</span>
                        </div>

                        <div class="w-full h-auto text-center print:h-8">
                            <div class="w-full h-4 border-b border-black">
                                <span class="block mx-auto my-auto text-xs font-extrabold uppercase print:text-8">University Accountant</span>
                            </div>
                            <div class="w-full h-4">
                                <span class="block mx-auto my-auto text-xs font-extrabold uppercase print:text-8">Head, Accounting
                                    Unit/Authorized Representative</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex w-1/2 space-y-1 text-center border-r-2 border-black print:text-8">
                        <div class="flex w-20 h-auto text-center border-r border-black shrink-0 print:h-8 print:w-16">
                            <span class="flex mx-auto my-auto print:text-12">Position</span>
                        </div>
                        <div class="w-full h-auto text-center print:h-8">
                            <div class="w-full h-4 border-b border-black">
                                <span class="block mx-auto my-auto text-xs font-extrabold uppercase print:text-8">University President</span>
                            </div>
                            <div class="w-full h-4">
                                <span class="block mx-auto my-auto text-xs font-extrabold uppercase print:text-8">Agency Head/Authorized
                                    Representative</span>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="flex items-start min-w-full col-span-8 font-serif border-t-2 border-black print:text-12">
                    <div class="w-1/2 space-y-1 border-r-2 border-black print:text-8">
                        <div class="flex w-20 h-auto text-center border-r border-black print:h-8 print:w-16">
                            <span class="flex mx-auto my-auto print:text-12">Date</span>
                        </div>
                    </div>
                    <div class="w-1/2 space-y-1 print:text-8">
                        <div class="flex w-20 h-auto text-center border-r border-black print:h-8 print:w-16">
                            <span class="flex mx-auto my-auto print:text-12">Date</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-start min-w-full col-span-8 font-serif border-t-2 border-black print:text-12">
                    <div class="flex-col w-full">
                        <div class="flex w-full border-b-2 border-black">
                            <div class="px-1 font-extrabold border-r border-black print:text-12">E.</div>
                            <span class="pl-1 font-extrabold print:text-12">Receipt of Payment</span>
                        </div>
                        <div class="flex flex-row w-full border-b-2 border-black">
                            <div class="w-20 h-auto px-1 text-xs font-extrabold border-r border-black print:text-10 shrink-0 print:w-20">Check / ADA No.: </div>
                            <div class="w-1/3 h-auto border-r border-black shrink-0">
                                <div class="h-5"></div>
                            </div>
                            <div class="w-full h-auto px-1 text-xs font-extrabold border-r border-black print:text-10">Date: </div>
                            <div class="w-full h-auto px-1 text-xs font-extrabold border-r border-black print:text-10">Bank Name & Account Number</div>
                        </div>
                    </div>
                    <div class="float-left w-1/6 h-full border-l border-black shrink-0">
                        <div class="flex text-left print:text-12">
                            JEV No.
                        </div>
                    </div>
                </div>
                <div class="flex items-start min-w-full col-span-8 font-serif border-t-2 border-black print:text-12">
                    <div class="flex-col w-full">
                        <div class="flex flex-row w-full border-b-2 border-black">
                            <div class="w-20 h-auto px-1 text-xs font-extrabold border-r border-black print:text-10 shrink-0 print:w-20">Signature </div>
                            <div class="w-1/3 h-auto border-r border-black shrink-0">
                                <div class="h-5"></div>
                            </div>
                            <div class="w-full h-auto px-1 text-xs font-extrabold border-r border-black print:text-10">Date: </div>
                            <div class="w-full h-auto px-1 text-xs font-extrabold border-r border-black print:text-10">Printed Name:</div>
                        </div>
                        <div class="w-full">
                            <span class="pl-1 font-extrabold print:text-12">Official Receipt No. & Date/Other Documents</span>
                        </div>
                    </div>
                    <div class="float-left w-1/6 h-full border-l border-black shrink-0">
                        <div class="flex text-left print:text-12">
                            Date
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <button type="button" onclick="printDiv('dvPrint')"
            class="inline-flex items-center px-4 py-2 mt-2 text-xs font-medium text-white border border-transparent rounded-md shadow-sm bg-primary-500 hover:bg-primary-300 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
            <!-- Heroicon name: mini/envelope -->

            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 mr-2 -ml-1">
                <path fill-rule="evenodd"
                    d="M7.875 1.5C6.839 1.5 6 2.34 6 3.375v2.99c-.426.053-.851.11-1.274.174-1.454.218-2.476 1.483-2.476 2.917v6.294a3 3 0 003 3h.27l-.155 1.705A1.875 1.875 0 007.232 22.5h9.536a1.875 1.875 0 001.867-2.045l-.155-1.705h.27a3 3 0 003-3V9.456c0-1.434-1.022-2.7-2.476-2.917A48.716 48.716 0 0018 6.366V3.375c0-1.036-.84-1.875-1.875-1.875h-8.25zM16.5 6.205v-2.83A.375.375 0 0016.125 3h-8.25a.375.375 0 00-.375.375v2.83a49.353 49.353 0 019 0zm-.217 8.265c.178.018.317.16.333.337l.526 5.784a.375.375 0 01-.374.409H7.232a.375.375 0 01-.374-.409l.526-5.784a.373.373 0 01.333-.337 41.741 41.741 0 018.566 0zm.807-3.97a.75.75 0 01.75-.75h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H18a.75.75 0 01-.75-.75V10.5zM15 9.75a.75.75 0 00-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 00.75-.75V10.5a.75.75 0 00-.75-.75H15z"
                    clip-rule="evenodd" />
            </svg>

            Print Voucher
        </button>
        <style>
            @page {
                size: auto;
                size: A4;
                margin: 0mm;
            }
        </style>
    </div>

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
</x-forms::field-wrapper>
