<div class="">
    <div class="grid grid-cols-1 lg:grid-cols-3">
        <div class="flex-row col-span-1 lg:col-span-2">
            <div
                class="px-4 py-5 bg-white border-b rounded-md border-primary-200 sm:px-6 md:rounded-none md:rounded-tl-lg">
                <div class="flex flex-wrap items-center justify-between -mt-4 -ml-4 sm:flex-nowrap">
                    <div class="w-full mt-4 ml-4">
                        <h3 class="text-lg font-medium leading-6 text-primary-900">Travel Order Details</h3>
                        <p class="mt-4 text-sm text-primary-500">Tracking Code: {{ $travel_order->tracking_code }}</p>
                        <p class="mt-1 text-sm text-primary-500">Travel Order Type:
                            {{ $travel_order->travel_order_type->name }}</p>
                        <p class="mt-1 text-sm text-primary-500">Date Range:
                            {{ $travel_order->date_from->format('F d Y') }} to
                            {{ $travel_order->date_to->format('F d Y') }}</p>
                        @if ($travel_order->travel_order_type_id == 1)
                            @if ($travel_order->other_details == '')
                                <p class="mt-1 text-sm text-primary-500">Destination:
                                    {{ $travel_order->philippine_city->city_municipality_description }},
                                    {{ $travel_order->philippine_province->province_description }},
                                    {{ $travel_order->philippine_region->region_description }}</p>
                            @else
                                <p class="mt-1 text-sm text-primary-500">Destination:
                                    {{ $travel_order->other_details }},
                                    {{ $travel_order->philippine_city->city_municipality_description }},
                                    {{ $travel_order->philippine_province->province_description }},
                                    {{ $travel_order->philippine_region->region_description }}</p>
                            @endif
                        @endif
                        <p class="mt-1 text-sm text-primary-500">Purpose:</p>
                        <p class="mt-1 text-sm whitespace-pre-line text-primary-500">{{ $travel_order->purpose }}</p>

                        @if ($travel_order->signatories()->firstWhere('is_approved', false) == null)
                            @if ($travel_order->itineraries()->firstWhere('user_id', auth()->id()))
                                <a target="_blank"
                                    href="{{ route('requisitioner.itinerary.show', ['itinerary' => $travel_order->itineraries()->firstWhere('user_id', auth()->id())]) }}"
                                    class="flex float-right px-4 py-2 text-sm rounded-full bg-primary-600 text-primary-100 hover:text-primary-100 hover:bg-primary-900 active:ring-primary-700 w-fit active:ring-2 active:ring-offset-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-auto">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="pl-2">
                                        View Itinerary

                                </a>
                            @else
                                <a target="_blank" href="{{ route('requisitioner.itinerary.create') }}"
                                    class="flex float-right px-4 py-2 text-sm rounded-full bg-primary-600 text-primary-100 hover:text-primary-100 hover:bg-primary-900 active:ring-primary-700 w-fit active:ring-2 active:ring-offset-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-auto">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="pl-2">
                                        Create Itinerary
                                    </span>
                                </a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <div
                class="px-4 py-5 mt-5 bg-white border-b rounded-md border-primary-200 sm:px-6 md:rounded-none md:rounded-bl-lg">
                <div class="flex flex-wrap items-center justify-between -mt-4 -ml-4 sm:flex-nowrap">
                    <div class="mt-4 ml-4">
                        <h3 class="text-lg font-medium leading-6 text-primary-900">Status</h3>
                        @foreach ($travel_order->signatories as $signatory)
                            <p class="mt-4 text-sm text-primary-500">Signatory:
                                {{ $signatory->employee_information->full_name }}</p>
                            <p class="mt-1 text-sm text-primary-500">Approval Status:
                                {{ $signatory->pivot->is_approved ? 'Approved' : 'Pending' }}</p>
                            <p class="mt-1 text-sm text-primary-500">Date Approved:
                                {{ $signatory->pivot->is_approved ? $signatory->pivot->updated_at->format('F d, Y') : 'Unavailable' }}
                            </p>
                            <p class="mt-1 text-sm text-primary-500">Time Approved:
                                {{ $signatory->pivot->is_approved ? $signatory->pivot->updated_at->format('h:i:s a') : 'Unavailable' }}
                            </p>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>

        <div
            class="col-span-1 px-4 py-5 ml-4 bg-white border-b rounded-md border-primary-300 md:rounded-l-none md:rounded-r-3xl">
            <div class="flow-root mt-6">
                <ul role="list" class="-my-5 divide-y divide-primary-200">
                    <h3 class="font-semibold text-primary-600 text-md">Notes</h3>
                    @forelse ($travel_order->sidenotes as $sidenote)
                        <li class="py-5">
                            <div class="relative focus-within:ring-2 focus-within:ring-indigo-500">
                                <h3 class="text-sm font-semibold text-primary-800">
                                    <span class="absolute inset-0" aria-hidden="true"></span>
                                    {{ $sidenote->user->employee_information->full_name }}
                                </h3>
                                <p class="mt-1 text-sm text-primary-600 line-clamp-2">{{ $sidenote->user->content }}
                                </p>
                                <p class="mt-1 text-sm text-right text-primary-300">
                                    {{ $sidenote->created_at->format('M d, Y') }}</p>
                            </div>
                        </li>
                    @empty
                        <li class="py-5">
                            <div class="relative focus-within:ring-2 focus-within:ring-indigo-500">
                                <h3 class="text-sm italic font-light text-primary-300">
                                    <span class="absolute inset-0" aria-hidden="true"></span>
                                    Nothing to show
                                </h3>
                            </div>
                        </li>
                    @endforelse
                </ul>
            </div>
            @if ($travel_order->sidenotes->count())
                <div class="mt-6">
                    <a href="#"
                        class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium bg-white border rounded-md shadow-sm text-primary-700 border-primary-300 hover:bg-primary-50">
                        View all</a>
                </div>
            @endif
        </div>

    </div>

</div>
