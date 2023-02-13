<div class="p-8" x-data>
    <div class="flex justify-end w-full px-2">
        <button class="flex px-4 py-2 text-center rounded-md bg-primary-700 text-primary-100 hover:bg-primary-900 hover:shadow-primary-600 hover:shadow-sm" @click="printOut($refs.dvPrint.outerHTML)">
            <svg class="w-5 h-5 my-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" />
            </svg>
            <span class="my-auto ml-2 text-sm">Print</span>
        </button>
    </div>
    <div class="rounded-lg bg-white font-serif print:block print:h-[297mm] print:max-h-[297mm] print:w-[220mm] print:max-w-[220mm] print:rounded-none print:font-serif" x-ref="dvPrint">
        <div class="grid grid-cols-10 p-2 m-2 border-collapse print:text-12">
            <div class="col-span-10 text-center"><strong class="text-sm font-extrabold tracking-wide uppercase print:text-sm">Itinerary of Travel</strong>
            </div>
            <div class="col-span-8">&nbsp</div>
            <div class="col-span-2 col-start-9 row-span-2">
                <div class="flex flex-col">
                    <img class="w-auto mx-auto h-14" src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $travel_order->tracking_code }}" alt="N/A">
                    <span class="flex justify-center text-xs font-normal">{{ $travel_order->tracking_code }}</span>
                </div>
            </div>
            <div class="col-span-8">Entity name: <span class="font-extrabold capitalize">SKSU</span></div>
            <div class="col-span-7">Fund Cluster: <span
                      class="font-extrabold capitalize">{{ isset($travel_order->disbursement_voucher->fund_cluster->name) ? $travel_order->disbursement_voucher->fund_cluster->name : '' }}</span>
            </div>
            <div class="col-span-3">No:</div>
            <div class="col-span-5 px-2 py-1 border-t border-black border-x">Name: <span class="font-extrabold">{{ $itinerary->user->name }}</span></div>
            <div class="col-span-5 px-2 py-1 border-t border-r border-black">Date of Travel: <span class="font-extrabold print:text-10">{{ $travel_order->date_from->format('M d, Y') }} to
                    {{ $travel_order->date_to->format('M d, Y') }}</span></div>
            <div class="col-span-5 px-2 py-1 border-black border-x">Position: <span class="font-extrabold">{{ $itinerary->user->employee_information->position->description }}</span>
            </div>
            <div class="col-span-5 row-span-2 px-2 py-1 border-r border-black">Purpose of Travel: <span
                      class="font-extrabold whitespace-pre-line">{{ $itinerary->purpose != null ? $itinerary->purpose : $travel_order->purpose }}</span>
            </div>
            <div class="col-span-5 px-2 py-1 border-black border-x">Official Station: <span class="font-extrabold">{{ $itinerary->user->employee_information->office->name }}</span></div>

            {{-- columns --}}
            {{-- headers --}}
            <div class="flex items-center col-span-1 row-span-2 text-center border-black border-y border-x">
                <div class="mx-auto my-auto">Date</div>
            </div>
            <div class="flex col-span-2 row-span-2 text-center break-words border-r border-black border-y">
                <div class="mx-auto my-auto text-sm print:text-12">Places to be visited (Destination)</div>
            </div>
            <div class="grid justify-center grid-cols-2 col-span-2 grid-rows-2 row-span-2 text-sm text-center border-r border-black print:text-12 border-y">
                <div class="col-span-2 row-span-1 tracking-wider border-b border-black">TIME</div>
                <div class="col-span-1 row-span-1 border-r border-black">Departure</div>
                <div class="col-span-1 row-span-1">Arrival</div>
            </div>
            <div class="flex col-span-1 row-span-2 text-center break-all border-r border-black border-y">
                <div class="p-1 mx-auto my-auto text-sm print:text-12">Means of Transportation</div>
            </div>
            <div class="flex col-span-1 row-span-2 text-center break-all border-r border-black border-y">
                <div class="p-1 mx-auto my-auto text-sm print:text-12">Transportation Exp</div>
            </div>
            <div class="flex col-span-1 row-span-2 text-center break-all border-r border-black border-y">
                <div class="mx-auto my-auto text-sm">Per Diem</div>
            </div>
            <div class="flex col-span-1 row-span-2 text-center break-all border-r border-black border-y">
                <div class="mx-auto my-auto text-sm">Others</div>
            </div>
            <div class="flex col-span-1 row-span-2 text-center break-words border-r border-black border-y">
                <div class="mx-auto my-auto text-sm">Total Amount</div>
            </div>
            <div class="grid grid-cols-10 col-span-10 text-center border-b border-black print:text-12 border-x">
                @if ($travel_order->has_registration)
                    <div class="col-span-1 px-2 py-1 border-r border-black">

                    </div>
                    <div class="col-span-2 px-2 py-1 border-r border-black"> Registration Amount </div>
                    <div class="col-span-1 px-2 py-1 border-r border-black">
                    </div>
                    <div class="col-span-1 px-2 py-1 border-r border-black">
                    </div>
                    <div class="col-span-1 px-2 py-1 border-r border-black"></div>
                    <div class="col-span-1 px-2 py-1 text-right border-r border-black">
                    </div>
                    <div class="col-span-1 px-2 py-1 text-right border-r border-black">
                    </div>
                    <div class="col-span-1 px-2 py-1 text-right border-r border-black">
                        {{ $travel_order->registration_amount }}
                    </div>
                    <div class="col-span-1 px-2 py-1 text-right">
                        {{ $travel_order->registration_amount }}
                    </div>
                @endif
                @php
                    $trans_exp = 0.0;
                    $per_diem = 0.0;
                    $others = 0.0;
                    $total_amount = $travel_order->registration_amount;
                @endphp
                @foreach ($itinerary->coverage as $coverage)
                    <div class="col-span-1 px-2 py-1 border-r border-black">{{ date_format(date_create($coverage['date']), 'M d, Y') }}
                        -
                    </div>
                    <div class="col-span-2 px-2 py-1 border-r border-black"> - </div>
                    <div class="col-span-1 px-2 py-1 border-r border-black">
                        -</div>
                    <div class="col-span-1 px-2 py-1 border-r border-black">
                        -</div>
                    <div class="col-span-1 px-2 py-1 border-r border-black"></div>
                    <div class="col-span-1 px-2 py-1 text-right border-r border-black">
                        -</div>
                    <div class="col-span-1 px-2 py-1 text-right border-r border-black">
                        {{ $coverage['per_diem'] }}</div>
                    <div class="col-span-1 px-2 py-1 text-right border-r border-black">
                        -
                    </div>
                    <div class="col-span-1 px-2 py-1 text-right">
                        {{ $coverage['per_diem'] }}
                    </div>
                    @php
                        $per_diem = $coverage['per_diem'] + $per_diem;
                    @endphp

                    @foreach ($itinerary_entries as $entry)
                        @if ($entry->date?->format('Y-m-d') == $coverage['date'])
                            <div class="col-span-1 px-2 py-1 border-r border-black">{{ $entry->date?->format('M d, Y') }}
                            </div>
                            <div class="col-span-2 px-2 py-1 border-r border-black">{{ $entry->place }}</div>
                            <div class="col-span-1 px-2 py-1 border-r border-black">
                                {{ $entry->departure_time?->format('g:i A') }}</div>
                            <div class="col-span-1 px-2 py-1 border-r border-black">
                                {{ $entry->arrival_time?->format('g:i A') }}</div>
                            <div class="col-span-1 px-2 py-1 border-r border-black">{{ $entry->mot?->name }}</div>
                            <div class="col-span-1 px-2 py-1 text-right border-r border-black">
                                {{ $entry->transportation_expenses }}</div>
                            <div class="col-span-1 px-2 py-1 text-right border-r border-black"></div>
                            <div class="col-span-1 px-2 py-1 text-right border-r border-black">{{ $entry->other_expenses }}
                            </div>
                            <div class="col-span-1 px-2 py-1 text-right">
                                {{ $itinerary->coverage[0]['per_diem'] + $entry->other_expenses + $entry->transportation_expenses }}
                            </div>
                            @php
                                $trans_exp = $entry->transportation_expenses + $trans_exp;
                                $others = $entry->other_expenses + $others;
                            @endphp
                        @endif
                    @endforeach
                @endforeach

                <div class="col-span-6 px-2 py-1 text-right border-t-4 border-r border-black">
                    <span class="italic tracking-wider">Total:</span>
                </div>
                <div class="col-span-1 px-2 py-1 text-right border-t-4 border-r border-black border-double">
                    {{ $trans_exp }}
                </div>
                <div class="col-span-1 px-2 py-1 text-right border-t-4 border-r border-black border-double">
                    {{ $per_diem }}
                </div>
                <div class="col-span-1 px-2 py-1 text-right border-t-4 border-r border-black border-double">
                    {{ $others }}
                </div>
                <div class="col-span-1 px-2 py-1 text-right border-t-4 border-black border-double">
                    {{ $total_amount = $trans_exp + $others + $per_diem + $total_amount }}
                </div>
            </div>

            <div class="col-span-10">&nbsp</div>
            <div class="col-span-10">&nbsp</div>
            <div class="col-span-10">&nbsp</div>
            <div class="col-span-10">&nbsp</div>
            <div class="col-span-10">&nbsp</div>

            <div class="col-span-3 col-start-2 text-center">
                <div class="flex flex-col w-full pt-5 border-t-2 border-black">
                    <span class="font-extrabold capitalize print:text-sm">{{ $itinerary->user->name }}</span>
                    <span class="text-xs font-extrabold capitalize print:text-10">{{ $itinerary->user->employee_information->position->description }}
                        <span class="lowercase">of</span>
                        {{ $itinerary->user->employee_information->office->name }}</span>
                </div>
            </div>

            <div class="col-span-3 col-start-7 text-center">
                <div class="flex flex-col w-full pt-5 border-t-2 border-black">
                    <span class="font-extrabold capitalize print:text-sm">{{ $immediate_signatory->name }}</span>
                    <span class="text-xs font-extrabold capitalize print:text-10">{{ $immediate_signatory->employee_information->position->description }}
                        <span class="lowercase">of</span>
                        {{ $immediate_signatory->employee_information->office->name }}</span>
                </div>
            </div>
        </div>
    </div>
    <script>
        function printOut(data) {
            var mywindow = window.open('', 'Print Itinerary', 'height=1000,width=1000');
            mywindow.document.write('<html><head>');
            mywindow.document.write('<title>Print Itinerary</title>');
            mywindow.document.write(`<link rel="stylesheet" href="{{ Vite::asset('resources/css/app.css') }}" />`);
            mywindow.document.write('</head><body >');
            mywindow.document.write(data);
            mywindow.document.write('</body></html>');
            mywindow.document.close();
            mywindow.focus();
            setTimeout(() => {
                mywindow.print();
            }, 1000);
            return false;
        }
    </script>
</div>
