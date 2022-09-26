<div>
	<div class="flex-col space-y-5">
		<div class="border-primary-200 rounded-md border-b bg-white px-4 py-5 sm:px-6 md:rounded-lg">
			<div class="-mt-4 -ml-4 w-full flex-wrap items-center justify-between sm:flex-nowrap">
				<div class="mt-4 ml-4" x-data="{open:true}">
					<div class="flex w-full justify-between">
						<h3 class="text-primary-700 hover:text-primary-400 text-lg font-medium leading-6 flex w-full justify-between hover:cursor-pointer" x-on:click="open= !open">Travel Order Details
                            <svg xmlns="http://www.w3.org/2000/svg" :class="open ? 'rotate-180':'rotate-360'" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                class="h-6 w-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 5.25l-7.5 7.5-7.5-7.5m15 6l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </h3>
					</div>
					<div x-show = 'open' 
                    x-transition:enter = 'ease-out transition duration-400'
                    x-transition:enter-start =  'opacity-0 scale-100'
                    x-transition:enter-end =  'opacity-100 scale-100'
                    x-transition:leave =  'transition ease-in duration-400'
                    x-transition:leave-start = 'opacity-100 scale-100' 
                    x-transition:leave-end = 'opacity-0 scale-0' class="origin-top-left">
						<p class="text-primary-500 mt-4 text-sm">Tracking Code: {{ $travel_order->tracking_code }}</p>
						<p class="text-primary-500 mt-1 text-sm">Travel Order Type: {{ $travel_order->travel_order_type->name }}</p>
						<p class="text-primary-500 mt-1 text-sm">Date Range: {{ $travel_order->date_from->format('F d Y') }} to
							{{ $travel_order->date_to->format('F d Y') }}</p>
						@if ($travel_order->travel_order_type_id == 1)
							@if ($travel_order->other_details == '')
								<p class="text-primary-500 mt-1 text-sm">Destination:
									{{ $travel_order->philippine_city->city_municipality_description }},
									{{ $travel_order->philippine_province->province_description }},
									{{ $travel_order->philippine_region->region_description }}</p>
							@else
								<p class="text-primary-500 mt-1 text-sm">Destination: {{ $travel_order->other_details }},
									{{ $travel_order->philippine_city->city_municipality_description }},
									{{ $travel_order->philippine_province->province_description }},
									{{ $travel_order->philippine_region->region_description }}</p>
							@endif
						@endif
						<p class="text-primary-500 mt-1 text-sm">Purpose: {{ $travel_order->purpose }}</p>
					</div>
				</div>
			</div>
		</div>

        <div class="border-primary-200 rounded-md border-b bg-white px-4 py-5 sm:px-6 md:rounded-lg">
			<div class="-mt-4 -ml-4 w-full flex-wrap items-center justify-between sm:flex-nowrap">
				<div class="mt-4 ml-4">
					<div class="flex w-full justify-between">
						<h3 class="text-primary-700 hover:text-primary-400 text-lg font-medium leading-6 flex w-full justify-between hover:cursor-pointer" x-on:click="open= !open">Itenerary
                            
                        </h3>
					</div>
                    @foreach ($itenerary->itenerary_entries as )
                        
                    @endforeach
                    <div class="w-full flex-col m-2 rounded-md bg-primary-100 "  x-data="{open:true}">
                        {{-- header --}}
                        <div class="w-full block p-2 bg-primary-200 rounded-t-md  border-b-0 " :class="open ?'shadow-md shadow-slate-400':'rounded-md'">
                            <h3 class="text-primary-900 font-bold flex w-full justify-between" x-on:click="open = !open">
                                September 30, 2099
                                <svg xmlns="http://www.w3.org/2000/svg" :class="open ? 'rotate-180':'rotate-360'" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                class="h-6 w-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 5.25l-7.5 7.5-7.5-7.5m15 6l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </h3>
                        </div>
                        <div>
                            <div x-show = 'open' 
                            x-transition:enter = 'ease-out transition duration-400'
                            x-transition:enter-start =  'opacity-0 scale-100'
                            x-transition:enter-end =  'opacity-100 scale-100'
                            x-transition:leave =  'transition ease-in duration-400'
                            x-transition:leave-start = 'opacity-100 scale-100' 
                            x-transition:leave-end = 'opacity-0 scale-0' class="origin-top-left">
                                <div class="flex px-4 pb-5 pt-2">
                                    
                                </div>             
                            </div>
                        </div>
                    </div>

					
				</div>
			</div>
		</div>
	</div>
</div>
