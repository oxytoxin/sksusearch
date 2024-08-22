<div>
    <div class="flex">
        <h2 class="font-light capitalize text-primary-600">Select WFP</h2>
        {{-- <a href="{{ route('requisitioner.motorpool.create') }}"
            class="hover:bg-primary-500 p-2 bg-primary-600 rounded-md font-light capitalize text-white text-sm">New
            Request</a> --}}
    </div>
    <div class="flex mt-20 min-h-screen">
        <div>
            @if ($types->count() <= 0)
              <div class="text-center">
                <h1 class="text-2xl font-bold text-gray-700">No WFP Types Found</h1>
              </div>
            @else

            <div class="flex space-x-5">
                <div class="grid grid-cols-4 space-x-4">
                    @foreach ($types as $item)
                    <a href="{{ route('wfp.create-wfp', $item->costCenters->first()->id) }}" class="col-span-1 my-3 block max-w-sm p-6 bg-green-800 border border-green-700 rounded-lg shadow-lg hover:bg-green-700 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-50"> Fund {{$item->name}}</h5>
                        <p class="font-normal text-gray-50 dark:text-gray-400">{{$wfp->description}}</p>
                        <p class="font-normal text-gray-50 dark:text-gray-400">Amount: ₱ {{number_format($item->fundAllocations->sum('initial_amount'), 2)}}</p>
                    </a>
                    </a>

                    @endforeach
                </div>

              </div>

            @endif
          </div>
    </div>

  </div>
