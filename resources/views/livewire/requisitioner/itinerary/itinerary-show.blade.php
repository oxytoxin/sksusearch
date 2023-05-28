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
                <div class="mt-4 ml-4" x-data="{ open: true }">
                    <div class="flex justify-between w-full">
                        <h3 class="flex justify-between w-full text-lg font-medium leading-6 text-primary-700 hover:text-primary-400 hover:cursor-pointer" x-on:click="open= !open">Travel Order Details
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" :class="open ? 'rotate-180' : 'rotate-360'" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 5.25l-7.5 7.5-7.5-7.5m15 6l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </h3>
                    </div>
                    <div class="mt-4 space-y-1 origin-top-left text-primary-500" x-show='open' x-transition:enter='ease-out transition duration-400' x-transition:enter-start='opacity-0 scale-100' x-transition:enter-end='opacity-100 scale-100' x-transition:leave='transition ease-in duration-400' x-transition:leave-start='opacity-100 scale-100' x-transition:leave-end='opacity-0 scale-0'>
                        <p>Tracking Code: {{ $travel_order->tracking_code }}</p>
                        <p>Travel Order Type: {{ $travel_order->travel_order_type->name }}
                        </p>
                        <p>Date Range: {{ $travel_order->date_from->format('F d Y') }} to
                            {{ $travel_order->date_to->format('F d Y') }}</p>
                        @if ($travel_order->travel_order_type_id == 1)
                            <p>Destination: {{ $travel_order->destination }}</p>
                        @endif
                        <p>Purpose:</p>
                        <p class="whitespace-pre p-4">{{ $travel_order->purpose }}</p>
                        <p>Registration Fee: <span>{{ number_format($travel_order->registration_amount, 2) > 0 ? number_format($travel_order->registration_amount, 2) : 'N/A' }}</span>
                        </p>
                        <p>Total Amount: <span>{{ number_format($total_amount, 2) }}</span>
                        </p>
                        <p>Status: {{ $itinerary->approved_at ? 'Approved' : 'Pending' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-4 py-5 bg-white border-b rounded-md border-primary-200 sm:px-6 md:rounded-lg">
            <form class="flex flex-col gap-4" wire:submit.prevent='save'>

                <div class="flex-wrap items-center justify-between w-full -mt-4 -ml-4 sm:flex-nowrap">

                    <p class="mt-1 mb-2 text-primary-500">Purpose:
                    </p>
                    <textarea class="block w-full px-3 py-3 m-0 text-base font-normal text-gray-700 transition ease-in-out bg-white border border-gray-300 border-solid rounded form-control bg-clip-padding focus:text-gray-700 focus:bg-white focus:outline-none" @if (!$is_requisitioner) disabled @endif rows="3" placeholder="{{ $travel_order->purpose }}" wire:model="purpose"></textarea>
                </div>
                <div class="flex justify-end w-full">
                    @if ($is_requisitioner)
                        <x-filament-support::button class="mr-4" type="submit" wire:target='save'>Save
                        </x-filament-support::button>
                    @endif
                </div>
            </form>
        </div>
        <div class="px-4 py-5 bg-white border-b rounded-md border-primary-200 sm:px-6 md:rounded-lg">
            <div class="flex-wrap items-center justify-between w-full -mt-4 -ml-4 sm:flex-nowrap">
                <div class="mt-4 ml-4">
                    <div class="flex justify-between w-full">
                        <h3 class="flex justify-between w-full text-lg font-medium leading-6 text-primary-700 hover:text-primary-400 hover:cursor-pointer" x-on:click="open= !open">Itinerary</h3>
                        <a class="max-w-sm px-4 py-2 text-sm font-semibold tracking-wider text-white rounded-lg w-sm bg-primary-500 hover:bg-primary-200 hover:text-primary-500 active:bg-primary-700 active:text-white" id="print" href="{{ $print_route }}">
                            Print
                        </a>
                    </div>
                    @foreach ($coverage as $covered)
                        <div class="flex-col w-full m-2 mb-1 ml-0 rounded-md bg-primary-100" x-data="{ open: true }">
                            {{-- header --}}
                            <div class="block w-full p-2 border-b-0 bg-primary-200 rounded-t-md" :class="open ? 'shadow-md shadow-slate-400' : 'rounded-md'">
                                <h3 class="flex justify-between w-full font-bold text-primary-900" x-on:click="open = !open">
                                    {{ date_format(date_create($covered['date']), 'F d, Y') }}
                                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" :class="open ? 'rotate-180' : 'rotate-360'" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 5.25l-7.5 7.5-7.5-7.5m15 6l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </h3>
                            </div>
                            <div>
                                <div class="origin-top-left" x-show='open' x-transition:enter='ease-out transition duration-400' x-transition:enter-start='scale-y-0' x-transition:enter-end='scale-y-100' x-transition:leave='transition ease-in duration-400' x-transition:leave-start='opacity-100 scale-100' x-transition:leave-end='opacity-0 scale-y-0'>
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
                                                        <p class="font-semibold text-primary-900">Travelling to: <span class="font-normal">{{ $entry->place }}</span></p>
                                                        <p class="font-semibold text-primary-900">Mode of
                                                            transportation: <span class="font-normal">{{ $entry->mot->name }}</span>
                                                        </p>
                                                        <p class="font-semibold text-primary-900">Departure time: <span class="font-normal">{{ $entry->departure_time->format('g:i a') }}</span>
                                                        </p>
                                                        <p class="font-semibold text-primary-900">Arrival time: <span class="font-normal">{{ $entry->arrival_time->format('g:i a') }}</span>
                                                        </p>
                                                        <div class="flex justify-between w-full font-semibold text-primary-900">
                                                            Transporation Expenses: <span class="font-normal text-right">{{ number_format($entry->transportation_expenses, 2) }}</span>
                                                        </div>
                                                        <div class="flex justify-between w-full font-semibold text-primary-900">
                                                            Other Expenses: <span class="font-normal text-right">{{ number_format($entry->other_expenses, 2) }}</span>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                        <div class="flex justify-between w-full mt-2 font-bold border-t-8 border-double text-primary-500 border-primary-900">
                                            Total Expenses: <span class="text-right">{{ number_format($covered['total_expenses'], 2) }}</span>
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
