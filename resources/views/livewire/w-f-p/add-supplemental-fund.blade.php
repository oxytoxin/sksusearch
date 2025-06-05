<div class="space-y-2">
    <div class="flex justify-between items-center">
        <h2 class="font-light capitalize text-primary-600">Add Supplemental Fund</h2>

        <a href="{{ route('wfp.fund-allocation', $record->fundClusterWFP->id) }}"
            class="flex hover:bg-yellow-500 p-2 bg-yellow-600 rounded-md font-light capitalize text-white text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-5 h-5 mr-3">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M7.49 12 3.74 8.248m0 0 3.75-3.75m-3.75 3.75h16.5V19.5" />
            </svg>
            Back
        </a>
    </div>
    <div class="grid grid-cols-2 space-x-3">

        @if (in_array($record->fundClusterWFP->id, [1, 3, 9]))
            <div class="col-span-1 lg:col-start-1">
                <h2 class="sr-only">Summary</h2>
                <div class="rounded-lg bg-gray-50 shadow-sm ring-1 ring-gray-900/5">
                    <dl class="flex flex-wrap">
                        {{-- <div class="flex-auto pl-6 pt-4">
                      <dt class="text-sm font-semibold leading-6 text-gray-900">Fund - {{$record->fundClusterWFP->name}}</dt>
                      <dd class="mt-1 text-base font-semibold leading-6 text-gray-900"></dd>
                    </div> --}}
                        <div class="mt-3 flex w-full flex-none gap-x-4 px-6 pt-3">
                            <dt class="flex-none">
                                <span class="sr-only">Client</span>
                                <svg class="h-6 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"
                                    aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-5.5-2.5a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0zM10 12a5.99 5.99 0 00-4.793 2.39A6.483 6.483 0 0010 16.5a6.483 6.483 0 004.793-2.11A5.99 5.99 0 0010 12z"
                                        clip-rule="evenodd" />
                                </svg>
                            </dt>
                            <dd wire:ignore class="text-sm font-medium leading-6 text-gray-900">
                                {{ $record->office->head_employee?->full_name }} - Cost Center Head</dd>
                        </div>
                        <div class="mt-4 flex w-full flex-none gap-x-2 px-6">
                            <dt class="flex-none">
                                <span class="sr-only">Name</span>

                                <svg class="h-6 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                                </svg>
                            </dt>
                            <dd class="text-sm leading-6 text-gray-500">
                            <dd class="text-sm font-medium leading-6 text-gray-900">{{ $record->name }}</dd>
                            </dd>
                        </div>
                        <div class="mt-4 flex w-full flex-none gap-x-2 px-6">
                            <dt class="flex-none">
                                <span class="sr-only">Office</span>

                                <svg class="h-6 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                                </svg>
                            </dt>
                            <dd class="text-sm leading-6 text-gray-500">
                            <dd class="text-sm font-medium leading-6 text-gray-900">{{ $record->office->name }} - Office
                            </dd>
                            </dd>
                        </div>
                        <div class="mt-4 flex w-full flex-none gap-x-4 px-6">
                            <dt class="flex-none">
                                <span class="sr-only">Status</span>
                                <svg class="h-6 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" />
                                </svg>
                            </dt>
                            <dd class="text-sm font-medium leading-6 text-gray-900">{{ $record->mfo->name }} - MFO</dd>
                        </div>
                        <div class="mt-4 flex w-full flex-none gap-x-4 px-6">
                            <dt class="flex-none">
                                <span class="sr-only">Status</span>
                                <svg class="h-6 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                                </svg>
                            </dt>
                            <dd class="text-sm font-medium leading-6 text-gray-900">{{ $supplemental_quarter->name }} -
                                Supplemental</dd>
                        </div>
                    </dl>
                    <div class="mt-2 border-t border-gray-900/5 px-6 py-3">
                        <span class="text-sm font-semibold">Add funds to each category group</span>
                        <div class="px-4 sm:px-6 lg:px-8">
                            <div class="mt-4 flow-root">
                                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                        <div
                                            class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                                            <table class="min-w-full divide-y divide-gray-300">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th scope="col"
                                                            class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                            Category Group</th>
                                                        <th scope="col"
                                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                            Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-200 bg-white">
                                                    @forelse ($category_groups as $key => $item)
                                                        <tr>
                                                            <td
                                                                class="whitespace-nowrap py-4 pl-2 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                                                {{ $item->name }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">
                                                                <div>
                                                                    <div class="relative mt-2 rounded-md shadow-sm">
                                                                        <div
                                                                            class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                                            <span
                                                                                class="text-gray-500 sm:text-sm">₱</span>
                                                                        </div>
                                                                        <input type="number" name="price"
                                                                            id="price_{{ $item->id }}"
                                                                            wire:model.lazy="amounts.{{ $item->id }}"
                                                                            {{-- wire:change="updatedAmounts" --}}
                                                                            class="text-right block w-full rounded-md border-0 py-1.5 pl-7 pr-12 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                                                            placeholder="0.00"
                                                                            aria-describedby="price-currency">
                                                                        <div
                                                                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                                                            <span class="text-gray-500 sm:text-sm"
                                                                                id="price-currency">PHP</span>
                                                                        </div>
                                                                        @error('amounts.{{ $item->id }}')
                                                                            <span
                                                                                class="error">{{ $message }}</span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="2"
                                                                class="text-center py-4 text-sm font-medium text-gray-900">
                                                                No records found</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-1 lg:col-start-2">
                <h2 class="sr-only">Summary</h2>
                <div class="rounded-lg bg-gray-50 shadow-sm ring-1 ring-gray-900/5">
                    <dl class="flex flex-wrap">
                        {{-- <div class="flex-auto pl-6 pt-4">
                      <dt class="text-sm font-semibold leading-6 text-gray-900">Fund - {{$record->fundClusterWFP->name}}</dt>
                      <dd class="mt-1 text-base font-semibold leading-6 text-gray-900"></dd>
                    </div> --}}
                        <div class="mt-3 flex w-full flex-none gap-x-4 px-6 pt-3">
                            <dt class="flex-none">
                                <span class="sr-only">Client</span>
                                <svg class="h-6 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"
                                    aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-5.5-2.5a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0zM10 12a5.99 5.99 0 00-4.793 2.39A6.483 6.483 0 0010 16.5a6.483 6.483 0 004.793-2.11A5.99 5.99 0 0010 12z"
                                        clip-rule="evenodd" />
                                </svg>
                            </dt>
                            <dd wire:ignore class="text-sm font-medium leading-6 text-gray-900">
                                {{ $record->office->head_employee?->full_name }} - Cost Center Head</dd>
                        </div>
                        <div class="mt-4 flex w-full flex-none gap-x-2 px-6">
                            <dt class="flex-none">
                                <span class="sr-only">Name</span>

                                <svg class="h-6 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                                </svg>
                            </dt>
                            <dd class="text-sm leading-6 text-gray-500">
                            <dd class="text-sm font-medium leading-6 text-gray-900">{{ $record->name }}</dd>
                            </dd>
                        </div>
                        <div class="mt-4 flex w-full flex-none gap-x-2 px-6">
                            <dt class="flex-none">
                                <span class="sr-only">Office</span>

                                <svg class="h-6 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                                </svg>
                            </dt>
                            <dd class="text-sm leading-6 text-gray-500">
                            <dd class="text-sm font-medium leading-6 text-gray-900">{{ $record->office->name }} -
                                Office</dd>
                            </dd>
                        </div>
                        <div class="mt-4 flex w-full flex-none gap-x-4 px-6">
                            <dt class="flex-none">
                                <span class="sr-only">Status</span>
                                <svg class="h-6 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" />
                                </svg>
                            </dt>
                            <dd class="text-sm font-medium leading-6 text-gray-900">{{ $record->mfo->name }} - MFO
                            </dd>
                        </div>
                        <div class="mt-4 flex w-full flex-none gap-x-4 px-6">
                            <dt class="flex-none">
                                <span class="sr-only">Status</span>
                                <svg class="h-6 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                                </svg>
                            </dt>
                            <dd class="text-sm font-medium leading-6 text-gray-900">{{ $supplemental_quarter->name }}
                                - Supplemental</dd>
                        </div>
                    </dl>
                    <div class="mt-2 border-t border-gray-900/5 px-6 py-6">
                        <span class="text-sm font-semibold">Summary</span>
                        <div class="">
                            <div class="mt-2 flow-root sm:mx-0">
                                <table class="min-w-full">
                                    <colgroup>
                                        <col class="w-full sm:w-1/2">
                                        <col class="sm:w-1/6">
                                        <col class="sm:w-1/6">
                                        <col class="sm:w-1/6">
                                    </colgroup>
                                    <thead class="border-b border-gray-300 text-gray-900">
                                        <tr>
                                            <th scope="col"
                                                class="py-3.5 pl-4 pr-0 text-left text-sm font-semibold text-gray-900 sm:pl-0">
                                                Category Group</th>
                                            {{-- <th scope="col" class="hidden px-3 py-3.5 text-right text-sm font-semibold text-gray-900 sm:table-cell"></th> --}}
                                            <th scope="col"
                                                class="py-3.5 pl-4 text-left text-sm font-semibold text-gray-900 sm:pl-0">
                                                Balance</th>
                                            <th scope="col"
                                                class="py-3.5 pl-4 text-left text-sm font-semibold text-gray-900 sm:pl-0">
                                                Supplemental</th>
                                            <th scope="col"
                                                class="py-3.5 pl-4 text-left text-sm font-semibold text-gray-900 sm:pl-0">
                                                Sub Total</th>
                                            <th scope="col"
                                                class="hidden py-3.5 text-right text-sm font-semibold text-gray-900 sm:table-cell">
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($category_groups as $item)
                                            <tr class="border-b border-gray-200">
                                                <td class="max-w-0 py-3 pl-4 pr-3 text-sm sm:pl-0">
                                                    <div class="font-medium text-gray-900">{{ $item->name }}</div>
                                                </td>
                                                {{-- <td class="hidden px-3 py-3 text-right text-sm text-gray-500 sm:table-cell"></td> --}}
                                                <td
                                                    class="hidden px-3 py-3 text-left text-sm text-gray-500 sm:table-cell">
                                                    ₱ {{ number_format($this->calculateSubTotal($item->id), 2) }}</td>
                                                <td
                                                    class="hidden px-3 py-3 text-left text-sm text-gray-500 sm:table-cell">
                                                    ₱ {{ number_format($this->calculateSupplemental($item->id), 2) }}
                                                </td>
                                                <td
                                                    class="hidden px-3 py-3 text-left text-sm text-gray-500 sm:table-cell">
                                                    ₱
                                                    {{ number_format($this->calculateSupplementalTotal($item->id), 2) }}
                                                </td>
                                                <td class="hidden py-3 text-right text-sm text-gray-500 sm:table-cell">
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4"
                                                    class="text-center py-4 text-sm font-medium text-gray-900">No
                                                    records found</td>
                                            </tr>
                                        @endforelse
                                        <!-- More projects... -->
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th scope="row"
                                                class="hidden pl-4 pr-3 pt-4 text-left text-sm font-semibold text-gray-900 sm:table-cell sm:pl-0">
                                                Total</th>
                                            <th scope="row"
                                                class="pl-4 pr-3 pt-4 text-left text-sm font-semibold text-gray-900 sm:hidden">
                                                Total</th>
                                            <td
                                                class="pl-3 pr-4 pt-4 text-left text-sm font-semibold text-gray-900 sm:pr-0">
                                                ₱ {{ number_format($this->calculateTotal(), 2) }}</td>
                                            <td
                                                class="pl-3 pr-4 pt-4 text-left text-sm font-semibold text-gray-900 sm:pr-0">
                                                ₱ {{ number_format($this->calculateTotalSupplemental(), 2) }}</td>
                                            <td
                                                class="pl-3 pr-4 pt-4 text-left text-sm font-semibold text-gray-900 sm:pr-0">
                                                ₱ {{ number_format($this->calculateGrandTotal(), 2) }}</td>

                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                        </div>

                    </div>

                </div>
                <div class="flex justify-end mt-2 space-x-3">
                    <button wire:click="confirmSupplementalFund"
                        class="flex hover:bg-primary-500 p-2 bg-primary-600 rounded-md font-light capitalize text-white text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        Submit
                    </button>
                </div>
            </div>
        @elseif(
            $record->fundClusterWFP->id === 2 ||
                $record->fundClusterWFP->id === 4 ||
                $record->fundClusterWFP->id === 5 ||
                $record->fundClusterWFP->id === 6 ||
                $record->fundClusterWFP->id === 7)
            <div class="col-span-1 lg:col-start-1 bg-gray-50 rounded-lg shadow-sm ring-1 ring-gray-900/5">
                <div class="flex w-full flex-none gap-x-4 px-6 pt-3">
                    <dt class="flex-none">
                        <span class="sr-only">Client</span>
                        <svg class="h-6 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"
                            aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-5.5-2.5a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0zM10 12a5.99 5.99 0 00-4.793 2.39A6.483 6.483 0 0010 16.5a6.483 6.483 0 004.793-2.11A5.99 5.99 0 0010 12z"
                                clip-rule="evenodd" />
                        </svg>
                    </dt>
                    <dd wire:ignore class="text-sm font-medium leading-6 text-gray-900">
                        {{ $record->office->head_employee?->full_name }} - Cost Center Head</dd>
                </div>
                <div class="mt-4 flex w-full flex-none gap-x-2 px-6">
                    <dt class="flex-none">
                        <span class="sr-only">Name</span>

                        <svg class="h-6 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                        </svg>
                    </dt>
                    <dd class="text-sm leading-6 text-gray-500">
                    <dd class="text-sm font-medium leading-6 text-gray-900">{{ $record->name }}</dd>
                    </dd>
                </div>
                <div class="mt-4 flex w-full flex-none gap-x-2 px-6">
                    <dt class="flex-none">
                        <span class="sr-only">Office</span>

                        <svg class="h-6 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                        </svg>
                    </dt>
                    <dd class="text-sm leading-6 text-gray-500">
                    <dd class="text-sm font-medium leading-6 text-gray-900">{{ $record->office->name }} - Office</dd>
                    </dd>
                </div>
                <div class="mt-4 flex w-full flex-none gap-x-4 px-6">
                    <dt class="flex-none">
                        <span class="sr-only">Status</span>
                        <svg class="h-6 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" />
                        </svg>
                    </dt>
                    <dd class="text-sm font-medium leading-6 text-gray-900">{{ $record->mfo->name }} - MFO</dd>
                </div>
                <div class="mt-4 flex w-full flex-none gap-x-4 px-6">
                    <dt class="flex-none">
                        <span class="sr-only">Status</span>
                        <svg class="h-6 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                        </svg>
                    </dt>
                    <dd class="text-sm font-medium leading-6 text-gray-900">{{ $supplemental_quarter->name }} -
                        Supplemental</dd>
                </div>


                <div class="border-t border-gray-900/5 px-6 py-3">
                    <span class="text-sm font-semibold">Please add an amount</span>
                    <div class="px-4 sm:px-6 lg:px-8">
                        <div class="mt-4 flow-root">
                            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                                        <div>
                                            <div class="relative rounded-md shadow-sm">
                                                <div
                                                    class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                    <span class="text-gray-500 sm:text-sm">₱</span>
                                                </div>
                                                <input wire:model.debounce.200ms="supplemental_allocation"
                                                    type="number" name="price" id="price"
                                                    class="block w-full rounded-md border-0 py-1.5 pl-7 pr-12 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                                    placeholder="0.00" aria-describedby="price-currency">
                                                <div
                                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                                    <span class="text-gray-500 sm:text-sm"
                                                        id="price-currency">PHP</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @error('supplemental_allocation')
                                        <span class="text-sm text-red-600">{{ $message }}</span>
                                    @enderror
                                    <div class="py-3">
                                        <textarea wire:model.debounce.200ms="supplemental_allocation_description" id="message" rows="4"
                                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Description"></textarea>
                                        @error('supplemental_allocation_description')
                                            <span class="text-sm text-red-600">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-2 border-t border-gray-900/5 px-6 py-6">
                    <span class="text-sm font-semibold">Summary</span>
                    <div class="px-4 sm:px-6 lg:px-8">
                        <div class="-mx-4 mt-2 flow-root sm:mx-0">
                            <table class="min-w-full">
                                <colgroup>
                                    <col class="w-full sm:w-1/2">
                                    <col class="sm:w-1/6">
                                    <col class="sm:w-1/6">
                                    <col class="sm:w-1/6">
                                </colgroup>
                                <thead class="border-b border-gray-300 text-gray-900">
                                    <tr>
                                        <th scope="col"
                                            class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">
                                            Description</th>
                                        <th scope="col"
                                            class="hidden px-3 py-3.5 text-right text-sm font-semibold text-gray-900 sm:table-cell">
                                        </th>
                                        <th scope="col"
                                            class="hidden px-3 py-3.5 text-right text-sm font-semibold text-gray-900 sm:table-cell">
                                        </th>
                                        <th scope="col"
                                            class="py-3.5 pl-20 pr-4 text-right text-sm font-semibold text-gray-900 sm:pr-0">
                                            Balance</th>
                                        <th scope="col"
                                            class="py-3.5 pl-20 pr-4 text-right text-sm font-semibold text-gray-900 sm:pr-0">
                                            Supplemental</th>
                                        <th scope="col"
                                            class="py-3.5 pl-24 pr-4 text-right text-sm font-semibold text-gray-900 sm:pr-0">
                                            Sub Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-b border-gray-200">
                                        <td class="max-w-0 py-3 pl-4 pr-3 text-sm sm:pl-0">
                                            <div class="font-medium text-gray-900"><span class="font-bold">(WFP) -
                                                </span>{{ $fund_description }}</div>
                                        </td>
                                        <td class="hidden px-3 py-3 text-right text-sm text-gray-500 sm:table-cell">
                                        </td>
                                        <td class="hidden px-3 py-3 text-right text-sm text-gray-500 sm:table-cell">
                                        </td>
                                        <td class="py-3 pl-3 pr-4 text-right text-sm text-gray-500 sm:pr-0">₱
                                            {{ number_format($balance_164, 2) }}</td>
                                        <td class="py-3 pl-3 pr-4 text-right text-sm text-gray-500 sm:pr-0"></td>
                                        <td class="py-3 pl-3 pr-4 text-right text-sm text-gray-500 sm:pr-0"></td>
                                    </tr>
                                    <tr class="border-b border-gray-200">
                                        <td class="max-w-0 py-3 pl-4 pr-3 text-sm sm:pl-0">
                                            <div class="font-medium text-gray-900"><span class="font-bold">(Q1) -
                                                </span>{{ $supplemental_allocation_description }}</div>
                                        </td>
                                        <td class="hidden px-3 py-3 text-right text-sm text-gray-500 sm:table-cell">
                                        </td>
                                        <td class="hidden px-3 py-3 text-right text-sm text-gray-500 sm:table-cell">
                                        </td>
                                        <td class="py-3 pl-3 pr-4 text-right text-sm text-gray-500 sm:pr-0">₱
                                            {{ number_format($balance_164, 2) }}</td>
                                        <td class="py-3 pl-3 pr-4 text-right text-sm text-gray-500 sm:pr-0">₱
                                            {{ $supplemental_allocation != null ? number_format($supplemental_allocation, 2) : number_format(0, 2) }}
                                        </td>
                                        <td class="py-3 pl-3 pr-4 text-right text-sm text-gray-500 sm:pr-0">₱
                                            {{ number_format($sub_total_164, 2) }}</td>
                                    </tr>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th scope="row" colspan="3"
                                            class="hidden pl-4 pr-3 pt-4 text-right text-sm font-semibold text-gray-900 sm:table-cell sm:pl-0">
                                            Total</th>
                                        <th scope="row"
                                            class="pl-4 pr-3 pt-4 text-left text-sm font-semibold text-gray-900 sm:hidden">
                                            Total</th>
                                        <td
                                            class="pl-3 pr-4 pt-4 text-right text-sm font-semibold text-gray-900 sm:pr-0">
                                            ₱ {{ number_format($balance_164, 2) }}</td>
                                        <td
                                            class="pl-3 pr-4 pt-4 text-right text-sm font-semibold text-gray-900 sm:pr-0">
                                            ₱
                                            {{ $supplemental_allocation != null ? number_format($supplemental_allocation, 2) : number_format(0, 2) }}
                                        </td>
                                        <td
                                            class="pl-3 pr-4 pt-4 text-right text-sm font-semibold text-gray-900 sm:pr-0">
                                            ₱ {{ number_format($sub_total_164, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>
                </div>
                {{-- <div class="mt-2 border-t border-gray-900/5 px-6 py-6">
                <span class="text-sm font-semibold">Summary</span>
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="-mx-4 mt-2 flow-root sm:mx-0">
                      <table class="min-w-full">
                        <colgroup>
                          <col class="w-full sm:w-1/2">
                          <col class="sm:w-1/6">
                          <col class="sm:w-1/6">
                          <col class="sm:w-1/6">
                        </colgroup>
                        <thead class="border-b border-gray-300 text-gray-900">
                          <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Description</th>
                            <th scope="col" class="hidden px-3 py-3.5 text-right text-sm font-semibold text-gray-900 sm:table-cell"></th>
                            <th scope="col" class="hidden px-3 py-3.5 text-right text-sm font-semibold text-gray-900 sm:table-cell"></th>
                            <th scope="col" class="py-3.5 pl-3 pr-4 text-right text-sm font-semibold text-gray-900 sm:pr-0">Amount</th>
                          </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-gray-200">
                                <td class="max-w-0 py-3 pl-4 pr-3 text-sm sm:pl-0">
                                  <div class="font-medium text-gray-900">{{$fund_description}}</div>
                                </td>
                                <td class="hidden px-3 py-3 text-right text-sm text-gray-500 sm:table-cell"></td>
                                <td class="hidden px-3 py-3 text-right text-sm text-gray-500 sm:table-cell"></td>
                                <td class="py-3 pl-3 pr-4 text-right text-sm text-gray-500 sm:pr-0">₱ {{ number_format($fundInitialAmount, 2) }}</td>
                              </tr>

                        </tbody>
                        <tfoot>
                          <tr>
                            <th scope="row" colspan="3" class="hidden pl-4 pr-3 pt-4 text-right text-sm font-semibold text-gray-900 sm:table-cell sm:pl-0">Total</th>
                            <th scope="row" class="pl-4 pr-3 pt-4 text-left text-sm font-semibold text-gray-900 sm:hidden">Total</th>
                            <td class="pl-3 pr-4 pt-4 text-right text-sm font-semibold text-gray-900 sm:pr-0">₱ {{ number_format($fundInitialAmount, 2) }}</td>
                          </tr>
                        </tfoot>
                      </table>
                    </div>

                  </div>
            </div> --}}
            </div>


            <div>

            </div>
            <div class="flex justify-end mt-2 space-x-3">
                <button wire:click="confirmSupplementalFund164"
                    class="flex hover:bg-primary-500 p-2 bg-primary-600 rounded-md font-light capitalize text-white text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Submit
                </button>
            </div>
        @endif
    </div>
</div>
