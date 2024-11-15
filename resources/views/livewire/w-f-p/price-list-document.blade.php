<div class="space-y-2">
    <div class="flex justify-between items-center">
        <h2 class="font-light capitalize text-primary-600">Pricelist Document</h2>
           <a href="{{ route('requisitioner.dashboard') }}"
            class="flex space-x-4 hover:bg-yellow-500 p-2 bg-yellow-600 rounded-md font-light capitalize text-white text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
              </svg>
              <span class="mt-1">Return</span>
            </a>
    </div>
    @if ($record)
    <div>
        <h1 class="text-xl mt-8">Description: <span class="font-semibold">{{$record->description}}</span></h1>
        <h1 class="text-xl mt-2">Revised Date: <span class="font-semibold">{{Carbon\Carbon::parse($record->revised_date)->format('F d, Y')}}</span></h1>
        <h1 class="text-xl mt-2">Effective Date: <span class="font-semibold">{{Carbon\Carbon::parse($record->effective_date)->format('F d, Y')}}</span></h1>
    </div>
    <div class="">
        <a class="mt-4 flex w-fit hover:text-primary-600 hover:text-lg hover:font-extrabold" href="{{ asset('storage/'.$record->path) }}" target="_blank"  x-data="{}" x-tooltip.raw="Click to download file!">
            <span class="underline flex mr-3 text-md text-green-800">  Download Updated Pricelist </span>
            <svg class="flex w-5 h-5 my-auto"xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="flex w-5 h-5 my-auto">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 7.5h-.75A2.25 2.25 0 0 0 4.5 9.75v7.5a2.25 2.25 0 0 0 2.25 2.25h7.5a2.25 2.25 0 0 0 2.25-2.25v-7.5a2.25 2.25 0 0 0-2.25-2.25h-.75m-6 3.75 3 3m0 0 3-3m-3 3V1.5m6 9h.75a2.25 2.25 0 0 1 2.25 2.25v7.5a2.25 2.25 0 0 1-2.25 2.25h-7.5a2.25 2.25 0 0 1-2.25-2.25v-.75" />
              </svg>
          </a>
    </div>
    @else
    <div>
        <h1 class="text-2xl mt-8 italic text-gray-500">No Document Available</h1>
    </div>
    @endif


</div>
