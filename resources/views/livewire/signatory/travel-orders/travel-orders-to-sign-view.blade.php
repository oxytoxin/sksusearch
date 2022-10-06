<div>
    @php
        $signatories = $travel_order->signatories;
        $actingSignatory = $signatories->firstWhere('pivot.user_id', $from_oic ? $oic_signatory : auth()->id());
        $needs_approval = $signatories
            ->where('pivot.is_approved', false)
            ->where('pivot.id', '<', $actingSignatory->pivot->id)
            ->count();
    @endphp
    <div class="grid grid-cols-1 lg:grid-cols-3">
        <div class="flex-row col-span-1 lg:col-span-2">
            <div class="px-4 py-5 bg-white border-b rounded-md border-primary-200 sm:px-6 md:rounded-none md:rounded-t-lg">
                <div class="flex-wrap items-center justify-between block -mt-4 -ml-4 sm:flex-nowrap">
                    <div class="mt-4 ml-4">
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
                        <p class="mt-1 text-sm text-primary-500">Purpose: {{ $travel_order->purpose }}</p>
                        <p class="mt-1 text-primary-500">Registration Fee: <span class="">{{ $travel_order->registration_amount > 0 ? $travel_order->registration_amount : 'N/A' }}</span>
                        </p>
                        @if ($needs_approval)
                            <p class="mt-4 text-amber-700">Travel Order needs approval on preliminary signatories.</p>
                        @elseif(!$actingSignatory->pivot->is_approved)
                            <div class="flex justify-between w-full">
                                <span>&nbsp</span>
                                <div class="flex space-x-3">
                                    <button class="flex text-sm text-primary-600 hover:text-primary-400" wire:click.prevent="approve">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="">Approve Travel Order</span>
                                    </button>
                                    <button class="flex text-sm text-red-500 hover:text-red-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                            <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                                        </svg>
                                        <span class="">Reject Travel Order</span>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="px-4 py-5 mt-2 bg-white border-b rounded-md border-primary-200 sm:px-6 md:rounded-none md:rounded-b-lg">
                <div class="flex flex-wrap items-center justify-between -mt-4 -ml-4 sm:flex-nowrap">
                    <div class="mt-4 ml-4">
                        <h3 class="text-lg font-medium leading-6 text-primary-900"></h3>
                        @foreach ($signatories as $signatory)
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

        <div class="col-span-1 px-4 py-5 ml-4 overflow-y-auto bg-white border-b rounded-md border-primary-300 max-h-screen-70 soft-scrollbar">
            <div class="flow-root mt-6">
                <ul role="list" class="-my-5 divide-y divide-primary-200">
                    <div class="flex justify-between w-full">
                        <h3 class="text-lg font-semibold text-primary-600">Notes</h3>
                        <button type="button" wire:click="$set('modal',true)" class="flex px-4 my-auto text-center">
                            <span class="my-auto text-sm"> Add note</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-auto h-3 my-auto">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                        </button>

                    </div>

                    @forelse ($travel_order->sidenotes as $sidenote)
                        @if (!($loop->index + 1 > $limit))
                            <li class="py-5">
                                <div class="relative focus-within:ring-2 focus-within:ring-indigo-500">
                                    <h3 class="flex justify-between text-sm font-semibold text-primary-800">
                                        <span class="absolute inset-0" aria-hidden="true"></span>
                                        <span class="flex">{{ $sidenote->user->employee_information->full_name }}</span>
                                        <span class="flex">{{ $sidenote->created_at->format('M d, Y') }}</span>
                                    </h3>
                                    <p class="mt-1 text-sm text-primary-600 line-clamp-2">
                                        {{ $sidenote->content }}</p>
                                </div>
                            </li>
                        @endif
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
            @if ($travel_order->sidenotes->count() > $limit)
                <div class="mt-6">
                    <button type="button" wire:click="showMore()" class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium bg-white border rounded-md shadow-sm text-primary-700 border-primary-300 hover:bg-primary-50">View
                        more</button>
                </div>
            @endif
        </div>

    </div>

    <x-modal.card title="Add Note" blur wire:model.defer="modal" class="text-primary-600">
        <form wire:submit.prevent='addNote' class="space=y=2 flex-col">
            <x-textarea class="text-primary-800 placeholder:text-primary-200" label="Your notes" placeholder="Write your notes" wire:model.defer="note" />
            <x-button type="submit" class="mt-2 text-primary-900 border-primary-800">
                <span class="text-primary-900">
                    Save Note
                </span>
            </x-button>
        </form>

    </x-modal.card>

</div>
