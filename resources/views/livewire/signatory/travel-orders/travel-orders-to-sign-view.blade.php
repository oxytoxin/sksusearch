<div>
    @php
        $signatories = $travel_order->signatories;
        $actingSignatory = $signatories->firstWhere('pivot.user_id', $from_oic ? $oic_signatory : auth()->id());
        $needs_approval = $signatories->where('pivot.is_approved', 0)->where('pivot.id', '<', $actingSignatory->pivot->id)->count() > 0;
        $rejectedAlready = $signatories->where('pivot.is_approved', 2)->where('pivot.id', '<', $actingSignatory->pivot->id)->count() > 0;
    @endphp
    <div class="grid grid-cols-1 lg:grid-cols-3">
        <div class="col-span-1 flex-row lg:col-span-2">
            <div class="rounded-md border-b border-primary-200 bg-white px-4 py-5 sm:px-6 md:rounded-none md:rounded-t-lg">
                <div class="-ml-4 -mt-4 block flex-wrap items-center justify-between sm:flex-nowrap">
                    <div class="ml-4 mt-4">
                        <h3 class="text-lg font-medium leading-6 text-primary-900">Travel Order Details</h3>
                        <div class="mt-4 space-y-1 text-sm text-primary-500">
                            <p>Tracking Code: {{ $travel_order->tracking_code }}</p>
                            <p>Travel Order Type:
                                {{ $travel_order->travel_order_type->name }}</p>
                            <p>Date:
                                {{ $travel_order->date_from->format('F d Y') }} to
                                {{ $travel_order->date_to->format('F d Y') }}</p>
                            @if ($travel_order->travel_order_type_id == 1)
                                <p>Destination: {{ $travel_order->destination }}</p>
                            @endif
                            <p>
                                Needs Vehicle: {{ $travel_order->needs_vehicle ? 'Yes' : 'Not Necessary' }},
                                @if ($travel_order->request_schedule)
                                    <a class="font-semibold underline" href="{{ route('signatory.motorpool.show-request-form', ['request' => $travel_order->request_schedule]) }}" target="_blank">View
                                        Vehicle Request Form</a>
                                @endif
                            </p>

                            <p>Purpose:</p>
                            <p class="whitespace-pre px-8 py-4">{{ $travel_order->purpose }}</p>
                            <p>Registration Fee: <span>{{ $travel_order->registration_amount > 0 ? $travel_order->registration_amount : 'N/A' }}</span></p>
                            <hr>
                            <div>
                                <h5>Applicants:</h5>
                                <ul class="space-y-2 p-4">
                                    @foreach ($travel_order->applicants as $applicant)
                                        <li class="flex items-center justify-between">
                                            <div class="flex items-center gap-4">
                                                @if ($actingSignatory->pivot->is_approved == 0 && !$needs_approval)
                                                    <button class="flex items-center">
                                                        <x-ri-delete-bin-line class="h-5 w-5 text-red-600" onclick="confirm('Are you sure you want to remove this applicant?') || event.stopImmediatePropagation()" wire:click="removeApplicant({{ $applicant->id }})" />
                                                    </button>
                                                @endif
                                                <p>{{ $applicant->employee_information->full_name }}</p>
                                            </div>
                                            @php
                                                $proposed_itinerary = $itineraries->where('user_id', $applicant->id)->where('is_actual', false)->first();
                                                $actual_itinerary = $itineraries->where('user_id', $applicant->id)->where('is_actual', true)->first();
                                            @endphp
                                            @if ($proposed_itinerary)
                                                <div class="flex items-end space-x-2">
                                                    <a class="font-semibold underline" href="{{ route('signatory.itinerary.show', ['itinerary' => $proposed_itinerary]) }}" target="_blank">View
                                                        Proposed Itinerary</a>
                                                    @if ($proposed_itinerary->approved_at)
                                                        <span class="text-primary-600">Approved</span>
                                                    @else
                                                        <x-filament-support::button size="sm" wire:target="approveItinerary({{ $proposed_itinerary->id }})" wire:click="approveItinerary({{ $proposed_itinerary->id }})">Approve</x-filament-support::button>
                                                    @endif
                                                </div>
                                            @elseif($travel_order->travel_order_type_id == App\Models\TravelOrderType::OFFICIAL_BUSINESS)
                                                <span class="font-semibold text-amber-600 underline">No Itinerary Created</span>
                                            @endif
                                            @if ($actual_itinerary)
                                                <div class="flex items-end space-x-2">
                                                    <a class="font-semibold underline" href="{{ route('signatory.itinerary.show', ['itinerary' => $actual_itinerary]) }}" target="_blank">
                                                        View Actual Itinerary</a>
                                                    @if ($actual_itinerary->approved_at)
                                                        <span class="text-primary-600">Approved</span>
                                                    @else
                                                        <span class="text-amber-600">Needs Approval</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            @if (count($travel_order->removed_applicants))
                                <div>
                                    <h5>Removed Applicants:</h5>
                                    <ul class="gap-2 p-4">
                                        @foreach ($travel_order->removed_applicants as $removed_applicant)
                                            <div class="flex items-center gap-4">
                                                @if ($actingSignatory->pivot->is_approved == 0 && !$needs_approval)
                                                    <button class="flex items-center">
                                                        <x-ri-arrow-go-back-line class="h-5 w-5 text-green-600" onclick="confirm('Are you sure you want to restore this applicant?') || event.stopImmediatePropagation()" wire:click="restoreApplicant({{ $removed_applicant->id }})" />
                                                    </button>
                                                @endif
                                                <p>{{ $removed_applicant->employee_information->full_name }}</p>
                                            </div>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div>
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
                            </div>
                        </div>
                        @if ($needs_approval)
                            <p class="mt-4 text-amber-700">Travel Order needs approval on preliminary signatories.</p>
                        @elseif($rejectedAlready)
                            <p class="mt-4 text-amber-700">Travel Order already rejected by preliminary signatories.</p>
                        @elseif(!$actingSignatory->pivot->is_approved)
                            @if ($travel_order->needs_vehicle && !$travel_order->request_schedule)
                                <p class="mt-4 text-amber-700">Travel Order requires a vehicle but no vehicle request was created by applicants.</p>
                            @elseif ($travel_order->travel_order_type_id == App\Models\TravelOrderType::OFFICIAL_BUSINESS && $itineraries->where('approved_at', '!=', null)->count() != $travel_order->applicants()->count())
                                <p class="mt-4 text-amber-700">Incomplete approved itinerary entries from travel order's applicants.</p>
                            @else
                                <div class="flex flex-col rounded border border-primary-400 p-4">
                                    <div class="flex w-full justify-evenly gap-4">
                                        <button class="flex flex-1 justify-center rounded border-2 border-red-600 bg-red-600 p-2 text-sm text-white hover:bg-red-500" onclick="confirm('Are you sure you want to reject this travel order?') || event.stopImmediatePropagation()" wire:click="$set('modalRejection',true)">
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                                            </svg>
                                            <span class="">Reject Travel Order</span>
                                        </button>
                                        <button class="flex flex-1 justify-center rounded border-2 border-primary-600 bg-primary-600 p-2 text-sm text-white hover:bg-primary-400" onclick="confirm('Are you sure you want to approve this travel order?') || event.stopImmediatePropagation()" wire:click.prevent="approve">
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                            </svg>
                                            <span class="font-bold">Approve Travel Order</span>
                                        </button>
                                    </div>
                                    <div class="mt-4 self-end">
                                        @if ($travel_order->travel_order_type_id == App\Models\TravelOrderType::OFFICIAL_BUSINESS)
                                            <x-filament::button class="!p-1 !text-xs" outlined onclick="confirm('Are you sure you want to convert this travel order?') || event.stopImmediatePropagation()" wire:click="toggleTravelOrderType" size="sm" wire:target="toggleTravelOrderType">Convert Travel Order Type to Official Time</x-filament::button>
                                        @else
                                            <x-filament::button onclick="confirm('Are you sure you want to convert this travel order?') || event.stopImmediatePropagation()" wire:click="toggleTravelOrderType" outlined size="sm" wire:target="toggleTravelOrderType">Convert Travel Order Type to Official Business</x-filament::button>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            <div class="mt-2 rounded-md border-b border-primary-200 bg-white px-4 py-5 sm:px-6 md:rounded-none md:rounded-b-lg">
                <div class="-ml-4 -mt-4 flex flex-wrap items-center justify-between sm:flex-nowrap">
                    <div class="ml-4 mt-4">
                        <h3 class="text-lg font-medium leading-6 text-primary-900"></h3>
                        @foreach ($signatories as $signatory)
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
        <div class="col-span-1 ml-4 max-h-screen-70 overflow-y-auto rounded-md border-b border-primary-300 bg-white px-4 py-5 soft-scrollbar">
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
                    <button class="flex w-full items-center justify-center rounded-md border border-primary-300 bg-white px-4 py-2 text-sm font-medium text-primary-700 shadow-sm hover:bg-primary-50" type="button" wire:click="showMore">View more</button>
                </div>
            @endif
        </div>
    </div>
    <x-modal.card class="text-primary-600" title="Add Note" blur wire:model.defer="modal">
        <form class="space=y=2 flex-col" wire:submit.prevent='addNote'>
            <x-textarea class="text-primary-800 placeholder:text-primary-200" label="Your notes" placeholder="Write your notes" wire:model.defer="note" />
            <x-button class="mt-2 border-primary-800 text-primary-900" type="submit">
                <span class="text-primary-900">
                    Save Note
                </span>
            </x-button>
        </form>
    </x-modal.card>
    <x-modal.card class="text-primary-600" title="Rejection Note" description="Please provide further explanation for rejection of this travel order" blur wire:model.defer="modalRejection">
        <form class="space=y=2 flex-col" wire:submit.prevent='reject'>
            <x-textarea class="text-primary-800 placeholder:text-primary-200" label="Your notes" placeholder="Write your notes" wire:model.defer="rejectionNote" />
            <x-button class="mt-2 border-primary-800 text-primary-900" type="submit">
                <span class="text-primary-900">
                    Proceed
                </span>
            </x-button>
        </form>
    </x-modal.card>
    <x-dialog id="custom" title="Reject Travel Order" description="Confirm Rejection of travel order">
        <div class="mt-2 w-full items-center space-y-2">
            <div class="min-w-0 flex-1">
                <p>Reject Travel Order : {{ $travel_order->tracking_code }} for the following reason/s:</p>
                <p class="whitespace-pre-line">{{ $rejectionNote }}</p>
            </div>
        </div>
    </x-dialog>
</div>
