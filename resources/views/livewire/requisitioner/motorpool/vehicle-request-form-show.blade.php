<div>

    <div id="print_to" class="col-span-2 print-bg-white">
        <div class="flex w-full justify-between border-b-4 border-black p-6 print:flex">
            <div id="header" class="ml-3 flex w-full text-left">
                <div class="my-auto inline"><img src="{{ asset('images/sksulogo.png') }}" alt="sksu logo"
                        class="h-full w-20 object-scale-down">
                </div>
                <div class="my-auto ml-3">
                    <div class="block">
                        <span class="text-left text-sm font-semibold tracking-wide text-black">Republic of the
                            Philippines</span>
                    </div>
                    <div class="block">
                        <span class="text-primary-600 text-left text-sm font-semibold uppercase tracking-wide">sultan
                            kudarat state university</span>
                    </div>
                    <div class="block">
                        <span class="text-sm font-semibold tracking-wide text-black">ACCESS, EJC Montilla, 9800 City of
                            Tacurong</span>
                    </div>
                    <div class="block">
                        <span class="text-sm font-semibold tracking-wide text-black">Province of Sultan Kudarat</span>
                    </div>
                </div>
            </div>
            <div class="relative right-0">
                <div class="m-auto">
                    {{-- <img src="https://api.qrserver.com/v1/create-qr-code/?data={{ $travel_order->tracking_code }}&amp;size=100x100"
                        alt="" title="" />
                    <span class="flex justify-center  text-[11px] font-xs">{{ $travel_order->tracking_code }}</span> --}}
                </div>
            </div>
        </div>

        <div class="w-full">
            <div class="m-2">
                <div class="flex h-auto w-full items-start px-6 pt-4 print:pb-0 print:block">
                    <div id="header" class="block w-full items-start space-y-2 text-left">
                        <div class="flex">
                            <span
                                class="mx-auto mb-6 text-lg font-extrabold uppercase tracking-wide text-black print:text-lg">Vehicle Request Form</span>
                        </div>
                        <div class="flex justify-between">
                            <div class="space-x-4">
                            <span class="text-sm font-semibold tracking-wide text-black">Requisitioner:</span>
                            <span class="text-sm font-semibold tracking-wide text-black underline">{{$request->requested_by->name}}</span>
                            </div>
                            <div class="space-x-5">
                            <span class="text-sm font-semibold tracking-wide text-black">Date:</span>
                            <span class="text-sm font-semibold tracking-wide text-black underline">{{\Carbon\Carbon::parse($request->created_at)->format('F d, Y')}}</span>
                            </div>
                        </div>
                        <div class="flex justify-start">
                        <div class="space-x-2">
                        <span class="text-sm font-semibold tracking-wide text-black">Date/Duration:</span>
                        <span class="text-sm font-semibold tracking-wide text-black underline">{{\Carbon\Carbon::parse($request->date_of_travel_from)->format('F d, Y')}} to
                            {{\Carbon\Carbon::parse($request->date_of_travel_to)->format('F d, Y')}}
                            @if($request->time_start != null || $request->time_end != null)
                            ({{\Carbon\Carbon::parse($request->time_start)->format('h:i A')}} - {{\Carbon\Carbon::parse($request->time_end)->format('h:i A')}})
                            @endif
                        </span>
                        </div>
                        </div>
                        <div class="flex justify-start">
                        <div class="space-x-5">
                        <span class="text-sm font-semibold tracking-wide text-black">Destination:</span>
                        <span class="text-sm font-semibold tracking-wide text-black underline">
                                &nbsp
                                @if ($request->other_details != '')
                                    {{ $request->other_details == null ? '' : $request->other_details }},
                                @endif
                                {{ $request->philippine_city_id == null ? 'City Not Set' : $request->philippine_city->city_municipality_description }},
                                {{ $request->philippine_province_id == null ? 'Province Not Set' : $request->philippine_province->province_description }},
                                {{ $request->philippine_region_id == null ? 'City Not Set' : $request->philippine_region->region_description }}
                                &nbsp
                        </span>
                        </div>
                        </div>
                        <div class="flex justify-start">
                        <div class="space-x-9">
                        <span class="text-sm font-semibold tracking-wide text-black">Purpose/s:</span>
                        <span class="text-sm font-semibold tracking-wide text-black underline">
                              {{$request->purpose}}
                        </span>
                        </div>
                        </div>
                        <div class="flex justify-start">
                        <div class="space-x-2 mb-20">
                        <span class="text-sm font-semibold tracking-wide text-black">List of Passengers:</span>
                        <span class="text-sm font-semibold tracking-wide text-black underline">
                                &nbsp
                                @foreach ($request->applicants as $key => $applicant)
                                {{ $applicant->employee_information->full_name }}
                                @if ($key != count($request->applicants) - 1)
                                    ,
                                @endif
                                @endforeach
                                &nbsp
                        </span>
                        </div>
                        </div>
                        <div class="mt-10">
                        <div class="flex -space-y-1 justify-end">
                            <div class="border-b border-black block w-1/2"></div>
                        </div>
                        <div class="flex -space-y-1 justify-end">
                            <div class=" w-1/2 text-center">(Signature)</div>
                        </div>
                        </div>

                        <div class="flex -space-y-1 justify-start my-20 pb-16">
                        <span class="text-md font-semibold tracking-wide text-black">APPROVED / DISSAPPROVED :</span>
                        </div>
                        <div class="flex-col">
                        <div class="flex justify-start">
                        <span class="text-md font-semibold tracking-wide underline text-black">SAMSON L. MOLAO, EdD</span>
                        </div>
                        <div class="flex justify-start">
                        <span class="text-md ml-3 tracking-wide text-black">University President</span>
                        </div>
                        <div class="flex -space-y-1 mb-10 justify-center">
                            REMARKS: 
                        </div>
                        </div>
                        
                        <div class="mt-16">
                        <div class="flex -space-y-1 mb-10 justify-end">
                            <div class="border-b border-black block w-1/2"></div>
                        </div>
                        <div class="flex -space-y-1 justify-end">
                            <div class="border-b border-black block w-1/2"></div>
                        </div>
                        </div>
                    </div>
                </div>
                <div id="contents" class="flex h-auto w-full px-6 ml-2 pt-2 print:pt-1 print:text-12">
              
                </div>

            </div>
            <div class="m-2">

                <div id="contents" class="flex h-auto w-full px-6 ml-2 pt-2 print:pt-1 print:text-12">
                  
                </div>

            </div>
        </div>
    </div>
    <div class="flex justify-center">
        <button type="button" value="click" onclick="printDiv('print_to')" id="printto"
            class="w-sm bg-primary-500 hover:bg-primary-200 hover:text-primary-500 active:bg-primary-700 max-w-sm rounded-full px-4 py-2 font-semibold tracking-wider text-white active:text-white">
            Print Vehicle Request Form
        </button>
    </div>
    @push('scripts')
        <script>
            function printDiv(divName) {
                var printContents = document.getElementById(divName).innerHTML;
                var originalContents = document.body.innerHTML;

                document.body.innerHTML = printContents;

                window.print();

                document.body.innerHTML = originalContents;

            }
        </script>
    @endpush
</div>
