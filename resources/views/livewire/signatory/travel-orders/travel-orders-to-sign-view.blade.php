<div class="">
	<div class="grid grid-cols-1 lg:grid-cols-3">
		<div class="col-span-1 flex-row lg:col-span-2">

			<div class="border-primary-200 rounded-md border-b bg-white px-4 py-5 sm:px-6 md:rounded-none md:rounded-tl-lg">
				<div class="-mt-4 -ml-4 flex flex-wrap items-center justify-between sm:flex-nowrap">
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

					</div>
				</div>
			</div>

			<div class="border-primary-200 mt-5 rounded-md border-b bg-white px-4 py-5 sm:px-6 md:rounded-none md:rounded-bl-lg">
				<div class="-mt-4 -ml-4 flex flex-wrap items-center justify-between sm:flex-nowrap">
					<div class="mt-4 ml-4">
						<h3 class="text-primary-900 text-lg font-medium leading-6">Status</h3>
						@foreach ($travel_order->signatories as $signatory)
							<p class="text-primary-500 mt-4 text-sm">Signatory:
								{{ $signatory->employee_information->full_name }}</p>
							<p class="text-primary-500 mt-1 text-sm">Approval Status:
								{{ $signatory->pivot->is_approved ? 'Approved' : 'Pending' }}</p>
							<p class="text-primary-500 mt-1 text-sm">Date Approved:
								{{ $signatory->pivot->is_approved ? $signatory->pivot->updated_at->format('F d, Y') : 'Unavailable' }}
							</p>
						@endforeach

					</div>
				</div>
			</div>
		</div>

		<div
			class="border-primary-300 col-span-1 ml-4 rounded-md border-b bg-white px-4 py-5 md:rounded-l-none md:rounded-r-3xl">
			<div class="mt-6 flow-root">
				<ul role="list" class="divide-primary-200 -my-5 divide-y">
					<div class="flex w-full justify-between">
						<h3 class="text-primary-600 text-sm font-semibold">Notes</h3>
						<button type="button" wire:click="showMore()" class="flex text-center my-auto px-4 ">
                            <span class="my-auto text-sm"> Add note</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-auto h-3 my-auto">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                        </button>
					</div>
					@if ($travel_order->sidenotes->count() >= 1)
						@foreach ($travel_order->sidenotes()->limit($limit) as $sidenote)
							<li class="py-5">
								<div class="relative focus-within:ring-2 focus-within:ring-indigo-500">
									<h3 class="text-primary-800 text-sm font-semibold">
										<span class="absolute inset-0" aria-hidden="true"></span>
										{{ $sidenote->user->employee_information->full_name }}
									</h3>
									<p class="text-primary-600 line-clamp-2 mt-1 text-sm">
										{{ $sidenote->user->content }}</p>
									<p class="text-primary-300 mt-1 text-right text-sm">
										{{ $sidenote->created_at->format('M d, Y') }}</p>
								</div>
							</li>
						@endforeach
					@else
						<li class="py-5">
							<div class="relative focus-within:ring-2 focus-within:ring-indigo-500">
								<h3 class="text-primary-300 text-sm font-light italic">
									<span class="absolute inset-0" aria-hidden="true"></span>
									Nothing to show
								</h3>
							</div>
						</li>
					@endif

				</ul>
			</div>
			@if ($travel_order->sidenotes->count() >= $limit)
				<div class="mt-6">
					<button type="button" wire:click="showMore()"
						class="text-primary-700 border-primary-300 hover:bg-primary-50 flex w-full items-center justify-center rounded-md border bg-white px-4 py-2 text-sm font-medium shadow-sm">View
						more</button>
				</div>
			@endif
		</div>

	</div>

</div>
