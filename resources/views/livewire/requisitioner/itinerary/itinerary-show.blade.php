<div>
    @php
        $total_amount = $travel_order->registration_amount;
        foreach ($coverage as $value) {
            $total_amount += $value['total_expenses'];
        }
    @endphp
    <div class="flex-col space-y-5 text-md">
        <div class="px-4 py-5 bg-white border-b rounded-md border-primary-200 sm:px-6 md:rounded-lg">
            <div class="flex-wrap items-center justify-between w-full -mt-4 -ml-4 sm:flex-nowrap">
                <div class="mt-4 ml-4" x-data="{ open: false }">
                    <div class="flex justify-between w-full">
                        <h3 class="flex justify-between w-full text-lg font-medium leading-6 text-primary-700 hover:text-primary-400 hover:cursor-pointer"
                            x-on:click="open= !open">Travel Order Details
                            <svg xmlns="http://www.w3.org/2000/svg" :class="open ? 'rotate-180' : 'rotate-360'"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 5.25l-7.5 7.5-7.5-7.5m15 6l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </h3>
                    </div>
                    <div x-show='open' x-transition:enter='ease-out transition duration-400'
                        x-transition:enter-start='opacity-0 scale-100' x-transition:enter-end='opacity-100 scale-100'
                        x-transition:leave='transition ease-in duration-400'
                        x-transition:leave-start='opacity-100 scale-100' x-transition:leave-end='opacity-0 scale-0'
                        class="origin-top-left">
                        <p class="mt-4 text-primary-500">Tracking Code: {{ $travel_order->tracking_code }}</p>
                        <p class="mt-1 text-primary-500">Travel Order Type: {{ $travel_order->travel_order_type->name }}
                        </p>
                        <p class="mt-1 text-primary-500">Date Range: {{ $travel_order->date_from->format('F d Y') }} to
                            {{ $travel_order->date_to->format('F d Y') }}</p>
                        @if ($travel_order->travel_order_type_id == 1)
                            @if ($travel_order->other_details == '')
                                <p class="mt-1 text-primary-500">Destination:
                                    {{ $travel_order->philippine_city->city_municipality_description }},
                                    {{ $travel_order->philippine_province->province_description }},
                                    {{ $travel_order->philippine_region->region_description }}</p>
                            @else
                                <p class="mt-1 text-primary-500">Destination: {{ $travel_order->other_details }},
                                    {{ $travel_order->philippine_city->city_municipality_description }},
                                    {{ $travel_order->philippine_province->province_description }},
                                    {{ $travel_order->philippine_region->region_description }}</p>
                            @endif
                        @endif
                        <p class="mt-1 text-primary-500">Purpose: {{ $travel_order->purpose }}</p>
                        <p class="mt-1 text-primary-500">Registration Fee: <span
                                class="">{{ number_format($travel_order->registration_amount, 2) > 0 ? number_format($travel_order->registration_amount, 2) : 'N/A' }}</span>
                        </p>
                        <p class="mt-1 text-primary-500">Total Amount: <span
                                class="">{{ number_format($total_amount, 2) }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-4 py-5 bg-white border-b rounded-md border-primary-200 sm:px-6 md:rounded-lg">
            <div class="flex-wrap items-center justify-between w-full -mt-4 -ml-4 sm:flex-nowrap">

                <p class="mt-1 text-primary-500 mb-2">Purpose:
                </p>
                <textarea
                    class="
        form-control
        block
        w-full
        px-3
        py-3
        text-base
        font-normal
        text-gray-700
        bg-white bg-clip-padding
        border border-solid border-gray-300
        rounded
        transition
        ease-in-out
        m-0
        focus:text-gray-700 focus:bg-white focus:outline-none
      "
                    rows="3" placeholder="{{ $travel_order->purpose }}"></textarea>
            </div>
            <div class="flex justify-between w-full">
                <div>
                </div>
                <a href="" id="save"
                    class="max-w-sm px-4 py-2 my-2 mr-4 mt-2 text-sm font-semibold tracking-wider text-white rounded-lg w-sm bg-primary-500 hover:bg-primary-200 hover:text-primary-500 active:bg-primary-700 active:text-white">
                    Save
                </a>
            </div>

        </div>

        <div class="px-4 py-5 bg-white border-b rounded-md border-primary-200 sm:px-6 md:rounded-lg">
            <div class="flex-wrap items-center justify-between w-full -mt-4 -ml-4 sm:flex-nowrap">
                <div class="mt-4 ml-4">
                    <div class="flex justify-between w-full">
                        <h3 class="flex justify-between w-full text-lg font-medium leading-6 text-primary-700 hover:text-primary-400 hover:cursor-pointer"
                            x-on:click="open= !open">Itinerary

                        </h3>
                        <a href="{{ route('requisitioner.itinerary.print', ['itinerary' => $travel_order->itineraries()->firstWhere('user_id', auth()->id())]) }}"
                            id="print"
                            class="max-w-sm px-4 py-2 text-sm font-semibold tracking-wider text-white rounded-lg w-sm bg-primary-500 hover:bg-primary-200 hover:text-primary-500 active:bg-primary-700 active:text-white">
                            Print
                        </a>
                    </div>
                    @foreach ($coverage as $covered)
                        <div class="flex-col w-full m-2 mb-1 ml-0 rounded-md bg-primary-100" x-data="{ open: true }">
                            {{-- header --}}
                            <div class="block w-full p-2 border-b-0 bg-primary-200 rounded-t-md"
                                :class="open ? 'shadow-md shadow-slate-400' : 'rounded-md'">
                                <h3 class="flex justify-between w-full font-bold text-primary-900"
                                    x-on:click="open = !open">
                                    {{ date_format(date_create($covered['date']), 'F d, Y') }}
                                    <svg xmlns="http://www.w3.org/2000/svg" :class="open ? 'rotate-180' : 'rotate-360'"
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                        class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 5.25l-7.5 7.5-7.5-7.5m15 6l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </h3>
                            </div>
                            <div>
                                <div x-show='open' x-transition:enter='ease-out transition duration-400'
                                    x-transition:enter-start='opacity-0 scale-100'
                                    x-transition:enter-end='opacity-100 scale-100'
                                    x-transition:leave='transition ease-in duration-400'
                                    x-transition:leave-start='opacity-100 scale-100'
                                    x-transition:leave-end='opacity-0 scale-0' class="origin-top-left">

                                    <div class="flex-col px-4 pt-2 pb-5">

                                        <p class="mt-4 font-semibold text-primary-500">
                                            <span>Per Diem: </span>
                                            <span class="font-normal">
                                                {{ number_format($covered['per_diem'], 2) }}
                                            </span>
                                        </p>
                                        <p class="mt-1 font-semibold text-primary-500">Services covered by
                                            registration:
                                            <span class="font-normal capitalize">
                                                @if ($covered['breakfast'])
                                                    breakfast,
                                                @endif
                                                @if ($covered['lunch'])
                                                    lunch,
                                                @endif
                                                @if ($covered['dinner'])
                                                    dinner,
                                                @endif
                                                @if ($covered['lodging'])
                                                    lodging
                                                @endif
                                                @if (($covered['breakfast'] || $covered['lodging'] || $covered['dinner'] || $covered['lunch']) == false)
                                                    N/A
                                                @endif
                                            </span>
                                        </p>
                                        <div class="mt-4 divide-y-2 divide-primary-400">
                                            @foreach ($itinerary->itinerary_entries as $entry)
                                                @if ($entry->date->format('Y-m-d') == $covered['date'])
                                                    <div class="flex-col w-full py-2 normal_case">
                                                        <p class="font-semibold text-primary-900">Travelling to: <span
                                                                class="font-normal">{{ $entry->place }}</span></p>
                                                        <p class="font-semibold text-primary-900">Mode of
                                                            transportation: <span
                                                                class="font-normal">{{ $entry->mot->name }}</span>
                                                        </p>
                                                        <p class="font-semibold text-primary-900">Departure time: <span
                                                                class="font-normal">{{ $entry->departure_time->format('g:i a') }}</span>
                                                        </p>
                                                        <p class="font-semibold text-primary-900">Arrival time: <span
                                                                class="font-normal">{{ $entry->arrival_time->format('g:i a') }}</span>
                                                        </p>
                                                        <div
                                                            class="flex justify-between w-full font-semibold text-primary-900">
                                                            Transporation Expenses: <span
                                                                class="font-normal text-right">{{ number_format($entry->transportation_expenses, 2) }}</span>
                                                        </div>
                                                        <div
                                                            class="flex justify-between w-full font-semibold text-primary-900">
                                                            Other Expenses: <span
                                                                class="font-normal text-right">{{ number_format($entry->other_expenses, 2) }}</span>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                        <div
                                            class="flex justify-between w-full mt-2 font-bold border-t-8 border-double text-primary-500 border-primary-900">
                                            Total Expenses: <span
                                                class="text-right">{{ number_format($covered['total_expenses'], 2) }}</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endforeach
                    <div class="flex justify-between w-full px-4 font-bold text-primary-500">
                        Grand Total: <span class="text-right">{{ number_format($total_amount, 2) }}</span></div>
                </div>
            </div>
        </div>
    </div>
</div>
