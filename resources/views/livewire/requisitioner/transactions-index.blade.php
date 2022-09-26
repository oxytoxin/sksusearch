<div>
    <div class="grid grid-cols-2 gap-4 text-center md:grid-cols-6">
        <a class="flex col-span-2 col-start-1 px-4 py-2 rounded-lg hover:bg-primary-800 hover:text-white bg-primary-600 text-primary-bg-alt active:ring-primary-400 active:ring-4 active:ring-offset-1 lg:row-start-1"
            href="{{ route('requisitioner.travel-orders.create') }}">
            <span class="flex my-auto text-sm break-none">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-auto mr-1 w-7 lg:w-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Create Travel Order
            </span>
        </a>
        <a class="flex col-span-2 col-start-3 px-4 py-2 rounded-lg hover:bg-primary-800 hover:text-white bg-primary-600 text-primary-bg-alt active:ring-primary-400 active:ring-4 active:ring-offset-1 lg:row-start-1"
            href="{{ route('requisitioner.itinerary.create') }}"><span class="flex my-auto text-sm break-none">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Create Itinerary
            </span></a>

        @foreach ($categories as $category)
            @if ($category->voucher_types->count() > 1)
                <div x-cloak class="col-span-6 text-left" x-data="{ openMe: false }">
                    <h4 x-on:click="openMe=!openMe" class="cursor-pointer hover:text-primary-600">{{ $category->name }}</h4>
                    <div x-show='openMe' x-transition:enter='transition ease-out duration-500' x-transition:enter-start='opacity-90 scale-0' x-transition:enter-end='opacity-100 scale-100' x-transition:leave='transition ease-in duration-300'
                        x-transition:leave-start='opacity-100 scale-100' x-transition:leave-end='opacity-20 scale-0' class="origin-top-left">
                        @foreach ($category->voucher_types as $type)
                            @if ($type->voucher_subtypes->count() == 1)
                                <a class="" href="{{ route('requisitioner.disbursement-vouchers.create', ['voucher_subtype' => $type->voucher_subtypes->first()->id]) }}">
                                    <h5 class="px-6 py-2 mt-3 rounded-lg hover:scale-105 hover:bg-primary-800 hover:text-primary-100 active:ring-primary-400 active:ring-2 active:ring-offset-2 active:ring-offset-white">
                                        {{ $type->name }}</h5>
                                </a>
                            @else
                                <div x-data="{ open: false }">
                                    <h5 class="px-6 mt-2 hover:cursor-pointer hover:text-primary-600" x-on:click="open=!open">{{ $type->name }}</h5>
                                    <div x-show='open' x-transition:enter="transition ease-out duration-500" x-transition:enter-start=" opacity-100 scale-0" x-transition:enter-end=" opacity-100 scale-100 "
                                        x-transition:leave="transition ease-in duration-300" x-transition:leave-start=" opacity-100 scale-100" x-transition:leave-end=" opacity-0 scale-0"
                                        class="grid gap-1 px-2 py-3 mx-6 ml-10 text-left origin-top-left rounded-tl-none bg-primary-300 rounded-3xl lg:gap-6">
                                        @foreach ($type->voucher_subtypes as $subtype)
                                            <a class="px-3 py-2 mt-1 text-left rounded-lg hover:scale-105 hover:bg-primary-800 hover:text-primary-100 active:ring-primary-400 active:ring-2 active:ring-offset-2 active:ring-offset-white"
                                                href="{{ route('requisitioner.disbursement-vouchers.create', ['voucher_subtype' => $subtype->id]) }}">
                                                <h6 class="">{{ $subtype->name }}</h6>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @else
                <a class="flex col-span-2 row-start-2 px-4 py-2 rounded-lg hover:bg-primary-800 hover:text-white bg-primary-600 text-primary-bg-alt active:ring-primary-400 active:ring-4 active:ring-offset-1 lg:col-span-1 lg:row-start-1"
                    href="#"><span class="mx-auto my-auto text-sm">{{ $category->name }}</span></a>
            @endif
        @endforeach
    </div>
</div>
