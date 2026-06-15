<div>
    <div class="flex-col space-y-5 text-md" x-data>
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
                        <p class="whitespace-pre-line p-4">{{ $travel_order->purpose }}</p>
                        <p>Registration Fee: <span>{{ number_format($travel_order->registration_amount, 2) > 0 ? number_format($travel_order->registration_amount, 2) : 'N/A' }}</span>
                        </p>
                        <p>Total Amount: <span>{{ $totalAmount }}</span>
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
            <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                <h3 class="text-lg font-medium leading-6 text-primary-700">Itinerary of Travel</h3>
                <div class="flex gap-2">
                    <button class="rounded-lg bg-primary-500 px-4 py-2 text-sm font-semibold tracking-wider text-white hover:bg-primary-200 hover:text-primary-500 active:bg-primary-700 active:text-white"
                            type="button"
                            @click="printOutData($refs.itineraryPreview.outerHTML, 'Itinerary of Travel')">
                        Print
                    </button>
                    <a class="rounded-lg border border-primary-500 px-4 py-2 text-sm font-semibold tracking-wider text-primary-600 hover:bg-primary-50"
                       id="print"
                       href="{{ $print_route }}">
                        Open Print View
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto rounded border border-gray-200 p-3" x-ref="itineraryPreview">
                @include('livewire.requisitioner.itinerary._official-form')
            </div>
        </div>
    </div>
</div>
