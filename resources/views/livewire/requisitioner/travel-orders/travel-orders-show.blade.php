<div>

    <div class="col-span-2" id="print_to">
        <div class="flex justify-between w-full p-6 border-b-4 border-black print:flex">
            <div class="flex w-full ml-3 text-left" id="header">
                <div class="inline my-auto"><img class="object-scale-down w-20 h-full" src="{{ asset('images/sksulogo.png') }}" alt="sksu logo">
                </div>
                <div class="my-auto ml-3">
                    <div class="block">
                        <span class="text-sm font-semibold tracking-wide text-left text-black">Republic of the Philippines</span>
                    </div>
                    <div class="block">
                        <span class="text-sm font-semibold tracking-wide text-left uppercase text-primary-600">sultan kudarat state university</span>
                    </div>
                    <div class="block">
                        <span class="text-sm font-semibold tracking-wide text-black">ACCESS, EJC Montilla, 9800 City of Tacurong</span>
                    </div>
                    <div class="block">
                        <span class="text-sm font-semibold tracking-wide text-black">Province of Sultan Kudarat</span>
                    </div>
                </div>
            </div>
            <div class="relative right-0">
                <div class="m-auto">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?data={{ $travel_order->tracking_code }}&amp;size=100x100" title="" alt="" />
                    <span class="font-xs flex justify-center text-[11px]">{{ $travel_order->tracking_code }}</span>
                </div>
            </div>
        </div>

        <div class="w-full">
            <div class="m-6 divide-y divide-black divide-solid print:divide-y-2">
                <div class="flex items-start w-full h-auto p-6 print:block">
                    <div class="items-start block w-full space-y-4 text-left" id="header">

                        <div class="flex">
                            <span class="mx-auto text-5xl font-extrabold tracking-wide text-black uppercase print:text-xl">travel
                                order</span>
                        </div>
                        <div class="block">
                            <span
                                  class="text-sm font-semibold tracking-wide text-left text-black">{{ $travel_order->created_at == '' ? 'Date Not Set' : $travel_order->created_at->format('F d, Y') }}</span>
                        </div>
                        <div class="grid grid-cols-4">
                            <span class="col-span-1 text-sm font-semibold tracking-wide text-black uppercase">Memorandum
                                to:</span>
                            @if ($travel_order->applicants->count() < 10)
                                @foreach ($travel_order->applicants as $applicant)
                                    <h4 class="col-span-3 text-sm tracking-wide text-black uppercase whitespace-nowrap">
                                        {{ $applicant->employee_information->full_name }}</h4>
                                @endforeach
                            @elseif ($travel_order->applicants->count() <= 30)
                                <div class="col-span-3 text-sm font-semibold tracking-wide text-black uppercase">
                                    @if ($travel_order->applicants->count() > 10)
                                        <div class="grid grid-cols-3 grid-rows-10">
                                            @foreach ($travel_order->applicants as $applicant)
                                                <h4 class="whitespace-nowrap">
                                                    {{ $applicant->employee_information->full_name }}
                                                </h4>
                                            @endforeach
                                        </div>
                                    @else
                                        @foreach ($travel_order->applicants as $applicant)
                                            <h4 class="whitespace-nowrap">
                                                {{ $applicant->employee_information->full_name }}</h4>
                                        @endforeach
                                    @endif

                                </div>
                            @else
                                <h4 class="col-span-1 text-sm tracking-wide text-black uppercase whitespace-nowrap">
                                    {{ $applicant->employee_information->full_name }}</h4>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex w-full h-auto px-6 pt-10 print:pt-5" id="contents">
                    <div class="items-start block w-full space-y-4 text-left" id="header">
                        <div class="flex-wrap block -space-y-1">
                            @if ($travel_order->travel_order_type->name == 'Official Time')
                                <span class="font-semibold tracking-wide text-left text-black text-md">
                                    You are scheduled to travel on <strong class="underline">{{ $travel_order->date_from->format('F j') . '-' . $travel_order->date_to->format('j, Y') }}</strong>
                                    to do the following:
                                </span>
                            @else
                                <span class="font-semibold tracking-wide text-left text-black text-md">
                                    You are hereby directed to proceed to
                                    <strong>
                                        @if ($travel_order->other_details != '')
                                            {{ $travel_order->other_details == null ? '' : $travel_order->other_details }},
                                        @endif
                                        {{ $travel_order->philippine_city_id == null ? 'City Not Set' : $travel_order->philippine_city->city_municipality_description }},
                                        {{ $travel_order->philippine_province_id == null ? 'Province Not Set' : $travel_order->philippine_province->province_description }},
                                        {{ $travel_order->philippine_region_id == null ? 'City Not Set' : $travel_order->philippine_region->region_description }}
                                    </strong>
                                    on
                                    <strong class="underline">{{ $travel_order->date_from->format('F j') . '-' . $travel_order->date_to->format('j, Y') }}</strong>
                                    to do the following:
                                </span>
                            @endif

                            <span class="block pl-5 font-semibold tracking-wide text-left text-black whitespace-pre-line text-md">
                                {{ $travel_order->purpose == '' ? 'Purpose not Found' : $travel_order->purpose }}
                            </span>

                            <p class="block pl-5 font-semibold tracking-wide text-left text-black whitespace-pre-line text-md">
                                Your travel is on <span class="underline">{{ $travel_order->travel_order_type->name }}</span>. A report of activities should be made immediately upon termination of
                                this travel order.
                            </p>

                            @foreach ($travel_order->signatories as $signatory)
                                <span class="block pt-16 font-semibold tracking-wide text-center text-black underline text-md">
                                    {{ $signatory->employee_information->full_name }}
                                </span>
                                <span class="block pt-3 font-semibold tracking-wide text-center text-black text-md">
                                    @if ($signatory->employee_information->position?->description == 'University President')
                                        {{ $signatory->employee_information->position?->description }}
                                    @elseif ($signatory->employee_information->position?->description == 'Faculty')
                                        {{ $signatory->employee_information->position?->description }}
                                    @elseif($signatory->employee_information->office == null)
                                        {{ $signatory->employee_information->position?->description }}
                                    @else
                                        {{ $signatory->employee_information->position?->description }}, {{ $signatory->employee_information->office->name }}
                                    @endif
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="flex justify-center">
        <button class="max-w-sm px-4 py-2 font-semibold tracking-wider text-white rounded-full w-sm bg-primary-500 hover:bg-primary-200 hover:text-primary-500 active:bg-primary-700 active:text-white"
                id="printto" type="button" value="click" onclick="printDiv('print_to')">
            Print Travel Order
        </button>
    </div>
    @push('scripts')
        <script>
            function printDiv(divName) {
                var printContents = document.getElementById(divName).innerHTML;
                var originalContents = document.body.innerHTML;
                document.body.innerHTML = printContents;
                window.print();
                window.location.href = "{{ request()->route()->getPrefix() }}/travel-orders";
                document.body.innerHTML = originalContents;
            }
        </script>
    @endpush
</div>
