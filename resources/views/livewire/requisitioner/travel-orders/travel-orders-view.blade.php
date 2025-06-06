<div class="">
    <div class="grid grid-cols-1 lg:grid-cols-3">
        <div class="col-span-1 flex-row lg:col-span-2">
            <div class="rounded-md border-b border-primary-200 bg-white px-4 py-5 sm:px-6 lg:rounded-none lg:rounded-tl-lg">
                <div class="-ml-4 -mt-4 flex flex-wrap items-center justify-between sm:flex-nowrap">
                    <div class="ml-4 mt-4 w-full">
                        <h3 class="text-lg font-medium leading-6 text-primary-900">Travel Order Details</h3>
                        <p class="mt-4 text-sm text-primary-500">Tracking Code: {{ $travel_order->tracking_code }}</p>
                        <p class="mt-1 text-sm text-primary-500">Travel Order Type:
                            {{ $travel_order->travel_order_type->name }}</p>
                        <p class="mt-1 text-sm text-primary-500">Date Range:
                            {{ $travel_order->date_from?->format('F d Y') }} to
                            {{ $travel_order->date_to?->format('F d Y') }}</p>
                        @if ($travel_order->travel_order_type_id == 1)
                            <p class="mt-1 text-sm text-primary-500">Destination: {{ $travel_order->destination }}</p>
                        @endif
                        <p class="mt-1 text-sm text-primary-500">
                            Needs Vehicle: {{ $travel_order->needs_vehicle ? 'Yes' : 'Not Necessary' }},
                            @if ($travel_order->needs_vehicle)
                                @if ($travel_order->request_schedule)
                                    <a class="font-semibold underline" href="{{ route('requisitioner.motorpool.show-request-form', ['request' => $travel_order->request_schedule]) }}" target="_blank">
                                        View Vehicle Request Form
                                    </a>
                                @else
                                    <a class="font-semibold underline" href="{{ route('requisitioner.motorpool.create', ['travel_order' => $travel_order]) }}" target="_blank">
                                        Create Vehicle Request
                                    </a>
                                @endif
                            @endif
                        </p>
                        <p class="mt-1 text-sm text-primary-500">Purpose:</p>
                        <p class="mt-1 whitespace-pre-line text-sm text-primary-500">{{ $travel_order->purpose }}</p>

                        {{-- <div>
                            <p class="mt-1 text-sm text-primary-500">Attachments:</p>
                            @forelse ($travel_order->attachments as $attachment)
                                <div class="flex items-start gap-4">
                                    <a class="mt-1 block text-sm text-primary-500 underline" href="{{ route('attachments.download', ['attachment' => $attachment]) }}" target="_blank">
                                        {{ $attachment->file_name }}
                                    </a>
                                    <p class="text-sm text-primary-500">{{ $attachment->description }}</p>
                                </div>
                            @empty
                                <p class="text-sm text-primary-500">No files attached.</p>
                            @endforelse
                        </div> --}}
                        @php
                            $proposed_itinerary = $travel_order
                                ->itineraries()
                                ->whereIsActual(false)
                                ->where('user_id', auth()->id())
                                ->first();
                            $actual_itinerary = $travel_order
                                ->itineraries()
                                ->whereIsActual(true)
                                ->where('user_id', auth()->id())
                                ->first();
                        @endphp

                        @if ($travel_order->travel_order_type_id == App\Models\TravelOrderType::OFFICIAL_BUSINESS)
                            <div class="flex justify-end gap-2">
                                @if ($proposed_itinerary)
                                    <a class="flex w-fit rounded-full bg-primary-600 px-4 py-2 text-sm text-primary-100 hover:bg-primary-900 hover:text-primary-100 active:ring-2 active:ring-primary-700 active:ring-offset-2" href="{{ route('requisitioner.itinerary.show', ['itinerary' => $proposed_itinerary]) }}" target="_blank">
                                        <svg class="h-auto w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span class="pl-2">
                                            View Proposed Itinerary
                                        </span>
                                    </a>
                                @elseif($travel_order->travel_order_type_id == App\Models\TravelOrderType::OFFICIAL_BUSINESS)
                                    <a class="flex w-fit rounded-full bg-primary-600 px-4 py-2 text-sm text-primary-100 hover:bg-primary-900 hover:text-primary-100 active:ring-2 active:ring-primary-700 active:ring-offset-2" href="{{ route('requisitioner.itinerary.create', ['travel_order' => $travel_order]) }}" target="_blank">
                                        <svg class="h-auto w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="pl-2">
                                            Create Itinerary
                                        </span>
                                    </a>
                                @endif
                                @if ($actual_itinerary)
                                    <a class="flex w-fit rounded-full bg-primary-600 px-4 py-2 text-sm text-primary-100 hover:bg-primary-900 hover:text-primary-100 active:ring-2 active:ring-primary-700 active:ring-offset-2" href="{{ route('requisitioner.itinerary.show', ['itinerary' => $actual_itinerary]) }}" target="_blank">
                                        <svg class="h-auto w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span class="pl-2">
                                            View Actual Itinerary
                                        </span>
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-5 rounded-md border-b border-primary-200 bg-white px-4 py-5 sm:px-6 lg:rounded-none lg:rounded-bl-lg">
                <div class="-ml-4 -mt-4 flex flex-wrap items-center justify-between sm:flex-nowrap">
                    <div class="ml-4 mt-4">
                        <h3 class="text-lg font-medium leading-6 text-primary-900">Status</h3>
                        @foreach ($travel_order->signatories as $signatory)
                            <p class="mt-4 text-sm text-primary-500">Signatory:
                                {{ $signatory->employee_information->full_name }}</p>
                            <p class="mt-1 text-sm text-primary-500">Approval Status:
                                {{ match ($signatory->pivot->is_approved) {
                                    0 => 'Pending',
                                    1 => 'Approved',
                                    2 => 'Rejected',
                                    default => '',
                                } }}
                            </p>
                            <p class="mt-1 text-sm text-primary-500">Date {{ !$signatory->pivot->is_approved ? 'Rejected' : 'Approved' }}:
                                {{ match ($signatory->pivot->is_approved) {
                                    0 => 'Unavailable',
                                    1 => $signatory->pivot->updated_at->format('F d, Y'),
                                    2 => $signatory->pivot->updated_at->format('F d, Y'),
                                    default => '',
                                } }}
                            </p>
                            <p class="mt-1 text-sm text-primary-500">Time {{ !$signatory->pivot->is_approved ? 'Rejected' : 'Approved' }}:
                                {{ match ($signatory->pivot->is_approved) {
                                    0 => 'Unavailable',
                                    1 => $signatory->pivot->updated_at->format('h:i:s a'),
                                    2 => $signatory->pivot->updated_at->format('h:i:s a'),
                                    default => '',
                                } }}
                            </p>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-span-1 mt-4 max-h-screen-70 overflow-y-auto rounded-md border-b border-primary-300 bg-white px-4 py-5 soft-scrollbar lg:ml-4 lg:mt-0">
            <div class="mt-6 flow-root">
                <ul class="-my-5 divide-y divide-primary-200" role="list">
                    <div class="flex w-full justify-between">
                        <h3 class="text-lg font-semibold text-primary-600">Notes</h3>
                        <button class="my-auto flex px-4 text-center" type="button" wire:click="$set('modal',true)">
                            <span class="my-auto text-sm"> Add note</span>
                            <svg class="my-auto h-3 w-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                        </button>

                    </div>

                    @forelse ($sidenotes as $sidenote)
                        <li class="py-5">
                            <div class="relative focus-within:ring-2 focus-within:ring-indigo-500">
                                <h3 class="flex justify-between text-sm font-semibold text-primary-800">
                                    <span class="absolute inset-0" aria-hidden="true"></span>
                                    <span class="flex">{{ $sidenote->user->employee_information->full_name }}</span>
                                    <span class="flex">{{ $sidenote->created_at->format('M d, Y') }}</span>
                                </h3>
                                <p class="mt-1 line-clamp-2 text-sm text-primary-600">
                                    {{ $sidenote->content }}</p>
                            </div>
                        </li>
                    @empty
                        <li class="py-5">
                            <div class="relative focus-within:ring-2 focus-within:ring-indigo-500">
                                <h3 class="text-sm font-light italic text-primary-300">
                                    <span class="absolute inset-0" aria-hidden="true"></span>
                                    Nothing to show
                                </h3>
                            </div>
                        </li>
                    @endforelse

                </ul>
            </div>
            @if ($travel_order->sidenotes()->count() > $limit)
                <div class="mt-6">
                    <button class="flex w-full items-center justify-center rounded-md border border-primary-300 bg-white px-4 py-2 text-sm font-medium text-primary-700 shadow-sm hover:bg-primary-50" type="button" wire:click="showMore()">View
                        more</button>
                </div>
            @endif
        </div>

    </div>

</div>
