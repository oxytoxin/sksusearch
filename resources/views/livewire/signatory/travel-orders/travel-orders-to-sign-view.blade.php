<div class="">
	@php
		$total_amount = $travel_order->registration_amount;
	@endphp
	<div class="grid grid-cols-1 lg:grid-cols-3">
		<div class="col-span-1 flex-row lg:col-span-2">

			<div class="border-primary-200 rounded-md border-b bg-white px-4 py-5 sm:px-6 md:rounded-none md:rounded-t-lg">
				<div class="-mt-4 -ml-4 block flex-wrap items-center justify-between sm:flex-nowrap">
					<div class="mt-4 ml-4">
						<h3 class="text-primary-900 text-lg font-medium leading-6">Travel Order Details</h3>
						<p class="text-primary-500 mt-4 text-sm">Tracking Code: {{ $travel_order->tracking_code }}</p>
						<p class="text-primary-500 mt-1 text-sm">Travel Order Type:
							{{ $travel_order->travel_order_type->name }}</p>
						<p class="text-primary-500 mt-1 text-sm">Date Range:
							{{ $travel_order->date_from->format('F d Y') }} to
							{{ $travel_order->date_to->format('F d Y') }}</p>
						@if ($travel_order->travel_order_type_id == 1)
							@if ($travel_order->other_details == '')
								<p class="text-primary-500 mt-1 text-sm">Destination:
									{{ $travel_order->philippine_city->city_municipality_description }},
									{{ $travel_order->philippine_province->province_description }},
									{{ $travel_order->philippine_region->region_description }}</p>
							@else
								<p class="text-primary-500 mt-1 text-sm">Destination:
									{{ $travel_order->other_details }},
									{{ $travel_order->philippine_city->city_municipality_description }},
									{{ $travel_order->philippine_province->province_description }},
									{{ $travel_order->philippine_region->region_description }}</p>
							@endif
						@endif
						<p class="text-primary-500 mt-1 text-sm">Purpose: {{ $travel_order->purpose }}</p>
						<p class="text-primary-500 mt-1">Registration Fee: <span
								class="">{{ $travel_order->registration_amount > 0 ? $travel_order->registration_amount : 'N/A' }}</span>
						</p>
						{{-- <p class="mt-1 text-primary-500">Total Amount: <span class="">{{ $total_amount }}</span>
						</p> --}}
						@php
							$signatory_ids = $travel_order->signatories()->pluck('user_id');
							$signatory_order = $travel_order->signatories()->pluck('is_approved');
							$has_approved = $travel_order
							    ->signatories()
							    ->wherePivot('user_id', auth()->id())
							    ->pluck('is_approved');
                            $sig_id = $travel_order
							    ->signatories()
							    ->wherePivot('user_id', auth()->id())
							    ->pluck('travel_order_signatories.id');
							$is_first_signatory = false;
							if (auth()->id() == $signatory_ids[0]) {
							    $is_first_signatory = true;
							}
							// dd($signatory_order);
						@endphp
						@if ($has_approved[0] == 0)
							@if ($is_first_signatory)
								<div class="flex w-full justify-between">
									<span>&nbsp</span>
									<div class="flex space-x-3">
										<button class="text-primary-600 hover:text-primary-400 flex text-sm" wire:click.prevent="approve">
											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
												<path fill-rule="evenodd"
													d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
													clip-rule="evenodd" />
											</svg>
											<span class="">Approve Travel Order</span>
										</button>
										<button class="flex text-sm text-red-500 hover:text-red-300">
											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
												<path
													d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
											</svg>
											<span class="">Reject Travel Order</span>
										</button>
									</div>
								</div>
							@else
                            @php
                               
                               $still_disapproved =$travel_order->signatories()->wherePivot('travel_order_id', $travel_order->id)->wherePivot('id','<',$sig_id)->wherePivot('is_approved',false)->count();
                            @endphp
								@if ($still_disapproved == 0)
                                <div class="flex w-full justify-between">
									<span>&nbsp</span>
									<div class="flex space-x-3">
										<button class="text-primary-600 hover:text-primary-400 flex text-sm" wire:click.prevent="approve">
											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
												<path fill-rule="evenodd"
													d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
													clip-rule="evenodd" />
											</svg>
											<span class="">Approve Travel Order</span>
										</button>
										<button class="flex text-sm text-red-500 hover:text-red-300">
											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
												<path
													d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
											</svg>
											<span class="">Reject Travel Order</span>
										</button>
									</div>
								</div>
                                @endif
							@endif
						@endif
					</div>

				</div>
			</div>

			<div class="border-primary-200 mt-2 rounded-md border-b bg-white px-4 py-5 sm:px-6 md:rounded-none md:rounded-b-lg">
				<div class="-mt-4 -ml-4 flex flex-wrap items-center justify-between sm:flex-nowrap">
					<div class="mt-4 ml-4">
						<h3 class="text-primary-900 text-lg font-medium leading-6"></h3>
						@foreach ($travel_order->signatories as $signatory)
							<p class="text-primary-500 mt-4 text-sm">Signatory:
								{{ $signatory->employee_information->full_name }}</p>
							<p class="text-primary-500 mt-1 text-sm">Approval Status:
								{{ $signatory->pivot->is_approved ? 'Approved' : 'Pending' }}</p>
							<p class="text-primary-500 mt-1 text-sm">Date Approved:
								{{ $signatory->pivot->is_approved ? $signatory->pivot->updated_at->format('F d, Y') : 'Unavailable' }}
							</p>
							<p class="text-primary-500 mt-1 text-sm">Time Approved:
								{{ $signatory->pivot->is_approved ? $signatory->pivot->updated_at->format('h:i:s a') : 'Unavailable' }}
							</p>
						@endforeach

					</div>
				</div>
			</div>
		</div>

		<div
			class="border-primary-300 max-h-screen-70 soft-scrollbar col-span-1 ml-4 overflow-y-auto rounded-md border-b bg-white px-4 py-5">
			<div class="mt-6 flow-root">
				<ul role="list" class="divide-primary-200 -my-5 divide-y">
					<div class="flex w-full justify-between">
						<h3 class="text-primary-600 text-lg font-semibold">Notes</h3>
						<button type="button" wire:click="$set('modal',true)" class="my-auto flex px-4 text-center">
							<span class="my-auto text-sm"> Add note</span>
							<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
								stroke="currentColor" class="my-auto h-3 w-auto">
								<path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
							</svg>
						</button>

					</div>

					@forelse ($travel_order->sidenotes as $sidenote)
						@if (!($loop->index + 1 > $limit))
							<li class="py-5">
								<div class="relative focus-within:ring-2 focus-within:ring-indigo-500">
									<h3 class="text-primary-800 flex justify-between text-sm font-semibold">
										<span class="absolute inset-0" aria-hidden="true"></span>
										<span class="flex">{{ $sidenote->user->employee_information->full_name }}</span>
										<span class="flex">{{ $sidenote->created_at->format('M d, Y') }}</span>
									</h3>
									<p class="text-primary-600 line-clamp-2 mt-1 text-sm">
										{{ $sidenote->content }}</p>
								</div>
							</li>
						@endif
					@empty
						<li class="py-5">
							<div class="relative focus-within:ring-2 focus-within:ring-indigo-500">
								<h3 class="text-primary-300 text-sm font-light italic">
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
					<button type="button" wire:click="showMore()"
						class="text-primary-700 border-primary-300 hover:bg-primary-50 flex w-full items-center justify-center rounded-md border bg-white px-4 py-2 text-sm font-medium shadow-sm">View
						more</button>
				</div>
			@endif
		</div>

	</div>

	<x-modal.card title="Add Note" blur wire:model.defer="modal" class="text-primary-600">
		<form wire:submit.prevent='addNote' class="space=y=2 flex-col">
			<x-textarea class="text-primary-800 placeholder:text-primary-200" label="Your notes"
				placeholder="Write your notes" wire:model.defer="note" />
			<x-button type="submit" class="text-primary-900 border-primary-800 mt-2">
				<span class="text-primary-900">
					Save Note
				</span>
			</x-button>
		</form>

	</x-modal.card>

</div>
 