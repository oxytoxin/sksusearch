<div class="space-y-2">
    <div class="flex justify-between items-center">
        <h2 class="font-light capitalize text-primary-600">Create Work & Financial Plan</h2>
        <div class="flex space-x-3">
            <a href="{{ route('wfp.request-supply') }}"
                class="flex hover:bg-green-500 p-2 bg-green-600 rounded-md font-light capitalize text-white text-sm">
                Request Item
            </a>
            <button wire:click="$set('suppliesDetailModal',true)" type="button"
                class="flex hover:bg-yellow-500 p-2 bg-yellow-600 rounded-md font-light capitalize text-white text-sm">
                Preview WFP
            </button>
            <a href="{{ route('wfp.select-wfp') }}"
                class="flex hover:bg-gray-500 p-2 bg-gray-600 rounded-md font-light capitalize text-white text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5 mr-3">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M7.49 12 3.74 8.248m0 0 3.75-3.75m-3.75 3.75h16.5V19.5" />
                </svg>
                Back
            </a>
        </div>

    </div>
    {{-- body --}}
    <div>
        <div class="grid grid-cols-3 space-x-3 px-8">
            <div class="col-span-1">
                <div class="flex-col text-sm w-full font-medium divide-y-2 divide-gray-800">
                    <div class="py-2">
                        <span class="text-left">WFP :</span>
                        <span class="text-center">{{ $wfp_type?->description }}</span>
                    </div>
                    <div class="py-2">
                        <span class="text-left">Fund :</span>
                        <span class="text-center">{{ $wfp_fund?->name }}</span>
                    </div>
                    <div></div>
                </div>
            </div>
            <div class="col-span-1">
                <div>
                    <div class="flex-col text-sm w-full font-medium divide-y-2 divide-gray-800">
                        <div class="py-2">
                            <span class="text-left">Cost Center :</span>
                            <span class="text-center">{{ $costCenter->name }}</span>
                        </div>
                        <div class="py-2">
                            <span class="text-left">Cost Center Head :</span>
                            <span
                                class="text-center">{{ $costCenter->office->head_employee?->full_name . ' - ' . $costCenter->office->name }}</span>
                        </div>

                        <div></div>
                    </div>
                </div>
            </div>
            <div class="col-span-1">
                <div>
                    <div class="flex-col text-sm w-full font-medium divide-y-2 divide-gray-800">
                        <div class="py-2">
                            <span class="text-left">MFO :</span>
                            <span class="text-center">{{ $costCenter->mfo->name }}</span>
                        </div>
                        <div class="py-2">
                            <span class="text-left">MFO Fee :</span>
                            <span class="text-center">{{ $costCenter->mfoFee->name ?? 'N/A' }}</span>
                        </div>


                        <div></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="mt-8 flow-root">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="grid grid-cols-3 space-x-24 min-w-full py-1 align-middle sm:px-6 lg:px-8">
                        <div class="col-span-1">
                            @if (in_array($wfp_fund->id, [1, 3, 9]))
                                <div x-data="{ open: true }" x-cloak>
                                    <table class="min-w-full">
                                        <thead class="bg-green-800">
                                            <tr>
                                                <th scope="col"
                                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-50 sm:pl-3">
                                                    Title Group</th>
                                                <th scope="col"
                                                    class="px-3 py-2 text-left text-sm font-semibold text-gray-50">
                                                    Allocated Fund</th>
                                                <th scope="col"
                                                    class="px-3 py-2 text-left text-sm font-semibold text-gray-50">
                                                    Program</th>
                                                <th scope="col"
                                                    class="px-3 py-2 text-left text-sm font-semibold text-gray-50">
                                                    Balance</th>
                                                {{-- <th scope="col" class="relative py-2 pl-3 pr-4 sm:pr-3">
                                            <span class="sr-only">Edit</span>
                                          </th> --}}
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white">
                                            <tr class="border-t border-gray-300">
                                                <td colspan="4"
                                                    class="text-left italic whitespace-nowrap px-3 py-2 text-sm text-gray-500">
                                                    <button @click="open = !open"
                                                        class="text-sm font-semibold text-gray-500">
                                                        <span x-show="open">
                                                            <svg class="w-4 h-4 text-green-600"
                                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke-width="1.5"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="m4.5 5.25 7.5 7.5 7.5-7.5m-15 6 7.5 7.5 7.5-7.5" />
                                                            </svg>
                                                        </span>
                                                        <span x-show="!open">
                                                            <svg class="w-4 h-4 text-green-600"
                                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke-width="1.5"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="m4.5 18.75 7.5-7.5 7.5 7.5" />
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="m4.5 12.75 7.5-7.5 7.5 7.5" />
                                                            </svg>
                                                        </span>
                                                    </button>
                                                </td>
                                            </tr>
                                            @forelse ($current_balance as $item)
                                                @if ($item)
                                                    <tr class="border-t border-gray-300" x-show="!open">
                                                        <td
                                                            class="whitespace-nowrap py-3 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-3">
                                                            {{ $item['category_group'] }}
                                                        </td>
                                                        <td
                                                            class="whitespace-nowrap py-3 pl-4 pr-3 text-right text-sm font-medium text-gray-900 sm:pl-3">
                                                            ₱
                                                            {{ number_format($item['initial_amount'], 2) }}
                                                        </td>
                                                        <td
                                                            class="whitespace-nowrap py-3 pl-4 pr-3 text-right text-sm font-medium {{ $item['initial_amount'] >= $item['current_total'] ? 'text-gray-900' : 'text-red-600' }} sm:pl-3">
                                                            ₱ {{ number_format($item['current_total'], 2) }}</td>
                                                        <td
                                                            class="whitespace-nowrap py-3 pl-4 pr-3 text-right text-sm font-medium {{ $item['initial_amount'] >= $item['current_total'] ? 'text-gray-900' : 'text-red-600' }} sm:pl-3">
                                                            ₱
                                                            {{ number_format($item['initial_amount'] - $item['current_total'], 2) }}
                                                        </td>
                                                        {{-- <td class="relative whitespace-nowrap py-3 pl-3 pr-4 text-right text-sm font-medium sm:pr-3">
                                            <a href="#" class="text-green-800 hover:text-green-700">View<span class="sr-only">, Lindsay Walton</span></a>
                                          </td> --}}
                                                    </tr>
                                                @endif
                                            @empty
                                                <tr class="border-t border-gray-300">
                                                    <td
                                                        class="text-center italic whitespace-nowrap px-3 py-2 text-sm text-gray-500">
                                                        No Record
                                                    </td>
                                                    <td
                                                        class="text-center italic whitespace-nowrap px-3 py-2 text-sm text-gray-500">
                                                        No Record</td>
                                                    <td
                                                        class="text-center italic whitespace-nowrap px-3 py-2 text-sm text-gray-500">
                                                        No Record</td>
                                                    <td
                                                        class="text-center italic whitespace-nowrap px-3 py-2 text-sm text-gray-500">
                                                        No Record</td>
                                                </tr>
                                            @endforelse
                                            @php
                                                $sumAllocated = 0;
                                                $sumTotal = 0;
                                                $sumBalance = 0;

                                                $sumAllocated = array_sum(
                                                    array_column($current_balance, 'initial_amount'),
                                                );
                                                $sumTotal = array_sum(array_column($current_balance, 'current_total'));
                                                $sumBalance = $sumAllocated - $sumTotal;
                                                // $sumBalance = array_sum(array_column($current_balance, 'balance'));
                                            @endphp
                                            <tr class="border-t border-gray-300">
                                                <td
                                                    class="whitespace-nowrap py-3 pl-4 pr-3 text-sm font-semibold text-gray-900 sm:pl-3">
                                                    Total</td>
                                                <td
                                                    class="whitespace-nowrap py-3 pl-4 pr-3 text-sm text-right font-semibold text-gray-900 sm:pl-3">
                                                    ₱ {{ number_format($sumAllocated, 2) }}</td>
                                                <td
                                                    class="whitespace-nowrap py-3 pl-4 pr-3 text-sm text-right font-semibold text-gray-900 sm:pl-3">
                                                    ₱ {{ number_format($sumTotal, 2) }}</td>
                                                <td
                                                    class="whitespace-nowrap py-3 pl-4 pr-3 text-sm text-right font-semibold text-gray-900 sm:pl-3">
                                                    ₱ {{ number_format($sumBalance, 2) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @elseif($wfp_fund->id === 2 || $wfp_fund->id === 4 || $wfp_fund->id === 5 || $wfp_fund->id === 6 || $wfp_fund->id === 7)
                                <div x-data="{ opens: true }" x-cloak>
                                    <table class="min-w-full">
                                        <thead class="bg-green-800">
                                            <tr>
                                                <th scope="col"
                                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-50 sm:pl-3">
                                                    Title Group</th>
                                                <th scope="col"
                                                    class="px-3 py-2 text-left text-sm font-semibold text-gray-50">
                                                </th>
                                                <th scope="col"
                                                    class="px-3 py-2 text-left text-sm font-semibold text-gray-50">
                                                    Program</th>
                                                <th scope="col"
                                                    class="px-3 py-2 text-left text-sm font-semibold text-gray-50">
                                                </th>
                                                {{-- <th scope="col" class="px-3 py-2 text-left text-sm font-semibold text-gray-50">Total</th> --}}
                                                {{-- <th scope="col" class="relative py-2 pl-3 pr-4 sm:pr-3">
                                            <span class="sr-only">Edit</span>
                                          </th> --}}
                                            </tr>
                                        <tbody class="bg-white">
                                            <tr class="border-t border-gray-300">
                                                <td colspan="4"
                                                    class="text-left italic whitespace-nowrap px-3 py-2 text-sm text-gray-500">
                                                    <button @click="opens = !opens"
                                                        class="text-sm font-semibold text-gray-500">
                                                        <span x-show="!opens">

                                                            <svg class="w-4 h-4 text-green-600"
                                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke-width="1.5"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="m4.5 5.25 7.5 7.5 7.5-7.5m-15 6 7.5 7.5 7.5-7.5" />
                                                            </svg>
                                                        </span>
                                                        <span x-show="opens">
                                                            <svg class="w-4 h-4 text-green-600"
                                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke-width="1.5"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="m4.5 18.75 7.5-7.5 7.5 7.5" />
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="m4.5 12.75 7.5-7.5 7.5 7.5" />
                                                            </svg>
                                                        </span>
                                                    </button>

                                                </td>
                                            </tr>
                                            @forelse ($current_balance as $key => $item)
                                                <tr class="border-t border-gray-300" x-show="opens">
                                                    <td
                                                        class="whitespace-nowrap py-3 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-3">
                                                        {{ $current_balance[$key]['category_group'] }}</td>
                                                    <td
                                                        class="whitespace-nowrap py-3 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-3">
                                                    </td>
                                                    <td
                                                        class="whitespace-nowrap py-3 pl-4 pr-3 text-sm text-right font-medium text-gray-900 sm:pl-3">
                                                        ₱
                                                        {{ number_format($current_balance[$key]['current_total'], 2) }}
                                                    </td>
                                                    <td
                                                        class="whitespace-nowrap py-3 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-3">
                                                    </td>
                                                    {{-- <td class="whitespace-nowrap py-3 pl-4 pr-3 text-sm font-medium {{$item['initial_amount'] >= $item['current_total'] ? 'text-gray-900' : 'text-red-600'}} sm:pl-3">₱ {{number_format($item['current_total'], 2)}}</td> --}}
                                                    {{-- <td class="relative whitespace-nowrap py-3 pl-3 pr-4 text-right text-sm font-medium sm:pr-3">
                                            <a href="#" class="text-green-800 hover:text-green-700">View<span class="sr-only">, Lindsay Walton</span></a>
                                          </td> --}}
                                                </tr>
                                            @empty
                                                <tr class="border-t border-gray-300" x-show="opens">
                                                    <td colspan="4"
                                                        class="text-center italic whitespace-nowrap px-3 py-2 text-sm text-gray-500">
                                                        No Record</td>
                                                </tr>
                                            @endforelse
                                            <thead class="bg-green-800">
                                                <tr>
                                                    <th scope="col"
                                                        class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-50 sm:pl-3">
                                                    </th>
                                                    <th scope="col"
                                                        class="px-3 py-2 text-left text-sm font-semibold text-gray-50">
                                                        Allocated Fund</th>
                                                    <th scope="col"
                                                        class="px-3 py-2 text-left text-sm font-semibold text-gray-50">
                                                        Program</th>
                                                    <th scope="col"
                                                        class="px-3 py-2 text-left text-sm font-semibold text-gray-50">
                                                        Balance</th>
                                                    {{-- <th scope="col" class="px-3 py-2 text-left text-sm font-semibold text-gray-50">Total</th> --}}
                                                    {{-- <th scope="col" class="relative py-2 pl-3 pr-4 sm:pr-3">
                                                <span class="sr-only">Edit</span>
                                              </th> --}}
                                                </tr>
                                                @php
                                                    $sumAllocated = 0;
                                                    $sumTotal = 0;
                                                    $sumBalance = 0;

                                                    $balance = $wfp_balance;
                                                    $totalAllocated =
                                                        $record->fundAllocations
                                                            ->where('wpf_type_id', $wfp_type->id)
                                                            ->where('is_supplemental', 1)
                                                            ->sum('initial_amount') + $balance;
                                                    if ($is_supplemental) {
                                                        $sumAllocated = $totalAllocated;
                                                    } else {
                                                        $sumAllocated = $record->fundAllocations
                                                            ->where('wpf_type_id', $wfp_type->id)
                                                            ->where('is_supplemental', 0)
                                                            ->sum('initial_amount');
                                                    }
                                                    // $sumAllocated = $is_supplemental
                                                    //     ? $totalAllocated
                                                    //     : $record->fundAllocations->where('is_supplemental', 0)->sum('inital_amount');
                                                    $sumTotal = array_sum(
                                                        array_column($current_balance, 'current_total'),
                                                    );
                                                    $sumBalance = $sumAllocated - $sumTotal;

                                                @endphp
                                            <tbody class="bg-white">
                                                <tr class="border-t border-gray-300">
                                                    <td
                                                        class="whitespace-nowrap py-3 pl-4 pr-3 text-sm font-semibold text-gray-900 sm:pl-3">
                                                        Total</td>
                                                    <td
                                                        class="whitespace-nowrap py-3 pl-4 pr-3 text-sm text-right font-semibold text-gray-900 sm:pl-3">
                                                        ₱ {{ number_format($sumAllocated, 2) }}</td>
                                                    <td
                                                        class="whitespace-nowrap py-3 pl-4 pr-3 text-sm text-right font-semibold {{ $sumAllocated > $sumTotal ? 'text-gray-900' : 'text-red-600' }} sm:pl-3">
                                                        ₱ {{ number_format($sumTotal, 2) }}</td>
                                                    <td
                                                        class="whitespace-nowrap py-3 pl-4 pr-3 text-sm text-right font-semibold {{ $sumAllocated > $sumTotal ? 'text-gray-900' : 'text-red-600' }} sm:pl-3">
                                                        ₱ {{ number_format($sumBalance, 2) }}</td>
                                                </tr>
                                            </tbody>

                                            </thead>
                                        </tbody>
                                        </thead>
                                    </table>
                                </div>

                            @endif
                        </div>
                        <div class="col-span-2">

                            <div class="bg-white p-6 rounded-lg shadow-md">

                                {{-- Tabs --}}
                                <div class="flex justify-between mb-4">
                                    @php
                                        $step_labels = [
                                            'Initial Information',
                                            'Supplies',
                                            'MOOE',
                                            'Trainings',
                                            'Machines',
                                            'Buildings',
                                            'PS',
                                        ];
                                    @endphp
                                    @for ($i = 1; $i <= 7; $i++)
                                        <div class="flex-1 text-center cursor-pointer"
                                            wire:click="setStep({{ $i }})">
                                            <div
                                                class="w-full h-1
                                            @if ($global_index == $i) bg-green-700
                                            @elseif ($global_index > $i) bg-gray-400
                                            @else bg-gray-200 @endif
                                        ">
                                            </div>
                                            <p
                                                class="mt-2
                                            @if ($global_index == $i) text-green-700 font-bold
                                            @else text-gray-500 @endif
                                        ">
                                                {{ $step_labels[$i - 1] }}
                                            </p>
                                        </div>
                                    @endfor
                                </div>

                                {{-- Step Content --}}
                                <div>
                                    @if ($global_index == 1)
                                        @include('create-wfp-pages.initial_information')
                                    @elseif ($global_index == 2)
                                        @include('create-wfp-pages.supplies_expendables')
                                    @elseif ($global_index == 3)
                                        @include('create-wfp-pages.mooe')
                                    @elseif ($global_index == 4)
                                        @include('create-wfp-pages.trainings')
                                    @elseif ($global_index == 5)
                                        @include('create-wfp-pages.machine_equipment')
                                    @elseif ($global_index == 6)
                                        @include('create-wfp-pages.building_infrastracure')
                                    @elseif ($global_index == 7)
                                        @include('create-wfp-pages.ps-salaries')
                                    @endif
                                </div>

                                {{-- Navigation Buttons --}}
                                <div class="flex justify-between mt-6">
                                    @if ($global_index > 1)
                                        <button wire:click="decreaseStep"
                                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-1.5 px-3 rounded-lg">
                                            Back
                                        </button>
                                    @endif

                                    @if ($global_index < 7)
                                        <div></div>
                                        <button wire:click="increaseStep"
                                            class="bg-green-600 hover:bg-green-800 text-white font-bold py-1.5 px-3 rounded-lg">
                                            Next
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <table class="min-w-full">
                    <thead class="bg-green-800">
                      <tr>
                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-50 sm:pl-3">UACS Code</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-50">Account Title</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-50">Title Group</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-50">Particulars / Specifications</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-50">Title of Program</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-50">Qty</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-50">UOM</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-50">Cost per unit</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-50">Estimated Budget</th>
                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-3">
                          <span class="sr-only">Edit</span>
                        </th>
                      </tr>
                    </thead>
                    <tbody class="bg-white">
                      <tr class="border-t border-gray-200">
                        <th colspan="10" scope="colgroup" class="bg-yellow-100 py-2 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-3">Supplies & Semi-Expendables</th>
                      </tr>

                        @forelse ($supplies as $item)
                        <tr class="border-t border-gray-300">
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-3">Supplies & Semi-Expendables</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Front-end Developer</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">lindsay.walton@example.com</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Member</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Member</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Member</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Member</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Member</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Member</td>
                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-3">
                          <a href="#" class="text-green-800 hover:text-green-700">Edit<span class="sr-only">, Lindsay Walton</span></a>
                        </td>
                        </tr>
                        @empty
                        <tr  class="border-t border-gray-300">
                            <td colspan="10" class="text-center italic whitespace-nowrap px-3 py-2 text-sm text-gray-500">No Record</td>
                        </tr>
                        @endforelse

                      <tr class="border-t border-gray-200">
                        <th colspan="10" scope="colgroup" class="bg-yellow-100 py-2 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-3">MOOE</th>
                      </tr>
                      @forelse ($mooe as $item)
                      <tr class="border-t border-gray-300">
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-3">MOOE</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Front-end Developer</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">lindsay.walton@example.com</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Member</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Member</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Member</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Member</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Member</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Member</td>
                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-3">
                          <a href="#" class="text-green-800 hover:text-green-700">Edit<span class="sr-only">, Lindsay Walton</span></a>
                        </td>
                      </tr>
                      @empty
                      <tr  class="border-t border-gray-300">
                        <td colspan="10" class="text-center italic whitespace-nowrap px-3 py-2 text-sm text-gray-500">No Record</td>
                      </tr>
                      @endforelse
                      <tr class="border-t border-gray-200">
                        <th colspan="10" scope="colgroup" class="bg-yellow-100 py-2 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-3">Trainings</th>
                      </tr>
                      @forelse ($trainings as $item)
                      <tr class="border-t border-gray-300">
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-3">Trainings</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Front-end Developer</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">lindsay.walton@example.com</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Member</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Member</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Member</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Member</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Member</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Member</td>
                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-3">
                          <a href="#" class="text-green-800 hover:text-green-700">Edit<span class="sr-only">, Lindsay Walton</span></a>
                        </td>
                      </tr>
                      @empty
                      <tr  class="border-t border-gray-300">
                        <td colspan="10" class="text-center italic whitespace-nowrap px-3 py-2 text-sm text-gray-500">No Record</td>
                      </tr>
                      @endforelse
                      <tr class="border-t border-gray-200">
                        <th colspan="10" scope="colgroup" class="bg-yellow-100 py-2 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-3">Machine & Equipment / Furniture & Fixtures / Bio / Vehicles</th>
                      </tr>
                      @forelse ($machines as $item)
                      <tr class="border-t border-gray-300">
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-3">Machine & Equipment / Furniture & Fixtures / Bio / Vehicles</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Front-end Developer</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">lindsay.walton@example.com</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Member</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Member</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Member</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Member</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Member</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Member</td>
                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-3">
                          <a href="#" class="text-green-800 hover:text-green-700">Edit<span class="sr-only">, Lindsay Walton</span></a>
                        </td>
                      </tr>
                      @empty
                      <tr  class="border-t border-gray-300">
                        <td colspan="10" class="text-center italic whitespace-nowrap px-3 py-2 text-sm text-gray-500">No Record</td>
                      </tr>
                      @endforelse
                      <tr class="border-t border-gray-200">
                        <th colspan="10" scope="colgroup" class="bg-yellow-100 py-2 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-3">Building & Infrastructure</th>
                      </tr>
                      @forelse ($buildings as $building)
                      <tr class="border-t border-gray-300">
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-3">Building & Infrastructure</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Front-end Developer</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">lindsay.walton@example.com</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Member</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Member</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Member</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Member</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Member</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Member</td>
                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-3">
                          <a href="#" class="text-green-800 hover:text-green-700">Edit<span class="sr-only">, Lindsay Walton</span></a>
                        </td>
                      </tr>
                      @empty
                      <tr  class="border-t border-gray-300">
                        <td colspan="10" class="text-center italic whitespace-nowrap px-3 py-2 text-sm text-gray-500">No Record</td>
                      </tr>
                      @endforelse

                    </tbody>
                  </table> --}}

            <div wire:ignore.self>
                <x-modal.card title="Work & Financial Plan Preview" fullscreen blur
                    wire:model.defer="suppliesDetailModal">

                    <div>
                        <div class="px-4 sm:px-6 lg:px-8">
                            <div class="sm:flex sm:items-center">
                                <div class="sm:flex-auto">
                                    <h1 class="text-base font-semibold leading-6 text-gray-900">Work & Financial Plan
                                    </h1>

                                </div>
                            </div>
                            <div class="mt-2 grid grid-cols-3">
                                <div class="col-span-1">
                                    <p class="mt-2 text-sm text-gray-700">{{ $wfp_type->description }}</p>
                                    <p class="mt-2 text-sm text-gray-700">Fund:
                                        {{ $wfp_fund->name }}
                                        {{ $fund_description === null ? '' : '- ' . $fund_description }}</p>
                                </div>
                                <div class="col-span-1">
                                    <p class="mt-2 text-sm text-gray-700">Source of Fund: {{ $source_fund }}</p>
                                    <p class="mt-2 text-sm text-gray-700">if miscellaneous/fiduciary fee, please
                                        specify: {{ $confirm_fund_source ?? 'N/A' }}</p>
                                </div>
                                <div class="col-span-1">
                                    <p class="mt-2 text-sm text-gray-700">Cost Center: {{ $costCenter->name }}</p>
                                    <p class="mt-2 text-sm text-gray-700">Cost Center Head:
                                        {{ $costCenter->office->head_employee?->full_name . ' - ' . $costCenter->office->name }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-8 flow-root">
                                <div class="my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-2">
                                        <table class="min-w-full">
                                            <thead class="bg-gray-400">
                                                <tr class="border-t border-gray-200">
                                                    <th colspan="23" scope="colgroup"
                                                        class="bg-green-700 py-2 pl-4 pr-3 text-left text-sm font-semibold text-gray-50 sm:pl-3 h-10">
                                                    </th>
                                                </tr>
                                            </thead>
                                            <thead class="bg-white">
                                                <tr>
                                                    <th scope="col"
                                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                        UACS Code</th>
                                                    <th scope="col"
                                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                        Account Title</th>
                                                    <th scope="col"
                                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                        Particulars</th>
                                                    <th scope="col"
                                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                        Remarks</th>
                                                    <th scope="col"
                                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                        Supply Code</th>
                                                    <th scope="col"
                                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                        Qty</th>
                                                    <th scope="col"
                                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                        UOM</th>
                                                    <th scope="col"
                                                        class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">
                                                        Unit Cost (₱)</th>
                                                    <th scope="col"
                                                        class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">
                                                        Estimated Budget (₱)</th>
                                                    <th scope="col"
                                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">
                                                        Jan</th>
                                                    <th scope="col"
                                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">
                                                        Feb</th>
                                                    <th scope="col"
                                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">
                                                        Mar</th>
                                                    <th scope="col"
                                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">
                                                        Apr</th>
                                                    <th scope="col"
                                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">
                                                        May</th>
                                                    <th scope="col"
                                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">
                                                        Jun</th>
                                                    <th scope="col"
                                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">
                                                        Jul</th>
                                                    <th scope="col"
                                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">
                                                        Aug</th>
                                                    <th scope="col"
                                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">
                                                        Sep</th>
                                                    <th scope="col"
                                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">
                                                        Oct</th>
                                                    <th scope="col"
                                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">
                                                        Nov</th>
                                                    <th scope="col"
                                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">
                                                        Dec</th>
                                                    <th scope="col"
                                                        class="relative py-3.5 pl-3 pr-4 sm:pr-3 bg-gray-200">
                                                        <span class="sr-only">View</span>
                                                    </th>
                                                    <th scope="col"
                                                        class="relative py-3.5 pl-3 pr-4 sm:pr-3 bg-gray-200">
                                                        <span class="sr-only">Delete</span>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white">
                                                @php
                                                    $supply_name = App\Models\BudgetCategory::where('id', 1)->first()
                                                        ->name;
                                                @endphp
                                                <tr class="border-t border-gray-200">
                                                    <th colspan="23" scope="colgroup"
                                                        class="bg-yellow-100 py-2 pl-4 pr-3
                                                text-left text-sm font-semibold text-gray-900 sm:pl-3">
                                                        {{ $supply_name }}</th>
                                                </tr>
                                                @php
                                                    $fund_allocation_categories = $fund_allocations
                                                        ->where('initial_amount', '!=', '0.00')
                                                        ->pluck('category_group_id')
                                                        ->toArray();
                                                @endphp
                                                @forelse ($supplies as $item)
                                                    <tr class="border-t border-gray-300">
                                                        @if (in_array($wfp_fund->id, [1, 3, 9]))
                                                            <td
                                                                class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-900' : 'text-red-600' }} sm:pl-3">
                                                                {{ $item['uacs'] }}</td>
                                                            <td
                                                                class="px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-wrap">
                                                                {{ $item['account_title'] }}</td>
                                                            <td
                                                                class="px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-wrap">
                                                                {{ $item['particular'] }}</td>
                                                            <td
                                                                class="px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-wrap">
                                                                {{ $item['remarks'] }}</td>
                                                            <td
                                                                class="px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-wrap">
                                                                {{ $item['supply_code'] }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-wrap">
                                                                {{ $item['total_quantity'] }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }}">
                                                                {{ $item['uom'] }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-right">
                                                                {{ number_format($item['cost_per_unit'], 2) }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-right">
                                                                {{ number_format((float) ($item['cost_per_unit'] * $item['total_quantity']), 2, '.', ',') }}
                                                            </td>
                                                        @else
                                                            <td
                                                                class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-3">
                                                                {{ $item['uacs'] }}</td>
                                                            <td class="px-3 py-4 text-sm text-gray-500 text-wrap">
                                                                {{ $item['account_title'] }}</td>
                                                            <td class="px-3 py-4 text-sm text-gray-500 text-wrap">
                                                                {{ $item['particular'] }}</td>
                                                            <td class="px-3 py-4 text-sm text-gray-500 text-wrap">
                                                                {{ $item['remarks'] }}</td>
                                                            <td class="px-3 py-4 text-sm text-gray-500 text-wrap">
                                                                {{ $item['supply_code'] }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-wrap">
                                                                {{ $item['total_quantity'] }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                                {{ $item['uom'] }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-right">
                                                                {{ number_format($item['cost_per_unit'], 2) }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-right">
                                                                {{ $item['cost_per_unit'] === '' ? number_format(0, 2) : number_format((float) ($item['cost_per_unit'] * $item['total_quantity']), 2, '.', ',') }}
                                                            </td>
                                                        @endif
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][0] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][1] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][2] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][3] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][4] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][5] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][6] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][7] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][8] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][9] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][10] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][11] }}</td>
                                                        {{-- <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-3">
                                                    @if ($item['remarks'] != null)
                                                    <button wire:click="viewRemarks({{$loop->index}}, 1)" class="text-blue-600 hover:text-blue-900">View Remarks</button>
                                                    @endif
                                                </td> --}}
                                                        <td
                                                            class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-3">
                                                            <button wire:click="deleteSupply({{ $loop->index }})"
                                                                class="text-red-600 hover:text-red-900">
                                                                <svg class="w-5 h-5 text-red-600 hover:text-red-900"
                                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                    viewBox="0 0 24 24" stroke-width="1.5"
                                                                    stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                                </svg>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr class="border-t border-gray-200">
                                                        <th colspan="23" scope="colgroup"
                                                            class="bg-gray-100 py-2 pl-4 pr-3 text-center text-sm font-semibold text-gray-900 sm:pl-3">
                                                            No Record</th>
                                                    </tr>
                                                @endforelse
                                                @php
                                                    $mooe_name = App\Models\BudgetCategory::where('id', 2)->first()
                                                        ->name;
                                                @endphp
                                                <tr class="border-t border-gray-200">
                                                    <th colspan="23" scope="colgroup"
                                                        class="bg-yellow-100 py-2 pl-4 pr-3
                                                text-left text-sm font-semibold text-gray-900 sm:pl-3">
                                                        {{ $mooe_name }}</th>
                                                </tr>
                                                @forelse ($mooe as $item)
                                                    <tr class="border-t border-gray-300">
                                                        @if (in_array($wfp_fund->id, [1, 3, 9]))
                                                            <td
                                                                class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-900' : 'text-red-600' }} sm:pl-3">
                                                                {{ $item['uacs'] }}

                                                            </td>
                                                            <td
                                                                class="px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-wrap">
                                                                {{ $item['account_title'] }}</td>
                                                            <td
                                                                class="px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-wrap">
                                                                {{ $item['particular'] }}</td>
                                                            <td
                                                                class="px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-wrap">
                                                                {{ $item['remarks'] }}</td>
                                                            <td
                                                                class="px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-wrap">
                                                                {{ $item['supply_code'] }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-wrap">
                                                                {{ $item['total_quantity'] }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }}">
                                                                {{ $item['uom'] }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-right">
                                                                {{ number_format($item['cost_per_unit'], 2) }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-right">
                                                                {{ number_format((float) ($item['cost_per_unit'] * $item['total_quantity']), 2, '.', ',') }}
                                                            </td>
                                                        @else
                                                            <td
                                                                class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-3">
                                                                {{ $item['uacs'] }}</td>
                                                            <td class="px-3 py-4 text-sm text-gray-500 text-wrap">
                                                                {{ $item['account_title'] }}</td>
                                                            <td class="px-3 py-4 text-sm text-gray-500 text-wrap">
                                                                {{ $item['particular'] }}</td>
                                                            <td class="px-3 py-4 text-sm text-gray-500 text-wrap">
                                                                {{ $item['remarks'] }}</td>
                                                            <td class="px-3 py-4 text-sm text-gray-500 text-wrap">
                                                                {{ $item['supply_code'] }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-wrap">
                                                                {{ $item['total_quantity'] }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                                {{ $item['uom'] }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-right">
                                                                {{ number_format($item['cost_per_unit'], 2) }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-right">
                                                                {{ $item['cost_per_unit'] === '' ? number_format(0, 2) : number_format((float) ($item['cost_per_unit'] * $item['total_quantity']), 2, '.', ',') }}
                                                            </td>
                                                        @endif
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][0] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][1] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][2] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][3] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][4] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][5] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][6] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][7] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][8] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][9] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][10] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][11] }}</td>
                                                        {{-- <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-3">
                                                    @if ($item['remarks'] != null)
                                                    <button wire:click="viewRemarks({{$loop->index}}, 2)" class="text-blue-600 hover:text-blue-900">View Remarks</button>
                                                    @endif
                                                </td> --}}
                                                        <td
                                                            class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-3">
                                                            <button wire:click="deleteMooe({{ $loop->index }})"
                                                                class="text-red-600 hover:text-red-900">
                                                                <svg class="w-5 h-5 text-red-600 hover:text-red-900"
                                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                    viewBox="0 0 24 24" stroke-width="1.5"
                                                                    stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                                </svg>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr class="border-t border-gray-200">
                                                        <th colspan="23" scope="colgroup"
                                                            class="bg-gray-100 py-2 pl-4 pr-3 text-center text-sm font-semibold text-gray-900 sm:pl-3">
                                                            No Record</th>
                                                    </tr>
                                                @endforelse
                                                @php
                                                    $training_name = App\Models\BudgetCategory::where('id', 3)->first()
                                                        ->name;
                                                @endphp
                                                <tr class="border-t border-gray-200">
                                                    <th colspan="23" scope="colgroup"
                                                        class="bg-yellow-100 py-2 pl-4 pr-3
                                                text-left text-sm font-semibold text-gray-900 sm:pl-3">
                                                        {{ $training_name }}</th>
                                                </tr>
                                                @forelse ($trainings as $item)
                                                    <tr class="border-t border-gray-300">
                                                        @if (in_array($wfp_fund->id, [1, 3, 9]))
                                                            <td
                                                                class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-900' : 'text-red-600' }} sm:pl-3">
                                                                {{ $item['uacs'] }}</td>
                                                            <td
                                                                class="px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-wrap">
                                                                {{ $item['account_title'] }}</td>
                                                            <td
                                                                class="px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-wrap">
                                                                {{ $item['particular'] }}</td>
                                                            <td
                                                                class="px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-wrap">
                                                                {{ $item['remarks'] }}</td>
                                                            <td
                                                                class="px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-wrap">
                                                                {{ $item['supply_code'] }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-wrap">
                                                                {{ $item['total_quantity'] }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }}">
                                                                {{ $item['uom'] }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-right">
                                                                {{ number_format($item['cost_per_unit'], 2) }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-right">
                                                                {{ number_format((float) ($item['cost_per_unit'] * $item['total_quantity']), 2, '.', ',') }}
                                                            </td>
                                                        @else
                                                            <td
                                                                class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-3">
                                                                {{ $item['uacs'] }}</td>
                                                            <td class="px-3 py-4 text-sm text-gray-500 text-wrap">
                                                                {{ $item['account_title'] }}</td>
                                                            <td class="px-3 py-4 text-sm text-gray-500 text-wrap">
                                                                {{ $item['particular'] }}</td>
                                                            <td class="px-3 py-4 text-sm text-gray-500 text-wrap">
                                                                {{ $item['remarks'] }}</td>
                                                            <td class="px-3 py-4 text-sm text-gray-500 text-wrap">
                                                                {{ $item['supply_code'] }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-wrap">
                                                                {{ $item['total_quantity'] }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                                {{ $item['uom'] }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-right">
                                                                {{ $item['cost_per_unit'] === '' ? number_format(0, 2) : number_format($item['cost_per_unit'], 2) }}
                                                            </td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-right">
                                                                {{ $item['cost_per_unit'] === '' ? number_format(0, 2) : number_format((float) ($item['cost_per_unit'] * $item['total_quantity']), 2, '.', ',') }}
                                                            </td>
                                                        @endif
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][0] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][1] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][2] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][3] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][4] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][5] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][6] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][7] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][8] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][9] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][10] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][11] }}</td>
                                                        {{-- <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-3">
                                                    @if ($item['remarks'] != null)
                                                    <button wire:click="viewRemarks({{$loop->index}}, 3)" class="text-blue-600 hover:text-blue-900">View Remarks</button>
                                                    @endif
                                                </td> --}}
                                                        <td
                                                            class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-3">
                                                            <button wire:click="deleteTraining({{ $loop->index }})"
                                                                class="">
                                                                <svg class="w-5 h-5 text-red-600 hover:text-red-900"
                                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                    viewBox="0 0 24 24" stroke-width="1.5"
                                                                    stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                                </svg>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr class="border-t border-gray-200">
                                                        <th colspan="23" scope="colgroup"
                                                            class="bg-gray-100 py-2 pl-4 pr-3 text-center text-sm font-semibold text-gray-900 sm:pl-3">
                                                            No Record</th>
                                                    </tr>
                                                @endforelse
                                                @php
                                                    $machine_name = App\Models\BudgetCategory::where('id', 4)->first()
                                                        ->name;
                                                @endphp
                                                <tr class="border-t border-gray-200">
                                                    <th colspan="23" scope="colgroup"
                                                        class="bg-yellow-100 py-2 pl-4 pr-3
                                                text-left text-sm font-semibold text-gray-900 sm:pl-3">
                                                        {{ $machine_name }}</th>
                                                </tr>
                                                @forelse ($machines as $item)
                                                    <tr class="border-t border-gray-300">
                                                        @if (in_array($wfp_fund->id, [1, 3, 9]))
                                                            <td
                                                                class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-900' : 'text-red-600' }} sm:pl-3">
                                                                {{ $item['uacs'] }}</td>
                                                            <td
                                                                class="px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-wrap">
                                                                {{ $item['account_title'] }}</td>
                                                            <td
                                                                class="px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-wrap">
                                                                {{ $item['particular'] }}</td>
                                                            <td
                                                                class="px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-wrap">
                                                                {{ $item['remarks'] }}</td>
                                                            <td
                                                                class="px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-wrap">
                                                                {{ $item['supply_code'] }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-wrap">
                                                                {{ $item['total_quantity'] }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }}">
                                                                {{ $item['uom'] }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-right">
                                                                {{ number_format($item['cost_per_unit'], 2) }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-right">
                                                                {{ number_format((float) ($item['cost_per_unit'] * $item['total_quantity']), 2, '.', ',') }}
                                                            </td>
                                                        @else
                                                            <td
                                                                class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-3">
                                                                {{ $item['uacs'] }}</td>
                                                            <td class="px-3 py-4 text-sm text-gray-500 text-wrap">
                                                                {{ $item['account_title'] }}</td>
                                                            <td class="px-3 py-4 text-sm text-gray-500 text-wrap">
                                                                {{ $item['particular'] }}</td>
                                                            <td class="px-3 py-4 text-sm text-gray-500 text-wrap">
                                                                {{ $item['remarks'] }}</td>
                                                            <td class="px-3 py-4 text-sm text-gray-500 text-wrap">
                                                                {{ $item['supply_code'] }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-wrap">
                                                                {{ $item['total_quantity'] }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                                {{ $item['uom'] }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-right">
                                                                {{ number_format($item['cost_per_unit'], 2) }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-right">
                                                                {{ $item['cost_per_unit'] === '' ? number_format(0, 2) : number_format((float) ($item['cost_per_unit'] * $item['total_quantity']), 2, '.', ',') }}
                                                            </td>
                                                        @endif
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][0] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][1] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][2] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][3] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][4] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][5] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][6] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][7] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][8] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][9] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][10] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][11] }}</td>
                                                        {{-- <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-3">
                                                    @if ($item['remarks'] != null)
                                                    <button wire:click="viewRemarks({{$loop->index}}, 4)" class="text-blue-600 hover:text-blue-900">View Remarks</button>
                                                    @endif
                                                </td> --}}
                                                        <td
                                                            class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-3">
                                                            <button wire:click="deleteMachine({{ $loop->index }})"
                                                                class="text-red-600 hover:text-red-900">
                                                                <svg class="w-5 h-5 text-red-600 hover:text-red-900"
                                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                    viewBox="0 0 24 24" stroke-width="1.5"
                                                                    stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                                </svg>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr class="border-t border-gray-200">
                                                        <th colspan="23" scope="colgroup"
                                                            class="bg-gray-100 py-2 pl-4 pr-3 text-center text-sm font-semibold text-gray-900 sm:pl-3">
                                                            No Record</th>
                                                    </tr>
                                                @endforelse
                                                @php
                                                    $building_name = App\Models\BudgetCategory::where('id', 5)->first()
                                                        ->name;
                                                @endphp
                                                <tr class="border-t border-gray-200">
                                                    <th colspan="23" scope="colgroup"
                                                        class="bg-yellow-100 py-2 pl-4 pr-3
                                                text-left text-sm font-semibold text-gray-900 sm:pl-3">
                                                        {{ $building_name }}</th>
                                                </tr>
                                                @forelse ($buildings as $item)
                                                    <tr class="border-t border-gray-300">
                                                        @if (in_array($wfp_fund->id, [1, 3, 9]))
                                                            <td
                                                                class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-900' : 'text-red-600' }} sm:pl-3">
                                                                {{ $item['uacs'] }}</td>
                                                            <td
                                                                class="px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-wrap">
                                                                {{ $item['account_title'] }}</td>
                                                            <td
                                                                class="px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-wrap">
                                                                {{ $item['particular'] }}</td>
                                                            <td
                                                                class="px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-wrap">
                                                                {{ $item['remarks'] }}</td>
                                                            <td
                                                                class="px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-wrap">
                                                                {{ $item['supply_code'] }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-wrap">
                                                                {{ $item['total_quantity'] }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }}">
                                                                {{ $item['uom'] }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-right">
                                                                {{ number_format($item['cost_per_unit'], 2) }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-right">
                                                                {{ number_format((float) ($item['cost_per_unit'] * $item['total_quantity']), 2, '.', ',') }}
                                                            </td>
                                                        @else
                                                            <td
                                                                class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-3">
                                                                {{ $item['uacs'] }}</td>
                                                            <td class="px-3 py-4 text-sm text-gray-500 text-wrap">
                                                                {{ $item['account_title'] }}</td>
                                                            <td class="px-3 py-4 text-sm text-gray-500 text-wrap">
                                                                {{ $item['particular'] }}</td>
                                                            <td class="px-3 py-4 text-sm text-gray-500 text-wrap">
                                                                {{ $item['remarks'] }}</td>
                                                            <td class="px-3 py-4 text-sm text-gray-500 text-wrap">
                                                                {{ $item['supply_code'] }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-wrap">
                                                                {{ $item['total_quantity'] }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                                {{ $item['uom'] }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-right">
                                                                {{ number_format($item['cost_per_unit'], 2) }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-right">
                                                                {{ $item['cost_per_unit'] === '' ? number_format(0, 2) : number_format((float) ($item['cost_per_unit'] * $item['total_quantity']), 2, '.', ',') }}
                                                            </td>
                                                        @endif
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][0] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][1] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][2] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][3] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][4] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][5] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][6] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][7] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][8] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][9] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][10] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][11] }}</td>
                                                        {{-- <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-3">
                                                    @if ($item['remarks'] != null)
                                                    <button wire:click="viewRemarks({{$loop->index}}, 5)" class="text-blue-600 hover:text-blue-900">View Remarks</button>
                                                    @endif
                                                </td> --}}
                                                        <td
                                                            class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-3">
                                                            <button wire:click="deleteBuilding({{ $loop->index }})"
                                                                class="text-red-600 hover:text-red-900">
                                                                <svg class="w-5 h-5 text-red-600 hover:text-red-900"
                                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                    viewBox="0 0 24 24" stroke-width="1.5"
                                                                    stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                                </svg>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr class="border-t border-gray-200">
                                                        <th colspan="23" scope="colgroup"
                                                            class="bg-gray-100 py-2 pl-4 pr-3 text-center text-sm font-semibold text-gray-900 sm:pl-3">
                                                            No Record</th>
                                                    </tr>
                                                @endforelse
                                                @php
                                                    $ps_name = App\Models\BudgetCategory::where('id', 6)->first()->name;
                                                @endphp
                                                <tr class="border-t border-gray-200">
                                                    <th colspan="23" scope="colgroup"
                                                        class="bg-yellow-100 py-2 pl-4 pr-3
                                                text-left text-sm font-semibold text-gray-900 sm:pl-3">
                                                        {{ $ps_name }}</th>
                                                </tr>
                                                @forelse ($ps as $item)
                                                    <tr class="border-t border-gray-300">
                                                        @if (in_array($wfp_fund->id, [1, 3, 9]))
                                                            <td
                                                                class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-900' : 'text-red-600' }} sm:pl-3">
                                                                {{ $item['uacs'] }}</td>
                                                            <td
                                                                class="px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-wrap">
                                                                {{ $item['account_title'] }}</td>
                                                            <td
                                                                class="px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-wrap">
                                                                {{ $item['particular'] }}</td>
                                                            <td
                                                                class="px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-wrap">
                                                                {{ $item['remarks'] }}</td>
                                                            <td
                                                                class="px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-wrap">
                                                                {{ $item['supply_code'] }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-wrap">
                                                                {{ $item['total_quantity'] }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }}">
                                                                {{ $item['uom'] }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-right">
                                                                {{ number_format($item['cost_per_unit'], 2) }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm {{ in_array($item['title_group'], $fund_allocation_categories) || isset($draft_amounts[$item['title_group']]) ? 'text-gray-500' : 'text-red-600' }} text-right">
                                                                {{ number_format((float) ($item['cost_per_unit'] * $item['total_quantity']), 2, '.', ',') }}
                                                            </td>
                                                        @else
                                                            <td
                                                                class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-3">
                                                                {{ $item['uacs'] }}</td>
                                                            <td class="px-3 py-4 text-sm text-gray-500 text-wrap">
                                                                {{ $item['account_title'] }}</td>
                                                            <td class="px-3 py-4 text-sm text-gray-500 text-wrap">
                                                                {{ $item['particular'] }}</td>
                                                            <td class="px-3 py-4 text-sm text-gray-500 text-wrap">
                                                                {{ $item['remarks'] }}</td>
                                                            <td class="px-3 py-4 text-sm text-gray-500 text-wrap">
                                                                {{ $item['supply_code'] }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-wrap">
                                                                {{ $item['total_quantity'] }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                                {{ $item['uom'] }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-right">
                                                                {{ number_format($item['cost_per_unit'], 2) }}</td>
                                                            <td
                                                                class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-right">
                                                                {{ $item['cost_per_unit'] === '' ? number_format(0, 2) : number_format((float) ($item['cost_per_unit'] * $item['total_quantity']), 2, '.', ',') }}
                                                            </td>
                                                        @endif
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][0] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][1] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][2] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][3] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][4] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][5] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][6] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][7] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][8] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][9] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][10] }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">
                                                            {{ $item['quantity'][11] }}</td>
                                                        {{-- <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-3">
                                                    @if ($item['remarks'] != null)
                                                    <button wire:click="viewRemarks({{$loop->index}}, 5)" class="text-blue-600 hover:text-blue-900">View Remarks</button>
                                                    @endif
                                                </td> --}}
                                                        <td
                                                            class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-3">
                                                            <button wire:click="deletePs({{ $loop->index }})"
                                                                class="text-red-600 hover:text-red-900">
                                                                <svg class="w-5 h-5 text-red-600 hover:text-red-900"
                                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                    viewBox="0 0 24 24" stroke-width="1.5"
                                                                    stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                                </svg>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr class="border-t border-gray-200">
                                                        <th colspan="23" scope="colgroup"
                                                            class="bg-gray-100 py-2 pl-4 pr-3 text-center text-sm font-semibold text-gray-900 sm:pl-3">
                                                            No Record</th>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                        <div class="grid grid-cols-3 space-x-3 mt-5">
                                            <div class="col-span-1 text-gray-800 font-semibold">

                                            </div>
                                            <div class="col-span-1 text-gray-800 font-semibold">

                                            </div>
                                            <div class="col-span-1 text-gray-800 font-semibold flex justify-end">
                                                <div>
                                                    @php
                                                        $sumAllocated = 0;
                                                        $sumTotal = 0;
                                                        $sumBalance = 0;

                                                        $sumAllocated = array_sum(
                                                            array_column($current_balance, 'initial_amount'),
                                                        );
                                                        $sumTotal = array_sum(
                                                            array_column($current_balance, 'current_total'),
                                                        );
                                                        $sumBalance = $sumAllocated - $sumTotal;
                                                        // $sumBalance = array_sum(array_column($current_balance, 'balance'));
                                                    @endphp
                                                    <div class="flex justify-between space-x-3">
                                                        {{-- TODO --}}
                                                        <span>Allocated Fund: </span><span>₱
                                                            {{ number_format($sumAllocated + $programmed_non_supplemental, 2) }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        {{-- TODO --}}
                                                        <span>Program: </span><span>₱
                                                            {{ number_format($sumTotal, 2) }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span>Balance: </span><span>₱
                                                            {{ number_format($sumBalance, 2) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <x-slot name="footer">
                        <div class="flex justify-between gap-x-4">
                            <div></div>
                            <div class="flex">
                                <x-button flat label="Cancel" x-on:click="close" />
                                <x-button spinner="submit" primary label="Submit"
                                    x-on:confirm="{
                                        title: 'Are you sure you want to save this data?',
                                        body: 'Please review the data before submitting.',
                                        icon: 'warning',
                                        method: 'submit',
                                        params: 1
                                    }" />
                            </div>
                        </div>
                    </x-slot>
                </x-modal.card>
            </div>
            {{-- remarks modal  --}}
            <div>
                <x-modal.card title="Remarks" blur wire:model.defer="remarksModal">
                    <div>
                        <span>{{ $remarks_modal_title }}</span>
                        <div class="mt-2 w-full">
                            @switch($remarks_modal_title)
                                @case('Supplies & Semi-Expendables')
                                    <textarea id="about" disabled wire:model="supplies_remarks_details" name="about" rows="4"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                                @break

                                @case('MOOE')
                                    <textarea id="about" disabled wire:model="mooe_remarks_details" name="about" rows="4"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                                @break

                                @case('Trainings')
                                    <textarea id="about" disabled wire:model="training_remarks_details" name="about" rows="4"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                                @break

                                @case('Machine & Equipment / Furniture & Fixtures / Bio / Vehicles')
                                    <textarea id="about" disabled wire:model="machine_remarks_details" name="about" rows="4"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                                @break

                                @case('Building & Infrastructure')
                                    <textarea id="about" disabled wire:model="building_remarks_details" name="about" rows="4"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                                @break

                                @default
                            @endswitch

                            {{-- <p class="mt-3 text-sm leading-6 text-gray-600">Write a few sentences about yourself.</p> --}}
                        </div>
                    </div>

                    {{-- <x-slot name="footer">
                            <div class="flex justify-between gap-x-4">
                                <x-button flat negative label="Delete" wire:click="delete" />

                                <div class="flex">
                                    <x-button flat label="Cancel" x-on:click="close" />
                                    <x-button primary label="Save" wire:click="save" />
                                </div>
                            </div>
                        </x-slot> --}}
                </x-modal.card>
            </div>

        </div>


        {{-- end body --}}
        {{-- <div class="p-3 bg-gray-50 rounded-lg">
        <form wire:submit.prevent="submit">
            {{ $this->form }}
            <div class="mt-5 p-3 border border-gray-300 rounded-lg">
                <div class="flex py-2 justify-end">
                    <span class="font-semibold">Grand Total: ₱ 100,000.00</span>
                </div>
                 <div class="flex py-2 justify-end">
                    <span class="font-semibold">Budget Allocation: ₱ {{number_format($costCenter->fundAllocations->first()->amount, 2)}}</span>
                </div>
                <div class="flex py-2 justify-end">
                    <span class="font-semibold">Balance forwarded from previous years: ₱ 15,000.00</span>
                </div>
                <div class="flex py-2 justify-end">
                    <span class="font-semibold border-gray-800 border-t-2 w-1/6"></span>
                </div>
                <div class="flex py-2 justify-end">
                    <span class="font-semibold">Unprogrammed Allocation: ₱ 88,660.00</span>
                </div>
            </div>
            <div class="mt-5 flex justify-end">

                <button class="hover:bg-primary-500 p-2 bg-primary-600 rounded-md font-light capitalize text-white text-sm" type="submit">
                    Submit
                </button>
            </div>
        </form>

    </div> --}}
