<div>
    <div class="col-span-2">
        <div class="flex justify-between w-full p-6 border-b-4 border-black print:flex">
            <div id="header" class="flex w-full ml-3 text-left">
                <div class="inline my-auto"><img src="http://sksu.edu.ph/wp-content/uploads/2020/09/512x512-1.png" alt="sksu logo" class="object-scale-down w-20 h-full">
                </div>
                <div class="my-auto ml-3">
                    <div class="block">
                        <span class="text-sm font-semibold tracking-wide text-left text-black">Republic of the
                            Philippines</span>
                    </div>
                    <div class="block">
                        <span class="text-sm font-semibold tracking-wide text-left uppercase text-primary-600">sultan
                            kudarat
                            state university</span>
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
                    <img src="https://api.qrserver.com/v1/create-qr-code/?data={{ $travel_order->tracking_code }}&amp;size=100x100" alt="" title="" />
                    {{-- <span class="text-xs text-grey-100">{{$travel_order->tracking_code}}</span> --}}
                </div>
            </div>
        </div>

        @if (isset($travel_order))
            @if ($travel_order->travel_order_type->name == 'Official Time')
                <div class="w-full">
                    <div class="m-6 divide-y divide-black divide-solid print:divide-y-2">
                        <div class="flex items-start w-full h-auto p-6 print:block ">
                            <div id="header" class="items-start block w-full space-y-4 text-left">
                                <div class="block">
                                    <span class="text-sm font-semibold tracking-wide text-left text-black">{{ $travel_order->created_at == '' ? 'Date Not Set' : $travel_order->created_at->format('F d, Y') }}</span>
                                </div>
                                <div class="flex">
                                    <span class="mx-auto text-5xl font-extrabold tracking-wide text-black uppercase print:text-xl">travel
                                        order</span>
                                </div>
                                <div class="grid grid-cols-4 ">
                                    <span class="col-span-1 text-sm font-semibold tracking-wide text-black uppercase">Memorandum
                                        to:</span>
                                    <div class="col-span-1 text-sm font-semibold tracking-wide text-black uppercase">
                                        @if (isset($applicants))
                                            @foreach ($applicants as $applicant)
                                                <span class="block">{{ $applicant->full_name }}</span>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="contents" class="flex w-full h-auto px-6 pt-10 print:pt-5">
                            <div id="header" class="items-start block w-full space-y-4 text-left">
                                <div class="flex-wrap block -space-y-1">
                                    <span class="font-semibold tracking-wide text-left text-black text-md">
                                        You are scheduled to travel from <strong class="underline">{{ $travel_order->date_from->format('jS') . ' of ' . $travel_order->date_from->format('F Y') }}</strong>
                                        to
                                        <strong class="underline">{{ $travel_order->date_to->format('jS') . ' of ' . $travel_order->date_to->format('F Y') }}</strong>
                                        to do the following:
                                    </span>
                                    <span class="block pl-5 font-semibold tracking-wide text-left text-black whitespace-pre-line text-md">
                                        {{ $travel_order->purpose == '' ? 'Purpose not Found' : $travel_order->purpose }}
                                    </span>
                                    @if (isset($signatories))
                                        @foreach ($signatories as $signatory)
                                            <span class="block pt-16 font-semibold tracking-wide text-center text-black underline text-md">
                                                {{ $signatory->full_name }}
                                            </span>

                                            @php
                                                $sigpositions = App\Models\Office::orWhere('admin_user_id', '=', $signatory->id)
                                                    ->orWhere('head_id', '=', $signatory->id)
                                                    ->get();
                                                $campuses = App\Models\Campus::orWhere('admin_user_id', '=', $signatory->id)->get();
                                                $campusCount = count($campuses);
                                                $posCount = count($sigpositions);
                                            @endphp
                                            <span class="block pt-3 font-semibold tracking-wide text-center text-black text-md">
                                                @if ($campusCount >= 1)
                                                    @foreach ($campuses as $campus)
                                                        @if (strtoupper($campus->name) == "PRESIDENT'S OFFICE")
                                                            @if ($campusCount == $loop->index + 1)
                                                                {{ $signatory->position->description }}
                                                            @else
                                                                {{ $signatory->position->description }} /
                                                            @endif
                                                        @else
                                                            @if ($campusCount == $loop->index + 1)
                                                                {{ $signatory->position->description }} of {{ $campus->name }} Campus
                                                            @else
                                                                {{ $signatory->position->description }} of {{ $campus->name }} /
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @elseif ($campusCount == 0 && $posCount >= 1)
                                                    @foreach ($sigpositions as $sigpos)
                                                        @if (strtoupper($sigpos->name) == "PRESIDENT'S OFFICE")
                                                            @if ($posCount == $loop->index + 1)
                                                                {{ $signatory->position->description }}
                                                            @else
                                                                {{ $signatory->position->description }} /
                                                            @endif
                                                        @else
                                                            @if ($posCount == $loop->index + 1)
                                                                {{ $signatory->position->description }} of {{ $signatory->office->name }}
                                                            @else
                                                                {{ $signatory->position->description }} of {{ $signatory->office->name }} /
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif

                                            </span>
                                        @endforeach

                                    @endif

                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            @else
                {{-- Official Business  --}}
                <div class="w-full">
                    <div class="m-6 divide-y divide-black divide-solid print:divide-y-2">
                        <div class="flex items-start w-full h-auto p-6 print:block ">
                            <div id="header" class="items-start block w-full space-y-4 text-left">
                                <div class="block">
                                    <span class="text-sm font-semibold tracking-wide text-left text-black">{{ $travel_order->created_at == '' ? 'Date Not Set' : $travel_order->created_at->format('F d, Y') }}</span>
                                </div>
                                <div class="flex">
                                    <span class="mx-auto text-5xl font-extrabold tracking-wide text-black uppercase print:text-xl">travel
                                        order</span>
                                </div>
                                <div class="grid grid-cols-4 ">
                                    <span class="col-span-1 text-sm font-semibold tracking-wide text-black uppercase">Memorandum
                                        to:</span>
                                    <div class="col-span-1 text-sm font-semibold tracking-wide text-black uppercase">
                                        @if (isset($applicants))
                                            @foreach ($applicants as $applicant)
                                                <span class="block">{{ $applicant->full_name }}</span>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="contents" class="flex w-full h-auto px-6 pt-10 print:pt-5">
                            <div id="header" class="items-start block w-full space-y-4 text-left">
                                <div class="flex-wrap block -space-y-1">
                                    <span class="font-semibold tracking-wide text-left text-black text-md">
                                        You are hereby
                                        directed
                                        to proceed to <strong>
                                            @if ($travel_order->other_details != '')
                                                {{ $travel_order->other_details == null ? '' : $travel_order->other_details }},
                                                {{ $travel_order->philippine_city_id == null ? 'City Not Set' : $travel_order->city->city_municipality_description }},
                                                {{ $travel_order->philippine_province_id == null ? 'Province Not Set' : $travel_order->province->province_description }},
                                                {{ $travel_order->philippine_region_id == null ? 'City Not Set' : $travel_order->region->region_description }}
                                            @else
                                                {{ $travel_order->philippine_city_id == null ? 'City Not Set' : $travel_order->city->city_municipality_description }},
                                                {{ $travel_order->philippine_province_id == null ? 'Province Not Set' : $travel_order->province->province_description }},
                                                {{ $travel_order->philippine_region_id == null ? 'Region Not Set' : $travel_order->region->region_description }}
                                            @endif
                                        </strong>
                                        on the
                                        <strong class="underline">{{ $travel_order->date_from->format('jS') . ' of ' . $travel_order->date_from->format('F Y') }}</strong>
                                        to
                                        <strong class="underline">{{ $travel_order->date_to->format('jS') . ' of ' . $travel_order->date_to->format('F Y') }}</strong>
                                        to do the following:
                                    </span>
                                    <span class="block pl-5 font-semibold tracking-wide text-left text-black whitespace-pre-line text-md">
                                        {{ $travel_order->purpose == '' ? 'Purpose not Found' : $travel_order->purpose }}
                                    </span>
                                    @if (isset($signatories))
                                        @foreach ($signatories as $signatory)
                                            <span class="block pt-16 font-semibold tracking-wide text-center text-black underline text-md">
                                                {{ $signatory->full_name }}
                                            </span>

                                            @php
                                                $sigpositions = App\Models\Office::orWhere('admin_user_id', '=', $signatory->id)
                                                    ->orWhere('head_id', '=', $signatory->id)
                                                    ->get();
                                                $campuses = App\Models\Campus::orWhere('admin_user_id', '=', $signatory->id)->get();
                                                $campusCount = count($campuses);
                                                $posCount = count($sigpositions);
                                            @endphp
                                            <span class="block pt-3 font-semibold tracking-wide text-center text-black text-md">
                                                @if ($campusCount >= 1)
                                                    @foreach ($campuses as $campus)
                                                        @if (strtoupper($campus->name) == "PRESIDENT'S OFFICE")
                                                            @if ($campusCount == $loop->index + 1)
                                                                {{ $signatory->position->description }}
                                                            @else
                                                                {{ $signatory->position->description }} /
                                                            @endif
                                                        @else
                                                            @if ($campusCount == $loop->index + 1)
                                                                {{ $signatory->position->description }} of {{ $campus->name }} Campus
                                                            @else
                                                                {{ $signatory->position->description }} of {{ $campus->name }} /
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @elseif ($campusCount == 0 && $posCount >= 1)
                                                    @foreach ($sigpositions as $sigpos)
                                                        @if (strtoupper($sigpos->name) == "PRESIDENT'S OFFICE")
                                                            @if ($posCount == $loop->index + 1)
                                                                {{ $signatory->position->description }}
                                                            @else
                                                                {{ $signatory->position->description }} /
                                                            @endif
                                                        @else
                                                            @if ($posCount == $loop->index + 1)
                                                                {{ $signatory->position->description }} of {{ $signatory->office->name }}
                                                            @else
                                                                {{ $signatory->position->description }} of {{ $signatory->office->name }} /
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif

                                            </span>
                                        @endforeach

                                    @endif

                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            @endif

        @endif
        <a href="#" target="_blank" id="printto" class="max-w-sm px-4 py-2 font-semibold tracking-wider text-white rounded-full w-sm bg-primary-500 hover:bg-primary-200 hover:text-primary-500 active:bg-primary-700 active:text-white">Print
            Travel Order</a>
        {{-- @if ($travel_order->isDraft == true)
        <button wire:click="deleteTO('{{ $travel_order->to_type }}')" id="printto"
            class="max-w-sm px-4 py-2 font-semibold tracking-wider text-white bg-red-500 rounded-full w-sm hover:bg-red-200 hover:text-primary-500 active:bg-primary-500 active:text-white">Delete
            Travel Order</button>
        <a href="{{route('travel-order', ['id'=>3,'isEdit'=>1,'travelOrderID'=>$travelorderID])}}"
            id="printto"
            class="max-w-sm px-4 py-2 font-semibold tracking-wider text-white rounded-full w-sm bg-primary-500 hover:bg-primary-200 hover:text-primary-500 active:bg-primary-700 active:text-white">Edit
            Travel Order</a>
        @else
        @if ($isSignatory == 1)
        <button wire:click="declineTO('{{ $travel_order->to_type }}')" id="decline"
            class="max-w-sm px-4 py-2 font-semibold tracking-wider text-white bg-red-500 rounded-full w-sm hover:bg-red-200 hover:text-primary-500 active:bg-primary-500 active:text-white">Decline
            Travel Order</button>
        <button wire:click="approveTO('{{ $travel_order->to_type }}')" id="approve"
            class="max-w-sm px-4 py-2 font-semibold tracking-wider text-white rounded-full bg-primary-500 w-sm hover:bg-indigo-200 hover:text-primary-500 active:bg-primary-500 active:text-white">Approve
            Travel Order</button>
        <button x-on:click="showModal = true" id="side_note_show"
            class="max-w-sm px-4 py-2 font-semibold tracking-wider text-white bg-indigo-800 rounded-full w-sm hover:bg-indigo-200 hover:text-primary-500 active:bg-primary-500 active:text-white">Add
            Side Note</button>
        @else
         <a href="{{route('print-to', [$travelorderID])}}" target="_blank" id="printto"
            class="max-w-sm px-4 py-2 font-semibold tracking-wider text-white rounded-full w-sm bg-primary-500 hover:bg-primary-200 hover:text-primary-500 active:bg-primary-700 active:text-white">Print
            Travel Order</a>
        @endif
        @endif
        @else --}}
        {{-- <div class="w-full">
            <div class="m-6 divide-y divide-black divide-solid print:divide-y-2">
                <div class="flex py-10 my-auto">
                    <span
                        class="mx-auto text-5xl font-extrabold tracking-wide text-black uppercase print:text-xl">travel
                        order not found</span>
                </div>
            </div>

        </div>
        <a href="#" id="printto"
            class="max-w-sm px-4 py-2 font-semibold tracking-wider rounded-full w-sm bg-primary-500 text-primary-200 hover:bg-primary-200 hover:text-primary-500 active:bg-primary-700 active:text-white">Go
            to dashboard</a> --}}
        {{-- @endif --}}

    </div>
</div>
