<div>
	@if ($currentVehicle ==null)
		{{ $this->redirectToCreateVehicle() }}
	@else
	<div class="flex flex-col h-full bg-white rounded-lg">
		<header class="flex items-center justify-between flex-none px-6 py-4 border-b border-gray-200">
			<div class="flex-col text-lg font-semibold text-gray-900 capitalize">
				<div class="flex">
					<time>{{ $currentMonth }} {{ $currentYearNumber }}</time>
				</div>
				<div class="flex lg:hidden">
					<span class="text-sm text-gray-600"><span class="text-extrabold">Vehicle: </span>{{ $currentVehicle->model . ' (' . $currentVehicle->plate_number.')' }}</span>
				</div>
			</div>
			
			<div class="flex items-center" x-data="{ showMeSelect: true }" x-cloak>
				<div class="hidden md:mr-4 md:flex md:items-center">
					<div class="relative flex flex-row items-center">
						<label for="search" class="text-sm md:mr-2">Search Vehicle </label>
						<div class="relative" class="origin-center-left" x-show='showMeSelect == false'
							x-transition:enter='transition ease-out duration-100' x-transition:enter-start='transform opacity-85 scale-50'
							x-transition:enter-end='transform opacity-100 scale-100' x-transition:leave='transition ease-out duration-75'
							x-transition:leave-start='transform opacity-100 scale-100' x-transition:leave-end='transfrom opacity-0 scale-50'>
							<input type="text" name="" id="search"
								class="flex items-center py-2 pl-3 pr-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
						</div>
					</div>

				</div>
				<!-- Enabled: "bg-indigo-600", Not Enabled: "bg-gray-200" -->
				<button type="button" x-on:click="showMeSelect = !showMeSelect"
					class="relative flex-shrink-0 hidden h-6 mr-4 transition-colors duration-200 ease-in-out border-2 border-transparent rounded-full cursor-pointer bg-primary-600 focus:ring-primary-500 w-11 focus:outline-none focus:ring-2 focus:ring-offset-2 md:inline-flex"
					role="switch" aria-checked="false">
					<span class="sr-only">Use setting</span>
					<!-- Enabled: "", Not Enabled: "translate-x-0" -->
					<span aria-hidden="true"
						:class="showMeSelect == false ? 'translate-x-0 bg-primary-alt-100' : 'translate-x-5 bg-primary-300 border border-white'"
						class="inline-block w-5 h-5 transition duration-200 ease-in-out transform rounded-full shadow pointer-events-none ring-0"></span>
				</button>

				<div class="hidden md:mr-4 md:flex md:items-center">
					<div class="flex flex-row items-center">
						<span class="text-sm md:mr-2">Select Vehicle </span>
						<div class="relative" x-data="{ open: false }" x-show='showMeSelect'
							x-transition:enter='transition ease-out duration-100' x-transition:enter-start='transform opacity-85 scale-50'
							x-transition:enter-end='transform opacity-100 scale-100' x-transition:leave='transition ease-out duration-75'
							x-transition:leave-start='transform opacity-100 scale-100' x-transition:leave-end='transfrom opacity-0 scale-50'>
							<button type="button" x-on:click="open=true"
								x-tooltip.raw="Select vehicle from dropdown to show schedule of vehicle"
								class="flex items-center py-2 pl-3 pr-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50"
								id="menu-button" aria-expanded="false" aria-haspopup="true">
								{{ $currentVehicle->model . ' (' . $currentVehicle->plate_number.')' }}
								<!-- Heroicon name: mini/chevron-down -->
								<svg class="w-5 h-5 ml-2 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
									fill="currentColor" aria-hidden="true">
									<path fill-rule="evenodd"
										d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
										clip-rule="evenodd" />
								</svg>
							</button>
							<div x-show="open" @click.outside="open = false" x-cloak x-transition:enter='transition ease-out duration-100'
								x-transition:enter-start='transform opacity-0 scale-95' x-transition:enter-end='transform opacity-100 scale-100'
								x-transition:leave='transition ease-in duration-75' x-transition:leave-start='transform opacity-100 scale-100'
								x-transition:leave-end='transform opacity-0 scale-95'
								class="absolute right-0 z-10 mt-2 overflow-hidden origin-top-right bg-white rounded-md shadow-lg shadow-slate-700 ring-1 ring-black ring-opacity-5 focus:outline-none"
								role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
								<div class="flex flex-col w-full py-1" role="none">
									@foreach ($vehicles as $vehicle)
									@if ($vehicle->model . ' : ' . $vehicle->plate_number == $currentVehicle->model . ' : ' . $currentVehicle->plate_number)
									<button type="button" x-on:click="open=false" wire:click="setVehicle({{ $vehicle->id }})"
										class="flex w-full px-4 py-2 text-sm text-primary-700 bg-primary-200" role="menuitem" tabindex="-1"
										id="menu-item-0">{{ $vehicle->model . ' (' . $vehicle->plate_number.')' }}</button>
									@else
									<button type="button" x-on:click="open=false" wire:click="setVehicle({{ $vehicle->id }})"
										class="flex w-full px-4 py-2 text-sm text-gray-700 hover:bg-primary-100" role="menuitem" tabindex="-1"
										id="menu-item-0">{{ $vehicle->model . ' (' . $vehicle->plate_number.')' }}</button>
									@endif
									
									@endforeach
								</div>
							</div>
						</div>
					</div>

				</div>

				<div class="flex items-center rounded-md shadow-sm md:items-stretch" x-data="{}">
					<button type="button" x-tooltip.raw="Previous week" wire:click="previousWeek"
						class="flex items-center justify-center py-2 pl-3 pr-4 text-gray-400 bg-white border border-r-0 border-gray-300 rounded-l-md hover:text-gray-500 focus:relative md:w-9 md:px-2 md:hover:bg-gray-50">
						<span class="sr-only">Previous week</span>
						<!-- Heroicon name: mini/chevron-left -->
						<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
							aria-hidden="true">
							<path fill-rule="evenodd"
								d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z"
								clip-rule="evenodd" />
						</svg>
					</button>
					<button type="button" wire:click="currentweek"
						class="hidden border-t border-b border-gray-300 bg-white px-3.5 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 focus:relative md:block">
						Current week
					</button>
					<span class="relative w-px h-5 -mx-px bg-gray-300 md:hidden"></span>
					<button type="button" x-tooltip.raw="Next week" wire:click="nextWeek"
						class="flex items-center justify-center py-2 pl-4 pr-3 text-gray-400 bg-white border border-l-0 border-gray-300 rounded-r-md hover:text-gray-500 focus:relative md:w-9 md:px-2 md:hover:bg-gray-50">
						<span class="sr-only">Next week</span>
						<!-- Heroicon name: mini/chevron-right -->
						<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
							aria-hidden="true">
							<path fill-rule="evenodd"
								d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
								clip-rule="evenodd" />
						</svg>
					</button>

					<div class="w-px h-6 ml-6 bg-gray-300"></div>
					<button type="button"
						class="hidden px-4 py-2 ml-6 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 md:flex">Add
						event</button>
				</div>

				<div class="relative ml-6 md:hidden" x-data="{ open: false }">
					<button type="button" x-on:click="open = !open"
						class="flex items-center p-2 -mx-2 text-gray-400 border border-transparent rounded-full hover:text-gray-500"
						id="menu-0-button" aria-expanded="false" aria-haspopup="true">
						<span class="sr-only">Open menu</span>
						<!-- Heroicon name: mini/ellipsis-horizontal -->
						<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
							aria-hidden="true">
							<path
								d="M3 10a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM8.5 10a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM15.5 8.5a1.5 1.5 0 100 3 1.5 1.5 0 000-3z" />
						</svg>
					</button>

					<div @click.outside="open = false" x-show='open' x-transition:enter='transition ease-out duration-100'
						x-transition:enter-start='transform opacity-0 scale-95' x-transition:enter-end='transform opacity-100 scale-100'
						x-transition:leave='transition ease-in duration-75' x-transition:leave-start='transform opacity-100 scale-100'
						x-transition:leave-end='transform opacity-0 scale-0'
						class="absolute right-0 z-10 mt-3 overflow-hidden text-left origin-top-right bg-white divide-y divide-gray-100 rounded-md shadow-lg shadow-slate-700 w-36 ring-1 ring-black ring-opacity-5 focus:outline-none"
						role="menu" aria-orientation="vertical" aria-labelledby="menu-0-button" tabindex="-1">
						<div class="py-1" role="none">
							<!-- Active: "bg-gray-100 text-gray-900", Not Active: "text-gray-700" -->
							<a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1"
								id="menu-0-item-0">Create event</a>
						</div>
						<div class="py-1" role="none">
							<button type="button" wire:click="currentweek" class="flex w-full px-4 py-2 text-sm text-gray-700 hover:bg-primary-100" role="menuitem"
								tabindex="-1" id="menu-0-item-1">Go to this week</button>
						</div>
						<div class="py-1 " role="none">
							@foreach ($vehicles as $vehicle)
								@if ($vehicle->model . ' : ' . $vehicle->plate_number == $currentVehicle->model . ' : ' . $currentVehicle->plate_number)
								<button type="button"x-on:click="open=false" wire:click="setVehicle({{ $vehicle->id }})"
									class="flex w-full px-4 py-2 text-sm text-left text-primary-700 bg-primary-200" role="menuitem" tabindex="-1"
									id="menu-0-item-{{ $loop->index }}">{{ $vehicle->model . ' (' . $vehicle->plate_number.')' }}
								</button>
								@else
								<button type="button"x-on:click="open=false" wire:click="setVehicle({{ $vehicle->id }})"
									class="flex w-full px-4 py-2 text-sm text-left text-gray-700 hover:bg-primary-100" role="menuitem" tabindex="-1"
									id="menu-0-item-{{ $loop->index }}">{{ $vehicle->model . ' (' . $vehicle->plate_number.')' }}
								</button>
								@endif
								
							@endforeach
						</div>
					</div>
				</div>
			</div>
		</header>
		<div class="flex flex-col flex-auto overflow-auto bg-white-red isolate">
			<div style="width: 165%" class="flex flex-col flex-none max-w-full sm:max-w-none md:max-w-full">
				<div class="sticky top-0 z-30 flex-none bg-white shadow ring-1 ring-black ring-opacity-5 sm:pr-8">
					<div class="grid grid-cols-7 text-sm leading-6 text-gray-500 sm:hidden">
						<button type="button" class="flex flex-col items-center pt-2 pb-3">M <span
								class="flex items-center justify-center w-8 h-8 mt-1 font-semibold text-gray-900">{{ $dates[0]['day_date'] }}</span></button>
						<button type="button" class="flex flex-col items-center pt-2 pb-3">T <span
								class="flex items-center justify-center w-8 h-8 mt-1 font-semibold text-gray-900">{{ $dates[1]['day_date'] }}</span></button>
						<button type="button" class="flex flex-col items-center pt-2 pb-3">W <span
								class="flex items-center justify-center w-8 h-8 mt-1 font-semibold text-gray-900">{{ $dates[2]['day_date'] }}</span></button>
						{{-- <button type="button" class="flex flex-col items-center pt-2 pb-3">W <span
								class="flex items-center justify-center w-8 h-8 mt-1 font-semibold text-white bg-indigo-600 rounded-full">12</span></button> --}}
						<button type="button" class="flex flex-col items-center pt-2 pb-3">T <span
								class="flex items-center justify-center w-8 h-8 mt-1 font-semibold text-gray-900">{{ $dates[3]['day_date'] }}</span></button>
						<button type="button" class="flex flex-col items-center pt-2 pb-3">F <span
								class="flex items-center justify-center w-8 h-8 mt-1 font-semibold text-gray-900">{{ $dates[4]['day_date'] }}</span></button>
						<button type="button" class="flex flex-col items-center pt-2 pb-3">S <span
								class="flex items-center justify-center w-8 h-8 mt-1 font-semibold text-gray-900">{{ $dates[5]['day_date'] }}</span></button>
						<button type="button" class="flex flex-col items-center pt-2 pb-3">S <span
								class="flex items-center justify-center w-8 h-8 mt-1 font-semibold text-gray-900">{{ $dates[6]['day_date'] }}</span></button>
					</div>

					<div
						class="hidden grid-cols-7 -mr-px text-sm leading-6 text-gray-500 border-r border-gray-100 divide-x divide-gray-100 sm:grid">
						<div class="col-end-1 w-14"></div>
						<div class="flex items-center justify-center py-3">
							@if ($dates[0] == $today->format('d'))
								{{-- <span class="flex items-baseline">Mon
									<span
										class="ml-1.5 flex h-8 w-8 items-center justify-center rounded-full bg-indigo-600 font-semibold text-white">{{ $dates[0] }}</span>
								</span> --}}
								<span>Mon <span
										class="items-center justify-center font-semibold text-gray-900">{{ $dates[0]['day_date'] }}</span></span>
							@else
								<span>Mon <span
										class="items-center justify-center font-semibold text-gray-900">{{ $dates[0]['day_date'] }}</span></span>
							@endif
						</div>
						<div class="flex items-center justify-center py-3">

							@if ($dates[1] == $today->format('d'))
								{{-- <span class="flex items-baseline">Tue
									<span
										class="font-semibolds ml-1.5 flex h-8 w-8 items-center justify-center rounded-full bg-indigo-600 text-white">{{ $dates[1] }}</span>
								</span> --}}
								<span>Tue <span
										class="items-center justify-center font-semibold text-gray-900">{{ $dates[1]['day_date'] }}</span></span>
							@else
								<span>Tue <span
										class="items-center justify-center font-semibold text-gray-900">{{ $dates[1]['day_date'] }}</span></span>
							@endif
						</div>
						<div class="flex items-center justify-center py-3">

							@if ($dates[2] == $today->format('d'))
								{{-- <span class="flex items-baseline">Wed
									<span
										class="ml-1.5 flex h-8 w-8 items-center justify-center rounded-full bg-indigo-600 font-semibold text-white">{{ $dates[2] }}</span>
								</span> --}}
								<span>Wed <span
										class="items-center justify-center font-semibold text-gray-900">{{ $dates[2]['day_date'] }}</span></span>
							@else
								<span>Wed <span
										class="items-center justify-center font-semibold text-gray-900">{{ $dates[2]['day_date'] }}</span></span>
							@endif

						</div>
						<div class="flex items-center justify-center py-3">

							@if ($dates[3] == $today->format('d'))
								{{-- <span class="flex items-baseline">Thu
									<span
										class="ml-1.5 flex h-8 w-8 items-center justify-center rounded-full bg-indigo-600 font-semibold text-white">{{ $dates[3] }}</span>
								</span> --}}
								<span>Thu <span
										class="items-center justify-center font-semibold text-gray-900">{{ $dates[3]['day_date'] }}</span></span>
							@else
								<span>Thu <span
										class="items-center justify-center font-semibold text-gray-900">{{ $dates[3]['day_date'] }}</span></span>
							@endif
						</div>
						<div class="flex items-center justify-center py-3">

							@if ($dates[4] == $today->format('d'))
								{{-- <span class="flex items-baseline">Fri
									<span
										class="ml-1.5 flex h-8 w-8 items-center justify-center rounded-full bg-indigo-600 font-semibold text-white">{{ $dates[4] }}</span>
								</span> --}}
								<span>Fri <span
										class="items-center justify-center font-semibold text-gray-900">{{ $dates[4]['day_date'] }}</span></span>
							@else
								<span>Fri <span
										class="items-center justify-center font-semibold text-gray-900">{{ $dates[4]['day_date'] }}</span></span>
							@endif
						</div>
						<div class="flex items-center justify-center py-3">

							@if ($dates[5] == $today->format('d'))
								{{-- <span class="flex items-baseline">Sat
									<span
										class="ml-1.5 flex h-8 w-8 items-center justify-center rounded-full bg-indigo-600 font-semibold text-white">{{ $dates[5] }}</span>
								</span> --}}
								<span>Sat <span
										class="items-center justify-center font-semibold text-gray-900">{{ $dates[5]['day_date'] }}</span></span>
							@else
								<span>Sat <span
										class="items-center justify-center font-semibold text-gray-900">{{ $dates[5]['day_date'] }}</span></span>
							@endif
						</div>
						<div class="flex items-center justify-center py-3">
							@if ($dates[6] == $today->format('d'))
								{{-- <span class="flex items-baseline">Sun
									<span
										class="ml-1.5 flex h-8 w-8 items-center justify-center rounded-full bg-indigo-600 font-semibold text-white">{{ $dates[6] }}</span>
								</span> --}}
								<span>Sun <span
										class="items-center justify-center font-semibold text-gray-900">{{ $dates[6]['day_date'] }}</span></span>
							@else
								<span>Sun <span
										class="items-center justify-center font-semibold text-gray-900">{{ $dates[6]['day_date'] }}</span></span>
							@endif
						</div>
					</div>
				</div>
				<div class="flex flex-auto">
					<div class="sticky left-0 z-10 flex-none bg-white rounded-b-lg w-14 ring-1 ring-gray-100"></div>
					<div class="grid flex-auto grid-cols-1 grid-rows-1">
						<!-- Horizontal lines -->
						<div class="grid col-start-1 col-end-2 row-start-1 divide-y divide-gray-300"
							style="grid-template-rows: repeat(48, minmax(3.5rem, 1fr))">
							<div class="row-end-1 h-7"></div>
							<div>
								<div class="sticky left-0 z-20 -mt-2.5 -ml-14 w-14 pr-2 text-right text-xs leading-5 text-gray-400">12AM</div>
							</div>
							<div></div>
							<div>
								<div class="sticky left-0 z-20 -mt-2.5 -ml-14 w-14 pr-2 text-right text-xs leading-5 text-gray-400">1AM</div>
							</div>
							<div></div>
							<div>
								<div class="sticky left-0 z-20 -mt-2.5 -ml-14 w-14 pr-2 text-right text-xs leading-5 text-gray-400">2AM</div>
							</div>
							<div></div>
							<div>
								<div class="sticky left-0 z-20 -mt-2.5 -ml-14 w-14 pr-2 text-right text-xs leading-5 text-gray-400">3AM</div>
							</div>
							<div></div>
							<div>
								<div class="sticky left-0 z-20 -mt-2.5 -ml-14 w-14 pr-2 text-right text-xs leading-5 text-gray-400">4AM</div>
							</div>
							<div></div>
							<div>
								<div class="sticky left-0 z-20 -mt-2.5 -ml-14 w-14 pr-2 text-right text-xs leading-5 text-gray-400">5AM</div>
							</div>
							<div></div>
							<div>
								<div class="sticky left-0 z-20 -mt-2.5 -ml-14 w-14 pr-2 text-right text-xs leading-5 text-gray-400">6AM</div>
							</div>
							<div></div>
							<div>
								<div class="sticky left-0 z-20 -mt-2.5 -ml-14 w-14 pr-2 text-right text-xs leading-5 text-gray-400">7AM</div>
							</div>
							<div></div>
							<div>
								<div class="sticky left-0 z-20 -mt-2.5 -ml-14 w-14 pr-2 text-right text-xs leading-5 text-gray-400">8AM</div>
							</div>
							<div></div>
							<div>
								<div class="sticky left-0 z-20 -mt-2.5 -ml-14 w-14 pr-2 text-right text-xs leading-5 text-gray-400">9AM</div>
							</div>
							<div></div>
							<div>
								<div class="sticky left-0 z-20 -mt-2.5 -ml-14 w-14 pr-2 text-right text-xs leading-5 text-gray-400">10AM</div>
							</div>
							<div></div>
							<div>
								<div class="sticky left-0 z-20 -mt-2.5 -ml-14 w-14 pr-2 text-right text-xs leading-5 text-gray-400">11AM</div>
							</div>
							<div></div>
							<div>
								<div class="sticky left-0 z-20 -mt-2.5 -ml-14 w-14 pr-2 text-right text-xs leading-5 text-gray-400">12PM</div>
							</div>
							<div></div>
							<div>
								<div class="sticky left-0 z-20 -mt-2.5 -ml-14 w-14 pr-2 text-right text-xs leading-5 text-gray-400">1PM</div>
							</div>
							<div></div>
							<div>
								<div class="sticky left-0 z-20 -mt-2.5 -ml-14 w-14 pr-2 text-right text-xs leading-5 text-gray-400">2PM</div>
							</div>
							<div></div>
							<div>
								<div class="sticky left-0 z-20 -mt-2.5 -ml-14 w-14 pr-2 text-right text-xs leading-5 text-gray-400">3PM</div>
							</div>
							<div></div>
							<div>
								<div class="sticky left-0 z-20 -mt-2.5 -ml-14 w-14 pr-2 text-right text-xs leading-5 text-gray-400">4PM</div>
							</div>
							<div></div>
							<div>
								<div class="sticky left-0 z-20 -mt-2.5 -ml-14 w-14 pr-2 text-right text-xs leading-5 text-gray-400">5PM</div>
							</div>
							<div></div>
							<div>
								<div class="sticky left-0 z-20 -mt-2.5 -ml-14 w-14 pr-2 text-right text-xs leading-5 text-gray-400">6PM</div>
							</div>
							<div></div>
							<div>
								<div class="sticky left-0 z-20 -mt-2.5 -ml-14 w-14 pr-2 text-right text-xs leading-5 text-gray-400">7PM</div>
							</div>
							<div></div>
							<div>
								<div class="sticky left-0 z-20 -mt-2.5 -ml-14 w-14 pr-2 text-right text-xs leading-5 text-gray-400">8PM</div>
							</div>
							<div></div>
							<div>
								<div class="sticky left-0 z-20 -mt-2.5 -ml-14 w-14 pr-2 text-right text-xs leading-5 text-gray-400">9PM</div>
							</div>
							<div></div>
							<div>
								<div class="sticky left-0 z-20 -mt-2.5 -ml-14 w-14 pr-2 text-right text-xs leading-5 text-gray-400">10PM</div>
							</div>
							<div></div>
							<div>
								<div class="sticky left-0 z-20 -mt-2.5 -ml-14 w-14 pr-2 text-right text-xs leading-5 text-gray-400">11PM</div>
							</div>
							<div></div>
						</div>

						<!-- Vertical lines -->
						<div
							class="hidden grid-cols-7 col-start-1 col-end-2 grid-rows-1 row-start-1 divide-x divide-gray-200 sm:grid sm:grid-cols-7">
							<div class="col-start-1 row-span-full"></div>
							<div class="col-start-2 row-span-full"></div>
							<div class="col-start-3 row-span-full"></div>
							<div class="col-start-4 row-span-full"></div>
							<div class="col-start-5 row-span-full"></div>
							<div class="col-start-6 row-span-full"></div>
							<div class="col-start-7 row-span-full"></div>
							<div class="w-8 col-start-8 row-span-full"></div>
						</div>

						<!-- Events -->
						<ol class="grid grid-cols-1 col-start-1 col-end-2 row-start-1 sm:grid-cols-7 sm:pr-8"
							style="grid-template-rows: 1.75rem repeat(288, minmax(0, 1fr)) auto">

							@foreach ($schedules as $schedule)
								@switch($schedule->date_of_travel)
									@case($dates[0]['full_date'])
									@php
										$date1=new DateTime($schedule->time_start);
										$date2=$date1->diff(new DateTime($schedule->time_end));
										$difference = ($date2->i/60)+$date2->h;
									@endphp
									<li class="relative flex mt-px sm:col-start-1"
										style="grid-row: {{  date_format(date_create($schedule->time_start),'G')  * 12 + 2 }} / span {{ $difference * 12 }}">
										<a href="#"
											class="absolute flex flex-col p-2 overflow-y-auto text-xs leading-5 rounded-lg bg-cyan-200 group inset-1 hover:bg-cyan-100">
											<p class="order-1 font-semibold text-cyan-700">{{ $schedule->purpose }}
											</p>
											<p class="text-cyan-500 group-hover:text-cyan-700"><time datetime="2022-01-12T06:00">{{ date_format(date_create($schedule->time_start),'h:i a') }} - {{ date_format(date_create($schedule->time_end),'h:i a') }} </time></p>
										</a>
									</li>
									@break

									@case($dates[1]['full_date'])
									@php
										$date1=new DateTime($schedule->time_start);
										$date2=$date1->diff(new DateTime($schedule->time_end));
										$difference = ($date2->i/60)+$date2->h;
									@endphp
									<li class="relative flex mt-px sm:col-start-2"
										style="grid-row: {{  date_format(date_create($schedule->time_start),'G')  * 12 + 2 }} / span {{ $difference * 12 }}">
										<a href="#"
											class="absolute flex flex-col p-2 overflow-y-auto text-xs leading-5 bg-blue-200 rounded-lg group inset-1 hover:bg-blue-100">
											<p class="order-1 font-semibold text-blue-700">{{ $schedule->purpose }}
											</p>
											<p class="text-blue-500 group-hover:text-blue-700"><time datetime="2022-01-12T06:00">{{ date_format(date_create($schedule->time_start),'h:i a') }} - {{ date_format(date_create($schedule->time_end),'h:i a') }} </time></p>
										</a>
									</li>
									@break

									@case($dates[2]['full_date'])
									@php
										$date1=new DateTime($schedule->time_start);
										$date2=$date1->diff(new DateTime($schedule->time_end));
										$difference = ($date2->i/60)+$date2->h;
									@endphp
									<li class="relative flex mt-px sm:col-start-3"
										style="grid-row: {{  date_format(date_create($schedule->time_start),'G')  * 12 + 2 }} / span {{ $difference * 12 }}">
										<a href="#"
											class="absolute flex flex-col p-2 overflow-y-auto text-xs leading-5 bg-pink-200 rounded-lg group inset-1 hover:bg-pink-100">
											<p class="order-1 font-semibold text-pink-700">{{ $schedule->purpose }}
											</p>
											<p class="text-pink-500 group-hover:text-pink-700"><time datetime="2022-01-12T06:00">{{ date_format(date_create($schedule->time_start),'h:i a') }} - {{ date_format(date_create($schedule->time_end),'h:i a') }} </time></p>
										</a>
									</li>
									@break

									@case($dates[3]['full_date'])
									@php
										$date1=new DateTime($schedule->time_start);
										$date2=$date1->diff(new DateTime($schedule->time_end));
										$difference = ($date2->i/60)+$date2->h;
									@endphp
									<li class="relative flex mt-px sm:col-start-4"
										style="grid-row: {{  date_format(date_create($schedule->time_start),'G')  * 12 + 2 }} / span {{ $difference * 12 }}">
										<a href="#"
											class="absolute flex flex-col p-2 overflow-y-auto text-xs leading-5 rounded-lg bg-amber-200 group inset-1 hover:bg-amber-100">
											<p class="order-1 font-semibold text-amber-700">{{ $schedule->purpose }}
											</p>
											<p class="text-amber-600 group-hover:text-amber-700"><time datetime="2022-01-12T06:00">{{ date_format(date_create($schedule->time_start),'h:i a') }} - {{ date_format(date_create($schedule->time_end),'h:i a') }} </time></p>
										</a>
									</li>
									@break

									@case($dates[4]['full_date'])
									@php
										$date1=new DateTime($schedule->time_start);
										$date2=$date1->diff(new DateTime($schedule->time_end));
										$difference = ($date2->i/60)+$date2->h;
									@endphp
									<li class="relative flex mt-px sm:col-start-5"
										style="grid-row: {{  date_format(date_create($schedule->time_start),'G')  * 12 + 2 }} / span {{ $difference * 12 }}">
										<a href="#"
											class="absolute flex flex-col p-2 overflow-y-auto text-xs leading-5 rounded-lg bg-primary-200 group inset-1 hover:bg-primary-100">
											<p class="order-1 font-semibold text-primary-700">{{ $schedule->purpose }}
											</p>
											<p class="text-primary-500 group-hover:text-primary-700"><time datetime="2022-01-12T06:00">{{ date_format(date_create($schedule->time_start),'h:i a') }} - {{ date_format(date_create($schedule->time_end),'h:i a') }} </time></p>
										</a>
									</li>
									@break

									@case($dates[5]['full_date'])
									@php
										$date1=new DateTime($schedule->time_start);
										$date2=$date1->diff(new DateTime($schedule->time_end));
										$difference = ($date2->i/60)+$date2->h;
									@endphp
									<li class="relative flex mt-px sm:col-start-6"
										style="grid-row: {{  date_format(date_create($schedule->time_start),'G')  * 12 + 2 }} / span {{ $difference * 12 }}">
										<a href="#"
											class="absolute flex flex-col p-2 overflow-y-auto text-xs leading-5 bg-purple-200 rounded-lg group inset-1 hover:bg-purple-100">
											<p class="order-1 font-semibold text-purple-700">{{ $schedule->purpose }}
											</p>
											<p class="text-purple-500 group-hover:text-purple-700"><time datetime="2022-01-12T06:00">{{ date_format(date_create($schedule->time_start),'h:i a') }} - {{ date_format(date_create($schedule->time_end),'h:i a') }} </time></p>
										</a>
									</li>
									@break

									@case($dates[6]['full_date'])
									@php
										$date1=new DateTime($schedule->time_start);
										$date2=$date1->diff(new DateTime($schedule->time_end));
										$difference = ($date2->i/60)+$date2->h;
									@endphp
									<li class="relative flex mt-px sm:col-start-7"
										style="grid-row: {{  date_format(date_create($schedule->time_start),'G')  * 12 + 2 }} / span {{ $difference * 12 }}">
										<a href="#"
											class="absolute flex flex-col p-2 overflow-y-auto text-xs leading-5 rounded-lg bg-rose-200 group inset-1 hover:bg-rose-100">
											<p class="order-1 font-semibold text-rose-700">{{ $schedule->purpose }}
											</p>
											<p class="text-rose-500 group-hover:text-rose-700"><time datetime="2022-01-12T06:00">{{ date_format(date_create($schedule->time_start),'h:i a') }} - {{ date_format(date_create($schedule->time_end),'h:i a') }} </time></p>
										</a>
									</li>
									@break
								@endswitch
							@endforeach

							

						</ol>
					</div>
				</div>
			</div>
		</div>
	</div>
	@endif

</div>
