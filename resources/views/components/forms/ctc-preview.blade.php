@if (isset($travel_order) && isset($ctc))
    <div x-data>
        <div class="my-8 flex justify-end">
            <x-filament::button icon="heroicon-o-printer" @click="printOutData($refs.print.outerHTML, 'Certification of Travel Completed')">Print</x-filament::button>
        </div>
        <div class="bg-white p-2 text-[10pt]" x-ref="print">
            <div class="flex justify-between w-full p-6 border-b-4 border-black print:flex">
                <div class="flex w-full ml-3 text-left" id="header">
                    <div class="inline my-auto"><img class="object-scale-down w-20 h-full" src="{{ asset('images/sksulogo.png') }}" alt="sksu logo">
                    </div>
                    <div class="my-auto ml-3">
                        <div class="block">
                            <span class="text-sm font-semibold tracking-wide text-left text-black">Republic of the Philippines</span>
                        </div>
                        <div class="block">
                            <span class="text-sm font-semibold tracking-wide text-left uppercase text-primary-600">sultan kudarat state university</span>
                        </div>
                        <div class="block">
                            <span class="text-sm font-semibold tracking-wide text-black">ACCESS, EJC Montilla, 9800 City of Tacurong</span>
                        </div>
                        <div class="block">
                            <span class="text-sm font-semibold tracking-wide text-black">Province of Sultan Kudarat</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-col items-center mt-8">
                <h1 class="text-xl font-semibold">CERTIFICATION OF TRAVEL COMPLETED</h1>
                <div class="flex">
                    <h2>Date:</h2>
                    <h2 class="border-b-2 border-black px-10">{{ $ctc->created_at->format('F d, Y') }}</h2>
                </div>
            </div>
            <div class="px-16">
                <div class="mt-8">
                    <p>I HEREBY CERTIFY THAT I have completed the travel as authorized in the Travel Order/Itinerary of Travel No. {{ $travel_order->tracking_code }} dated {{ $travel_order->date_from->format('m/d/Y') }} under conditions indicated below:</p>
                </div>
                <div class="px-8 mt-8">
                    <ul>
                        <label class="flex items-start gap-4">
                            <input class="text-black" type="checkbox" @checked($condition == 1) readonly disabled>
                            <span>Strictly in accordance with the approved itinerary.</span>
                        </label>
                        <label class="flex items-start gap-4">
                            <input class="text-black" type="checkbox" @checked($condition == 2) readonly disabled>
                            <span>Cut short as explained below.
                                @if (isset($refund_amount))
                                    Excess payment in the amount of P{{ number_format($refund_amount ?? 0, 2) }} was refunded under O. R. No. {{ $or_number }} dated <span class="underline">{{ $or_date }}</span>
                                @endif
                            </span>
                        </label>
                        <label class="flex items-start gap-4">
                            <input class="text-black" type="checkbox" @checked($condition == 3) readonly disabled>
                            <span>Extended as explained below, additional itinerary was submitted.</span>
                        </label>
                        <label class="flex items-start gap-4">
                            <input class="text-black" type="checkbox" @checked($condition == 4) readonly disabled>
                            <span>Other deviation as explained below.</span>
                        </label>
                    </ul>
                </div>
                <div class="mt-8">
                    <p>Explanation or justifications:</p>
                    <p class="mt-4 px-4 indent-8 text-justify">{{ $explanation }}</p>
                </div>
                <div class="mt-2">
                    <p>Evidence of travel attached hereto:</p>
                    <ol class="list-decimal list-inside">
                        <li>Certificate of Appearance and Completion</li>
                        <li>Travel Order/Invitation</li>
                        <li>Actual Itinerary of Travel</li>
                    </ol>
                </div>
                <div class="my-4 flex flex-col items-end">
                    <div class="mr-16 w-1/3">
                        <p>Respectfully submitted:</p>
                        <div class="flex flex-col items-center px-8 mt-12">
                            <p class="whitespace-nowrap">{{ $employee }}</p>
                            <p>Name of Employee</p>
                        </div>
                    </div>
                </div>
                <div>
                    <p class="indent-8 text-justify">On evidence and information of which I have the knowledge, the travel was actually undertaken.</p>
                </div>
                <div class="my-4 flex flex-col items-end">
                    <div class="mr-16 w-1/3">
                        <p>Approved:</p>
                        <div class="flex flex-col items-center px-8 mt-12">
                            <p class="whitespace-nowrap">{{ $supervisor }}</p>
                            <p>Supervisor</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
