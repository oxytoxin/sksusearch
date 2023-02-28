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
    <div class="bg-white p-4 rounded-lg font-serif print:block print:rounded-none" x-ref="dvPrint">
        <div class="text-center w-full">
            <h1 class="text-sm font-extrabold tracking-wide">ITINERARY OF TRAVEL</strong></h1>
            <div class="flex justify-between items-end mt-8">
                <div class="space-y-4 text-left">
                    <div>Entity name: <span class="font-extrabold capitalize">SKSU</span></div>
                    <div>Fund Cluster:
                        <span class="font-extrabold capitalize">
                            {{ isset($travel_order->disbursement_voucher->fund_cluster->name) ? $travel_order->disbursement_voucher->fund_cluster->name : '' }}
                        </span>
                    </div>
                </div>
                <div class="space-y-2 flex flex-col mr-16 min-w-[16rem] items-end">
                    <div class="">
                        <img class="w-auto mx-auto h-14" src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $travel_order->tracking_code }}" alt="tracking code">
                        <span class="text-center text-xs font-normal">{{ $travel_order->tracking_code }}</span>
                    </div>
                    <p class="self-start">No.:</p>
                </div>
            </div>
            <table class="w-full border-black border">
                <thead class="table-header-row">
                    <tr class="print:text-12">
                        <th class="border border-black w-1/2 align-baseline text-left font-normal p-2" colspan="5">
                            <div class="space-y-4">
                                <p>Name:
                                    <span class="font-extrabold">{{ $itinerary->user->name }}</span>
                                </p>

                                <p>Position:
                                    <span class="font-extrabold">{{ $itinerary->user->employee_information->position->description }}</span>
                                </p>

                                <p>Official Station:
                                    <span class="font-extrabold">{{ $itinerary->user->employee_information->office->name }}</span>
                                </p>
                            </div>
                        </th>
                        <th class="border border-black w-1/2 align-baseline text-left font-normal p-2" colspan="5">
                            <div class="space-y">
                                <p>Date of Travel:
                                    <span class="font-extrabold">
                                        {{ $travel_order->date_from->format('M d, Y') }} to {{ $travel_order->date_to->format('M d, Y') }}
                                    </span>
                                </p>
                                <p>Purpose of Travel:</p>
                                <p class="font-extrabold whitespace-pre-line text-justify">
                                    {{ $itinerary->purpose != null ? $itinerary->purpose : $travel_order->purpose }}
                                </p>
                            </div>
                        </th>
                    </tr>
                    <tr class="text-sm border border-black">
                        <th class="font-normal border border-black" rowspan="2">Date</th>
                        <th class="font-normal border border-black" colspan="2" rowspan="2">Place to be visited (Destination)</th>
                        <th class="font-normal border border-black" colspan="2">Time</th>
                        <th class="font-normal border border-black" rowspan="2">Means of Transportation</th>
                        <th class="font-normal border border-black" rowspan="2">Transportation Exp</th>
                        <th class="font-normal border border-black" rowspan="2">Per Diem</th>
                        <th class="font-normal border border-black" rowspan="2">Others</th>
                        <th class="font-normal border border-black" rowspan="2">Total Amount</th>
                    </tr>
                    <tr class="text-sm border border-black">
                        <th class="font-normal px-2 border border-black">Departure</th>
                        <th class="font-normal px-2 border border-black">Arrival</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @php
                        $per_diem_total = 0;
                        $transportation_expenses_total = 0;
                        $other_expenses_total = 0;
                    @endphp
                    @if ($travel_order->has_registration)
                        <tr class="text-right my-2 print:text-12">
                            <td class="py-2 border-x border-black"></td>
                            <td class="py-2 text-center px-2 border-x border-black" colspan="2">Registration Amount</td>
                            <td class="py-2 border-x border-black"></td>
                            <td class="py-2 border-x border-black"></td>
                            <td class="py-2 border-x border-black"></td>
                            <td class="py-2 border-x border-black"></td>
                            <td class="py-2 border-x border-black"></td>
                            <td class="py-2 border-x px-1 border-black">{{ $travel_order->registration_amount ? number_format($travel_order->registration_amount, 2) : '' }}</td>
                            <td class="py-2 border-x px-1 border-black">{{ $travel_order->registration_amount ? number_format($travel_order->registration_amount, 2) : '' }}</td>
                        </tr>
                        @php
                            $other_expenses_total += $travel_order->registration_amount;
                        @endphp
                    @endif
                    @foreach ($itinerary->coverage as $coverage)
                        <tr class="border print:text-12 border-black">
                            <td class="border-x align-baseline py-2 whitespace-nowrap px-4 border-black">{{ date_format(date_create($coverage['date']), 'M d, Y') }}</td>
                            <td class="border-x py-2 border-black" colspan="2">-</td>
                            <td class="border-x py-2 border-black">-</td>
                            <td class="border-x py-2 border-black">-</td>
                            <td class="border-x py-2 border-black">-</td>
                            <td class="border-x py-2 border-black">-</td>
                            <td class="text-right px-1 align-baseline border-x py-2 border-black">{{ number_format($coverage['per_diem'] ?? 0, 2) }}</td>
                            <td class="border-x py-2 border-black">-</td>
                            <td class="text-right px-1 align-baseline border-x py-2 border-black">{{ number_format($coverage['per_diem'] ?? 0, 2) }}</td>
                        </tr>
                        @php
                            $per_diem_total += $coverage['per_diem'] ?? 0;
                        @endphp
                        @foreach (collect($itinerary_entries)->where('date', Carbon\Carbon::make($coverage['date'])) as $itinerary_entry)
                            <tr class="text-right border print:text-12 border-black">
                                <td class="border-x py-2 align-baseline whitespace-nowrap border-black text-center">{{ $itinerary_entry->date->format('M d, Y') }}</td>
                                <td class="border-x min-w-[12rem] py-2 align-baseline border-black text-center" colspan="2">{{ $itinerary_entry->place }}</td>
                                <td class="border-x px-4 py-2 align-baseline border-black whitespace-nowrap text-center">{{ $itinerary_entry->departure_time?->format('g:i A') }}</td>
                                <td class="border-x px-4 py-2 align-baseline border-black whitespace-nowrap text-center">{{ $itinerary_entry->arrival_time?->format('g:i A') }}</td>
                                <td class="border-x py-2 align-baseline border-black text-center">{{ $itinerary_entry->mot?->name }}</td>
                                <td class="border-x py-2 px-1 align-baseline border-black whitespace-nowrap">
                                    {{ $itinerary_entry->transportation_expenses ? number_format($itinerary_entry->transportation_expenses, 2) : '' }}
                                </td>
                                <td class="border-x py-2 align-baseline border-black "></td>
                                <td class="border-x py-2 px-1 align-baseline border-black whitespace-nowrap">
                                    {{ $itinerary_entry->other_expenses ? number_format($itinerary_entry->other_expenses, 2) : '' }}
                                </td>
                                @php
                                    $transportation_expenses_total += $itinerary_entry->transportation_expenses ?? 0;
                                    $other_expenses_total += $itinerary_entry->other_expenses ?? 0;
                                    $row_total = ($itinerary_entry->transportation_expenses ?? 0) + ($itinerary_entry->other_expenses ?? 0);
                                @endphp
                                <td class="text-right align-baseline py-2 px-1">{{ number_format($row_total, 2) }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                    @php
                        $grand_total = $per_diem_total + $transportation_expenses_total + $other_expenses_total;
                    @endphp
                    <tr class="border border-black text-right print:text-12">
                        <td class="border-t-4 py-2 border-black italic px-4 border-x" colspan="6">Total:</td>
                        <td class="border-t-4 border-x border-double py-2 border-black">{{ number_format($transportation_expenses_total, 2) }}</td>
                        <td class="text-right border-x px-1 align-baseline border-t-4 border-double py-2 border-black">{{ number_format($per_diem_total, 2) }}</td>
                        <td class="border-t-4 border-x border-double py-2 border-black">{{ number_format($other_expenses_total, 2) }}</td>
                        <td class="text-right border-x px-1 align-baseline border-t-4 border-double py-2 border-black">{{ number_format($grand_total, 2) }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="flex pt-32 justify-evenly w-full">
                <div class="col-start-2 text-center">
                    <div class="flex flex-col w-full pt-5 border-t-2 border-black px-16">
                        <span class="font-extrabold capitalize print:text-sm">{{ $itinerary->user->name }}</span>
                        <span class="text-xs font-extrabold capitalize print:text-12">{{ $itinerary->user->employee_information->position->description }}
                            <span class="lowercase">of</span>
                            {{ $itinerary->user->employee_information->office->name }}</span>
                    </div>
                </div>
                <div class="col-start-7 text-center">
                    <div class="flex flex-col w-full pt-5 border-t-2 border-black px-16">
                        <span class="font-extrabold capitalize print:text-sm">{{ $immediate_signatory->name }}</span>
                        <span class="text-xs font-extrabold capitalize print:text-12">{{ $immediate_signatory->employee_information->position->description }}
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
</div>
