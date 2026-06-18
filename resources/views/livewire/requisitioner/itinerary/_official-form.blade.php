<div class="mx-auto w-full max-w-[13in] bg-white font-serif text-[11px] leading-[1.12] text-black print:max-w-none print:text-10 print:[-webkit-print-color-adjust:exact] print:[print-color-adjust:exact]">
    <table class="w-full table-fixed border-collapse">
        <colgroup>
            <col class="w-[7.5%]">
            <col class="w-[28.5%]">
            <col class="w-[7.25%]">
            <col class="w-[7.75%]">
            <col class="w-[13%]">
            <col class="w-[9%]">
            <col class="w-[9%]">
            <col class="w-[9%]">
            <col class="w-[9%]">
        </colgroup>
        <tbody>
        <tr>
            <td class="border-0 text-right text-[13px] italic" colspan="9">Appendix 45</td>
        </tr>
        <tr>
            <td class="h-5 border-0 text-center text-[14px] font-bold" colspan="9">ITINERARY OF TRAVEL</td>
        </tr>
        <tr>
            <td class="h-7 border-0" colspan="9"></td>
        </tr>
        <tr>
            <td class="border-0 px-1 py-0.5 align-top" colspan="6">
                <span class="font-bold">Entity Name :</span>
                <span class="font-bold">SULTAN KUDARAT STATE UNIVERSITY</span>
            </td>
            <td class="border-0 px-1 py-0.5 align-top" colspan="3">
                <span class="font-bold">No.:</span>
                <span class="font-bold">{{ $itineraryForm['tracking_code'] }}</span>
            </td>
        </tr>
        <tr>
            <td class="border-0 px-1 py-0.5 align-top" colspan="9">
                <span class="font-bold">Fund Cluster:</span>
                {{ $itineraryForm['fund_cluster'] }}
            </td>
        </tr>
        </tbody>
    </table>

    <table class="w-full table-fixed border-collapse border-2 border-black">
        <colgroup>
            <col class="w-[7.5%]">
            <col class="w-[28.5%]">
            <col class="w-[7.25%]">
            <col class="w-[7.75%]">
            <col class="w-[13%]">
            <col class="w-[9%]">
            <col class="w-[9%]">
            <col class="w-[9%]">
            <col class="w-[9%]">
        </colgroup>
        <thead>
        <tr>
            <td class="border border-black px-1 py-0.5 align-top" colspan="4">
                <div><span class="font-bold">Name :</span> <span
                            class="font-bold">{{ $itineraryForm['traveler']['name'] }}</span></div>
                <div><span class="font-bold">Position :</span> {{ $itineraryForm['traveler']['position'] }}</div>
                <div><span class="font-bold">Official Station :</span> {{ $itineraryForm['traveler']['station'] }}</div>
            </td>
            <td class="border border-black px-1 py-0.5 align-top" colspan="5">
                <div>
                    <span class="font-bold">Date of Travel :</span>
                    {{ $itineraryForm['date_of_travel'] }}
                </div>
                <div><span class="font-bold">Purpose of Travel :</span> <span
                            class="whitespace-pre-line">{{ $itineraryForm['purpose'] }}</span></div>
            </td>
        </tr>
        <tr>
            <th class="border border-black px-1 py-0.5 text-center align-top font-bold" rowspan="2">Date</th>
            <th class="border border-black px-1 py-0.5 text-center align-top font-bold" rowspan="2">Places to be visited<br>(Destination)
            </th>
            <th class="border border-black px-1 py-0.5 text-center align-top font-bold" colspan="2">T I M E</th>
            <th class="border border-black px-1 py-0.5 text-center align-top font-bold" rowspan="2">Means of<br>Transportation
            </th>
            <th class="border border-black px-1 py-0.5 text-center align-top font-bold" rowspan="2">Transpor-<br>tation
            </th>
            <th class="border border-black px-1 py-0.5 text-center align-top font-bold" rowspan="2">Per<br>Diem</th>
            <th class="border border-black px-1 py-0.5 text-center align-top font-bold" rowspan="2">Others</th>
            <th class="border border-black px-1 py-0.5 text-center align-top font-bold" rowspan="2">Total Amount</th>
        </tr>
        <tr>
            <th class="border border-black px-1 py-0.5 text-center align-top font-bold">Departure</th>
            <th class="border border-black px-1 py-0.5 text-center align-top font-bold">Arrival</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($itineraryForm['rows'] as $row)
            <tr>
                <td class="h-[15px] border border-black px-1 py-0.5 text-center align-top">{{ $row['date'] }}</td>
                <td @class(['h-[15px] border border-black px-1 py-0.5 align-top', 'text-center' => $row['place'] === '-'])>{{ $row['place'] }}</td>
                <td class="h-[15px] border border-black px-1 py-0.5 text-center align-top">{{ $row['departure'] }}</td>
                <td class="h-[15px] border border-black px-1 py-0.5 text-center align-top">{{ $row['arrival'] }}</td>
                <td class="h-[15px] border border-black px-1 py-0.5 text-center align-top">{{ $row['means'] }}</td>
                <td class="h-[15px] whitespace-nowrap border border-black px-1 py-0.5 text-right align-top">{{ $row['transportation'] }}</td>
                <td class="h-[15px] whitespace-nowrap border border-black px-1 py-0.5 text-right align-top">{{ $row['per_diem'] }}</td>
                <td class="h-[15px] whitespace-nowrap border border-black px-1 py-0.5 text-right align-top">{{ $row['others'] }}</td>
                <td class="h-[15px] whitespace-nowrap border border-black px-1 py-0.5 text-right align-top">{{ $row['total'] }}</td>
            </tr>
        @endforeach

        @for ($i = 0; $i < $itineraryForm['blank_rows']; $i++)
            <tr>
                <td class="h-[15px] border border-black px-1 py-0.5 align-top"></td>
                <td class="h-[15px] border border-black px-1 py-0.5 align-top"></td>
                <td class="h-[15px] border border-black px-1 py-0.5 align-top"></td>
                <td class="h-[15px] border border-black px-1 py-0.5 align-top"></td>
                <td class="h-[15px] border border-black px-1 py-0.5 align-top"></td>
                <td class="h-[15px] border border-black px-1 py-0.5 align-top"></td>
                <td class="h-[15px] border border-black px-1 py-0.5 align-top"></td>
                <td class="h-[15px] border border-black px-1 py-0.5 align-top"></td>
                <td class="h-[15px] whitespace-nowrap border border-black px-1 py-0.5 text-right align-top">-</td>
            </tr>
        @endfor

        <tr>
            <td class="border border-black border-t-2 px-1 py-0.5 text-center align-top font-bold" colspan="5">TOTAL
            </td>
            <td class="whitespace-nowrap border border-black border-t-2 px-1 py-0.5 text-right align-top font-bold">{{ $itineraryForm['totals']['transportation'] }}</td>
            <td class="whitespace-nowrap border border-black border-t-2 px-1 py-0.5 text-right align-top font-bold">{{ $itineraryForm['totals']['per_diem'] }}</td>
            <td class="whitespace-nowrap border border-black border-t-2 px-1 py-0.5 text-right align-top font-bold">{{ $itineraryForm['totals']['others'] }}</td>
            <td class="whitespace-nowrap border border-black border-t-2 px-1 py-0.5 text-right align-top font-bold">{{ $itineraryForm['totals']['grand'] }}</td>
        </tr>
        <tr>
            <td class="h-[156px] border border-black px-1 py-0.5 text-center align-top" colspan="4"
                rowspan="{{ $itineraryForm['signatures']['right_rowspan'] }}">
                <p class="mt-7 text-justify [text-align-last:center]">
                    I certify that : (1) I have reviewed the foregoing itinerary, (2) the travel is necessary to the
                    service, (3) the period covered is reasonable and (4) the expenses claimed are proper.
                </p>
                <div class="mt-[66px] flex flex-col">
                    <span class="block text-left font-bold">Recommending Approval:</span>
                    <div class="relative h-12">
                        @if ($itineraryForm['signatures']['certifying']['signature'])
                            <x-esignature-block
                                    :signature="$itineraryForm['signatures']['certifying']['signature']"
                                    :signed-by="$itineraryForm['signatures']['certifying']['esign_name'] ?? null"
                                    :signed-at="$itineraryForm['signatures']['certifying']['approved_at'] ?? null"
                                    :show-info="$itineraryForm['signatures']['certifying']['signed_by_oic'] ?? false"
                                    width="12rem"
                                    max-height="6rem"
                                    bottom="0"
                                    info-offset-x="55%"
                                    info-offset-y="0"/>
                        @endif
                    </div>
                    <span class="inline-block min-w-[62%] border-b border-black px-2 pb-px">
                            {{ $itineraryForm['signatures']['certifying']['name'] }}
                        </span>
                    <span class="block text-10">Signature over Printed Name</span>
                    <span class="block text-10">{{ $itineraryForm['signatures']['certifying']['designation'] }}</span>
                </div>
            </td>
            <td class="border border-black px-1 py-0.5 align-top" colspan="5">
                <span class="font-bold">Prepared by :</span>
                <div class="text-center">
                        <span class="block h-12 text-center relative">
                            @if ($itineraryForm['traveler']['signature'])
                                <x-signature-block :signature="$itineraryForm['traveler']['signature']"
                                                   width="10rem"
                                                   max-height="3rem"
                                                   bottom="0"/>
                            @endif
                        </span>
                    <span class="inline-block min-w-[62%] border-b border-black px-2 pb-px">
                            {{ $itineraryForm['traveler']['name'] }}
                        </span>
                    <span class="block text-10">Signature over Printed Name</span>
                </div>
            </td>
        </tr>
        @foreach ($itineraryForm['signatures']['approving'] as $signatory)
            <tr>
                <td class="border border-black px-1 py-0.5 align-top" colspan="5">
                    <span class="font-bold">Approved:</span>
                    <div class="text-center">
                            <span class="block h-12 text-center relative">
                                @if ($signatory['signature'])
                                    <x-esignature-block
                                            :signature="$signatory['signature']"
                                            :signed-by="$signatory['esign_name'] ?? null"
                                            :signed-at="$signatory['approved_at'] ?? null"
                                            :show-info="$signatory['signed_by_oic'] ?? false"
                                            width="10rem"
                                            max-height="3rem"
                                            bottom="0"
                                            info-offset-x="64%"
                                            info-offset-y="0"/>
                                @endif
                            </span>
                        <span class="inline-block min-w-[62%] border-b border-black px-2 pb-px">
                                {{ $signatory['name'] }}
                            </span>
                        <span class="block text-10">Signature over Printed Name</span>
                        <span class="block text-10">{{ $signatory['designation'] }}</span>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
