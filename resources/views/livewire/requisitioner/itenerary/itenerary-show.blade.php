<div>
    @php
        $total_amount= $travel_order->registration_amount;
        foreach ($coverage as $value) {
            $total_amount += $value['total_expenses'];  
        }
        $itenerary_total_amount=0;
    @endphp
	<div class="text-md flex-col space-y-5">
		<div class="border-primary-200 rounded-md border-b bg-white px-4 py-5 sm:px-6 md:rounded-lg">
			<div class="-mt-4 -ml-4 w-full flex-wrap items-center justify-between sm:flex-nowrap">
				<div class="mt-4 ml-4" x-data="{ open: false }">
					<div class="flex w-full justify-between">
						<h3
							class="text-primary-700 hover:text-primary-400 flex w-full justify-between text-lg font-medium leading-6 hover:cursor-pointer"
							x-on:click="open= !open">Travel Order Details
							<svg xmlns="http://www.w3.org/2000/svg" :class="open ? 'rotate-180' : 'rotate-360'" fill="none"
								viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
								<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 5.25l-7.5 7.5-7.5-7.5m15 6l-7.5 7.5-7.5-7.5" />
							</svg>
						</h3>
					</div>
					<div x-show='open' x-transition:enter='ease-out transition duration-400'
						x-transition:enter-start='opacity-0 scale-100' x-transition:enter-end='opacity-100 scale-100'
						x-transition:leave='transition ease-in duration-400' x-transition:leave-start='opacity-100 scale-100'
						x-transition:leave-end='opacity-0 scale-0' class="origin-top-left">
						<p class="text-primary-500 mt-4">Tracking Code: {{ $travel_order->tracking_code }}</p>
						<p class="text-primary-500 mt-1">Travel Order Type: {{ $travel_order->travel_order_type->name }}</p>
						<p class="text-primary-500 mt-1">Date Range: {{ $travel_order->date_from->format('F d Y') }} to
							{{ $travel_order->date_to->format('F d Y') }}</p>
						@if ($travel_order->travel_order_type_id == 1)
							@if ($travel_order->other_details == '')
								<p class="text-primary-500 mt-1">Destination:
									{{ $travel_order->philippine_city->city_municipality_description }},
									{{ $travel_order->philippine_province->province_description }},
									{{ $travel_order->philippine_region->region_description }}</p>
							@else
								<p class="text-primary-500 mt-1">Destination: {{ $travel_order->other_details }},
									{{ $travel_order->philippine_city->city_municipality_description }},
									{{ $travel_order->philippine_province->province_description }},
									{{ $travel_order->philippine_region->region_description }}</p>
							@endif
						@endif
						<p class="text-primary-500 mt-1">Purpose: {{ $travel_order->purpose }}</p>
						<p class="text-primary-500 mt-1">Registration Fee: <span
								class="">{{ $travel_order->registration_amount > 0 ? $travel_order->registration_amount : 'N/A' }}</span>
						</p>
						<p class="text-primary-500 mt-1">Total Amount: <span
								class="">{{ $total_amount}}</span>
						</p>
					</div>
				</div>
			</div>
		</div>

		<div class="border-primary-200 rounded-md border-b bg-white px-4 py-5 sm:px-6 md:rounded-lg">
			<div class="-mt-4 -ml-4 w-full flex-wrap items-center justify-between sm:flex-nowrap">
				<div class="mt-4 ml-4">
					<div class="flex w-full justify-between">
						<h3
							class="text-primary-700 hover:text-primary-400 flex w-full justify-between text-lg font-medium leading-6 hover:cursor-pointer"
							x-on:click="open= !open">Itenerary

						</h3>
					</div>
					@foreach ($coverage as $covered)
						<div class="bg-primary-100 m-2 mb-1 ml-0 w-full flex-col rounded-md" x-data="{ open: true }">
							{{-- header --}}
							<div class="bg-primary-200 block w-full rounded-t-md border-b-0 p-2"
								:class="open ? 'shadow-md shadow-slate-400' : 'rounded-md'">
								<h3 class="text-primary-900 flex w-full justify-between font-bold" x-on:click="open = !open">
									{{ date_format(date_create($covered['date']), 'F d, Y') }}
									<svg xmlns="http://www.w3.org/2000/svg" :class="open ? 'rotate-180' : 'rotate-360'" fill="none"
										viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
										<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 5.25l-7.5 7.5-7.5-7.5m15 6l-7.5 7.5-7.5-7.5" />
									</svg>
								</h3>
							</div>
							<div>
								<div x-show='open' x-transition:enter='ease-out transition duration-400'
									x-transition:enter-start='opacity-0 scale-100' x-transition:enter-end='opacity-100 scale-100'
									x-transition:leave='transition ease-in duration-400' x-transition:leave-start='opacity-100 scale-100'
									x-transition:leave-end='opacity-0 scale-0' class="origin-top-left">

									<div class="flex-col px-4 pb-5 pt-2">

										<p class="text-primary-500 mt-4 font-semibold">Per Diem: <span class="font-normal">
												{{ $covered['per_diem'] }} </span></p>
										<p class="text-primary-500 mt-1 font-semibold">Services covered by registration:
											<span class="font-normal capitalize">
												@if ($covered['breakfast'])
													breakfast ,
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
										<div class="divide-primary-400 mt-4 divide-y-2">
											@foreach ($itenerary->itenerary_entries as $entry)
												@if ($entry->date->format('Y-m-d') == $covered['date'])
													<div class="normal_case w-full flex-col py-2">
														<p class="text-primary-900 font-semibold">Travelling to: <span
																class="font-normal">{{ $entry->place }}</span></p>
														<p class="text-primary-900 font-semibold">Mode of transportation: <span
																class="font-normal">{{ $entry->mot->name }}</span></p>
														<p class="text-primary-900 font-semibold">Departure time: <span
																class="font-normal">{{ $entry->departure_time->format('g:i a') }}</span></p>
														<p class="text-primary-900 font-semibold">Arrival time: <span
																class="font-normal">{{ $entry->arrival_time->format('g:i a') }}</span></p>
														<div class="text-primary-900 flex w-full justify-between font-semibold">Transporation Expenses: <span
																class="text-right font-normal">{{ $entry->transportation_expenses }}</span></div>
														<div class="text-primary-900 flex w-full justify-between font-semibold">Other Expenses: <span
																class="text-right font-normal">{{ $entry->other_expenses }}</span></div>
													</div>
                                                    @php
                                                        $itenerary_total_amount += ($entry->transportation_expenses + $entry->other_expenses);
                                                    @endphp
												@endif
											@endforeach
										</div>
										<div
											class="text-primary-500 border-primary-900 mt-2 flex w-full justify-between border-t-8 border-double font-bold">
											Total Expenses: <span class="text-right">{{ $covered['total_expenses'] }}</span></div>
									</div>
								</div>
                              
							</div>
						</div>
					@endforeach
                    <div
                    class="text-primary-500 px-4 flex w-full justify-between font-bold">
                    Grand Total: <span class="text-right">{{ $itenerary_total_amount }}</span></div>
				</div>
			</div>
		</div>
	</div>
</div>
