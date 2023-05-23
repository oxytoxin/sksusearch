<div class="p-16" x-data>
    <div x-ref="travelOrder">
        @php
            $requisitioner = $travel_order->applicants->first();
            $applicants = $travel_order->applicants->where('id', '!=', $requisitioner->id);
        @endphp
        <div class="text-xs">
            <div class="flex">
                <div class="flex flex-1 justify-center items-center gap-16">
                    <img class="h-20" src="{{ asset('images/headerlogo1.png') }}" alt="sksulogo">
                    <div class="text-sm flex flex-col items-center">
                        <p>Republic of the Philippines</p>
                        <p class="text-base text-green-600 font-semibold">SULTAN KUDARAT STATE UNIVERSITY</p>
                        <p>ACCESS, EJC Montilla, 9800 City of Tacurong</p>
                        <p>Province of Sultan Kudarat</p>
                    </div>
                    <img class="h-20" src="{{ asset('images/headerlogo2.png') }}" alt="headerlogo2">
                </div>
                <img class="w-24" src="{{ (new chillerlan\QRCode\QRCode())->render($travel_order->tracking_code) }}" alt="qr" />

            </div>
            <hr class="border my-2 border-black">
            <div class="flex flex-col items-center gap-2">
                <p class="text-3xl font-semibold" style="font-family: 'Script MT Bold';">Office of the President</p>
                <div class="flex flex-col items-center gap-2">
                    <p class="font-semibold text-xl">Travel Order</p>
                    <div class="flex gap-2">
                        <p>No. </p>
                        <p class="min-w-[4rem] text-center border-b border-black">{{ $travel_order->tracking_code }}</p>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex gap-2">
                            <p class="min-w-[4rem] relative text-center border-b border-black">
                                @if ($travel_order->travel_order_type_id == App\Models\TravelOrderType::OFFICIAL_TIME)
                                    <x-ri-check-line class="absolute inset-x-0 mx-auto -bottom-1" />
                                @endif
                            </p>
                            <p>Official Time</p>
                        </div>
                        <div class="flex gap-2">
                            <p class="min-w-[4rem] relative text-center border-b border-black">
                                @if ($travel_order->travel_order_type_id == App\Models\TravelOrderType::OFFICIAL_BUSINESS)
                                    <x-ri-check-line class="absolute inset-x-0 mx-auto -bottom-1" />
                                @endif
                            </p>
                            <p>Official Business</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 mt-8">
                <div>
                    <div class="flex gap-2">
                        <div class="w-36 flex gap-2">
                            <p class="flex-1">Name</p>
                            <p>:</p>
                        </div>
                        <p class="flex-1 border-b border-black">{{ $requisitioner->employee_information->full_name }}</p>
                    </div>
                    <div class="flex gap-2">
                        <div class="w-36 flex gap-2">
                            <p class="flex-1">Designation</p>
                            <p>:</p>
                        </div>
                        <p class="flex-1 border-b border-black">
                            {{ auth()->user()->employee_information->position?->description }}
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <div class="w-36 flex gap-2">
                            <p class="flex-1">Station</p>
                            <p>:</p>
                        </div>
                        <p class="flex-1 border-b border-black">{{ auth()->user()->employee_information->office?->name }}</p>
                    </div>
                </div>
                <div>
                    <div class="flex gap-2">
                        <div class="w-36 flex gap-2">
                            <p class="flex-1">Date</p>
                            <p>:</p>
                        </div>
                        <p class="flex-1 border-b border-black">{{ $travel_order->created_at->format('F d, Y') }}</p>
                    </div>
                    <div class="flex gap-2">
                        <div class="w-36 flex gap-2">
                            <p class="flex-1">Departure Date</p>
                            <p>:</p>
                        </div>
                        <p class="flex-1 border-b border-black">{{ $travel_order->date_from?->format('F d, Y') }}</p>
                    </div>
                    <div class="flex gap-2">
                        <div class="w-36 flex gap-2">
                            <p class="flex-1">Return Date</p>
                            <p>:</p>
                        </div>
                        <p class="flex-1 border-b border-black">{{ $travel_order->date_to?->format('F d, Y') }}</p>
                    </div>
                </div>
            </div>
            <div class="flex gap-2">
                <div class="w-36 flex gap-2">
                    <p class="flex-1">Destination</p>
                    <p>:</p>
                </div>
                <p class="flex-1 border-b border-black">{{ $travel_order->destination }}</p>
            </div>
            <div class="flex gap-2">
                <div class="w-36 flex gap-2">
                    <p class="flex-1">Purpose</p>
                    <p>:</p>
                </div>
                <p class="flex-1 border-b whitespace-pre border-black"> {{ $travel_order->purpose }}</p>
            </div>
            @if (count($applicants))
                <div class="mt-8">
                    <p>Accompanied by:</p>
                    <ul class="grid grid-cols-2 px-4 py-2 gap-2">
                        @foreach ($applicants as $applicant)
                            <li class="border-b text-sm border-black">{{ $loop->iteration }}. {{ $applicant->employee_information->full_name }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="flex gap-4 mt-8">
                <div class="flex gap-4 flex-1">
                    <p>Vehicle shall be provided:</p>
                    <div>
                        <div class="flex gap-2">
                            <p class="min-w-[4rem] relative text-center border-b border-black">
                                @if ($travel_order->needs_vehicle)
                                    <x-ri-check-line class="absolute inset-x-0 mx-auto -bottom-1" />
                                @endif
                            </p>
                            <p>Yes</p>
                        </div>
                        <div class="flex gap-2">
                            <p class="min-w-[4rem] relative text-center border-b border-black">
                                @if (!$travel_order->needs_vehicle)
                                    <x-ri-check-line class="absolute inset-x-0 mx-auto -bottom-1" />
                                @endif
                            </p>
                            <p>Not Necessary</p>
                        </div>
                    </div>
                </div>
                <div class="flex gap-4 flex-1">
                    <p>Remarks:</p>
                    <p class="text-justify flex-1 whitespace-pre underline"></p>
                </div>
            </div>
            <div class="mt-8">
                <p>Noted:</p>
                <div>
                    <div class="grid grid-cols-2 px-8 gap-16">
                        @forelse ($travel_order->immediate_supervisors as $supervisor)
                            <div class="px-8">
                                <p class="min-w-[4rem] text-sm text-center border-b border-black">{{ $supervisor->employee_information->full_name }}</p>
                                <p class="text-center">Immediate Supervisor</p>
                            </div>
                        @empty
                            <div class="px-8">
                                <p class="min-w-[4rem] text-center text-sm border-b border-black">&nbsp;</p>
                                <p class="text-center">Immediate Supervisor</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="mt-8">
                <div class="grid grid-cols-2">
                    <div>
                        <p>Recommending Approval:</p>
                        @forelse ($travel_order->recommending_approval as $approver)
                            <div class="px-16 mt-4">
                                <p class="min-w-[4rem] text-sm text-center border-b border-black">{{ $approver->employee_information->full_name }}</p>
                                <p class="text-center">VPAA / VPRDEX / VPFARG</p>
                            </div>
                        @empty
                            <div class="px-16 mt-4">
                                <p class="min-w-[4rem] text-sm text-center border-b border-black">&nbsp;</p>
                                <p class="text-center">VPAA / VPRDEX / VPFARG</p>
                            </div>
                        @endforelse
                    </div>
                    @if ($travel_order->philippine_region_id != 13)
                        <div>
                            <p>Approved:</p>
                            @forelse ($travel_order->university_president as $president)
                                <div class="px-16 mt-4">
                                    <p class="min-w-[4rem] text-sm text-center border-b border-black">{{ $president->employee_information->full_name }}</p>
                                    <p class="text-center">University President</p>
                                </div>
                            @empty
                                <div class="px-16 mt-4">
                                    <p class="min-w-[4rem] text-sm text-center border-b border-black">&nbsp;</p>
                                    <p class="text-center">University President</p>
                                </div>
                            @endforelse
                        </div>
                    @endif
                </div>
            </div>
            <hr class="my-4 border-2 border-black border-dashed">
            <div>
                <h2 class="text-xl text-center font-semibold">CERTIFICATE OF APPEARANCE</h2>
                <p class="mt-8 font-semibold">TO WHOM IT MAY CONCERN:</p>
                <div class="flex mt-4">
                    <p class="indent-16 text-justify text-sm">This is to certify that the above-mentioned name actually appeared in this office during
                        <span class="underline">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> to
                        <span class="underline">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>.
                        This certification is issued his/her request as evidence.
                    </p>
                </div>
                <div class="grid grid-cols-2 text-sm mt-4">
                    <div class="px-16">
                        <p class="min-w-[4rem] text-center border-b border-black">&nbsp;</p>
                        <p class="text-center">Date</p>
                    </div>
                    <div class="px-16">
                        <p class="min-w-[4rem] text-center border-b border-black">&nbsp;</p>
                        <p class="text-center">Position</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="flex justify-center">
        <button class="max-w-sm px-4 py-2 font-semibold tracking-wider text-white rounded-full w-sm bg-primary-500 hover:bg-primary-200 hover:text-primary-500 active:bg-primary-700 active:text-white" id="printto" type="button" value="click" @click="printOutData($refs.travelOrder.innerHTML, 'Travel Order')">
            Print Travel Order
        </button>
    </div>
</div>
