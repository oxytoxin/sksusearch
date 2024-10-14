<div class="">
    <div class="grid grid-cols-1 lg:grid-cols-3">
        <div class="flex-row col-span-1 lg:col-span-2">
            <div class="px-4 py-5 bg-white border-b rounded-md border-primary-200 sm:px-6 lg:rounded-none lg:rounded-tl-lg">
                <div class="flex flex-wrap items-center justify-between -mt-4 -ml-4 sm:flex-nowrap">
                    <div class="w-full mt-4 ml-4">
                        <h3 class="text-lg font-medium leading-6 text-primary-900">Request Supply Details</h3>
                        <p class="mt-4 text-sm text-primary-500">Particular: {{$record->particulars}}</p>
                        <p class="mt-1 text-sm text-primary-500">Specifications: </p>
                        <div class="text-sm text-primary-500 italic ml-5">
                            {!! $record->specification !!}
                        </div>
                        @php
                             $isAccountant = auth()->user()->employee_information->position_id == 15 && auth()->user()->employee_information->office_id == 3;
                             $isSupplyChief = auth()->user()->employee_information->office_id == 49 && auth()->user()->employee_information->position_id == 15;
                             $supplyChief = App\Models\EmployeeInformation::where('office_id', 49)->where('position_id', 15)->first();
                             $accountant = App\Models\EmployeeInformation::where('office_id', 3)->where('position_id', 15)->first();
                        @endphp
                        <p class="mt-1 text-sm text-primary-500">Unit Cost : &#8369; {{ number_format($record->unit_cost, 2) }}</p>
                        <p class="mt-1 text-sm text-primary-500">PPMP : {{$record->is_ppmp ? 'Yes' : 'No'}}</p>
                        <p class="mt-1 text-sm text-primary-500">Date Requested : {{Carbon\Carbon::parse($record->created_at)->format('F d, Y h:i A')}}</p>
                        <p class="mt-4 text-sm text-primary-500 ">Supply Code :
                            <span class="mt-1 text-sm text-primary-500">{{$record->supply_code}}</span>
                            {{-- @if ($record->status == 'Pending' || $record->status == 'Forwarded to Supply' && $record->supply_code == null)
                            <span class="italic underline ml-2 text-red-600">To be added by supply</span>
                            @elseif($isSupplyChief && $record->supply_code == null)
                            <button class="italic underline ml-2 font-semibold" wire:click="$set('assignSupplyCode',true)">(Assign Supply Code)</button>
                            @else
                            <span class="mt-1 text-sm text-primary-500">{{$record->supply_code}}</span>
                            @endif --}}
                        </p>
                        {{-- <p class="mt-1 text-sm text-primary-500 ">Date Added :
                            @if ($record->status == 'Pending')
                            <span class="italic underline ml-2 text-red-600">To be added by supply</span>
                            @endif
                        </p> --}}
                        <p class="mt-4 text-sm text-primary-500 ">UACS Code : <span class="italic underline ml-2 text-red-600">To be added by accounting</span></p>
                        <p class="mt-1 text-sm text-primary-500 ">Account Title : <span class="italic underline ml-2 text-red-600">To be added by accounting</span></p>
                        <p class="mt-1 text-sm text-primary-500 ">Title Group : <span class="italic underline ml-2 text-red-600">To be added by accounting</span></p>
                        {{-- <p class="mt-1 text-sm text-primary-500 ">Date Added : <span class="italic underline ml-2 text-red-600">To be added by accounting</span></p> --}}

                            {{-- @if ($is_motorpool_head)
                                <button class="italic underline ml-2" wire:click="$set('modifyDates',true)">(Click to modify)</button>
                            @endif
                        </p>
                        <p class="mt-1 text-sm text-primary-500">Vehicle :

                            @if (($is_president || $is_motorpool_head) && $request->vehicle_id == null)
                            <button class="italic underline ml-2" wire:click="$set('assignVehicleModal',true)">(Assign Vehicle)</button>
                            @elseif(($is_president || $is_motorpool_head) && $request->vehicle_id != null)
                            <button class="italic underline ml-2" wire:click="$set('modifyVehicleModal',true)">(Click to Modify)</button>
                            @endif
                        </p>

                        <p class="mt-1 text-sm text-primary-500">Destination :
                        </p>
                        <p class="mt-1 text-sm text-primary-500">Driver :

                            @if ($is_motorpool_head && $request->driver_id != null)
                            <button class="italic underline ml-2" wire:click="$set('modifyDriverModal',true)">(Click to modify)</button>
                            @endif
                        </p>
                        <p class="mt-1 text-sm text-primary-500">Passengers :
                            @foreach ($request->applicants()->get() as $index => $applicant)
                                {{ $applicant->employee_information->full_name }}
                                @if ($index < count($request->applicants()->get()) - 1)
                                    ,
                                @endif
                            @endforeach
                        </p>
                        <p class="mt-1 text-sm text-primary-500">Purpose : </p>
                        <p class="mt-1 text-sm whitespace-pre-line text-primary-500"></p>
                        @if ($is_president && $request->status == 'Pending')
                            <div class="flex justify-between w-full mt-10">
                                <span>&nbsp;</span>
                                <div class="flex space-x-3">
                                    <button class="flex text-sm text-primary-600 hover:text-primary-400" wire:click.prevent="approveRequest({{ $request->id }})">
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="">Approve Vehicle Request</span>
                                    </button>
                                    <button class="flex text-sm text-red-500 hover:text-red-300" wire:click="$set('rejectModal',true)">
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                                        </svg>
                                        <span class="">Reject Vehicle Request</span>
                                    </button>
                                </div>
                            </div>
                        @endif --}}


                        {{-- @if ($is_motorpool_head)
                            @if ($request->driver_id == null && $request->vehicle_id != null)
                                <a class="flex float-right mt-4 mx-2 px-4 py-2 text-sm rounded-full bg-primary-600 text-primary-100 hover:text-primary-100 hover:bg-primary-900 active:ring-primary-700 w-fit active:ring-2 active:ring-offset-2" wire:click="$set('assignDriverModal',true)" target="_blank">
                                    <svg class="w-5 h-auto" class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg>

                                    <span class="pl-2">
                                        Assign Driver
                                </a>
                            @endif --}}

                        {{-- @if ($request->vehicle_id == null)
                        <a class="flex float-right mx-2 mt-4 px-4 py-2 text-sm rounded-full bg-primary-600 text-primary-100 hover:text-primary-100 hover:bg-primary-900 active:ring-primary-700 w-fit active:ring-2 active:ring-offset-2" wire:click="$set('assignVehicleModal',true)" target="_blank">
                            <svg class="w-5 h-auto" class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                            </svg>
                            <span class="pl-2">
                                Assign Vehicle
                        </a>
                    @endif
                    @if ($request->driver_id != null && $request->vehicle_id != null)
                        <a class="flex float-right px-4 py-2 text-sm rounded-full bg-primary-600 text-primary-100 hover:text-primary-100 hover:bg-primary-900 active:ring-primary-700 w-fit active:ring-2 active:ring-offset-2" href="{{ route('motorpool.request.show', $request) }}" target="_blank">

                            <svg class="w-5 h-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="pl-2">
                                Print Driver's Trip Ticket
                            </span>
                        </a>
                    @endif --}}
                    </div>
                </div>
            </div>

            <div class="px-4 py-5 mt-5 bg-white border-b rounded-md border-primary-200 sm:px-6 lg:rounded-none lg:rounded-bl-lg">
                <div class="flex flex-wrap items-center justify-between -mt-4 -ml-4 sm:flex-nowrap">
                    <div class="mt-4 ml-4">
                        <h3 class="text-lg font-medium leading-6 text-primary-900">Signatories</h3>

                        <p class="mt-4 text-sm text-primary-500">Supply: {{$supplyChief->full_name}}</p>
                        <p class="mt-1 text-sm text-primary-500">Approval Status: {{$record->is_approved_supply ? 'Approved' : 'Pending'}}</p>
                        <p class="mt-4 text-sm text-primary-500">Accounting: {{$accountant->full_name}}</p>
                        <p class="mt-1 text-sm text-primary-500">Approval Status: {{$record->is_approved_finance ? 'Approved' : 'Pending'}}</p>
                        {{-- @if ($request->status == 'Approved')
                            <p class="mt-1 text-sm text-primary-500">Date Approved:

                            </p>
                            <p class="mt-1 text-sm text-primary-500">Time Approved:

                            </p>
                        @elseif($request->status == 'Rejected')
                            <p class="mt-1 text-sm text-primary-500">Date Rejected:

                            </p>
                            <p class="mt-1 text-sm text-primary-500">Time Rejected:

                            </p>
                        @endif --}}

                    </div>
                </div>
            </div>
        </div>

        <div class="col-span-1 px-4 py-5 mt-4 overflow-y-auto bg-white border-b rounded-md lg:mt-0 lg:ml-4 border-primary-300 max-h-screen-70 soft-scrollbar">
            <div class="flow-root mt-6">
                <ul class="-my-5 divide-y divide-primary-200" role="list">
                    <div class="flex justify-between w-full">
                        <h3 class="text-lg font-semibold text-primary-600">Timeline</h3>

                    </div>
                    {{-- remarks --}}
                    <div class="p-4">
                        <div class="flow-root">
                            <ul role="list" class="-mb-8">
                                @forelse ($record->wfpRequestTimeline as $timeline)
                                @if ($timeline->activity === 'Pending')
                                <li>
                                    <div class="relative pb-8">
                                        @if (!$loop->last)
                                            <span class="absolute left-5 top-5 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        @endif
                                      <div class="relative flex items-start space-x-3">
                                        <div class="relative">
                                          <img class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-400 ring-8 ring-white" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}">
                                          </span>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                          <div>
                                            <div class="text-sm">
                                              <a href="#" class="font-medium text-gray-900">{{$timeline->user->employee_information->full_name}}</a>
                                            </div>
                                            <p class="mt-0.5 text-sm text-gray-500">Requested: {{Carbon\Carbon::parse($record->created_at)->format('F d, Y h:i A')}}</p>
                                          </div>
                                          <div class="mt-2 text-sm text-gray-700">
                                            <p class="italic">To be forwarded to supply for verification</p>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </li>
                                @elseif($timeline->activity === 'Forwarded to Supply')
                                  <li>
                                    <div class="relative pb-8">
                                        @if (!$loop->last)
                                        <span class="absolute left-5 top-5 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                    @endif
                                      <div class="relative flex items-start space-x-3">
                                        <div>
                                          <div class="relative px-1">
                                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-green-100 ring-8 ring-white">
                                                <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8.689c0-.864.933-1.406 1.683-.977l7.108 4.061a1.125 1.125 0 0 1 0 1.954l-7.108 4.061A1.125 1.125 0 0 1 3 16.811V8.69ZM12.75 8.689c0-.864.933-1.406 1.683-.977l7.108 4.061a1.125 1.125 0 0 1 0 1.954l-7.108 4.061a1.125 1.125 0 0 1-1.683-.977V8.69Z" />
                                                  </svg>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="min-w-0 flex-1 py-1.5">
                                          <div class="text-sm text-gray-500">
                                            <a href="#" class="font-medium text-gray-900">Forwarded to Supply</a>
                                            <p class="mt-0.5 text-sm text-gray-500">Forwarded: {{Carbon\Carbon::parse($timeline->created_at)->format('F d, Y h:i A')}}</p>
                                            <div class="mt-2 text-sm text-gray-700">
                                                <p class="italic">To be assigned with supply code</p>
                                              </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </li>
                                  @elseif($timeline->activity === 'Forward to Accounting')
                                  <li>
                                    <div class="relative pb-8">
                                        @if (!$loop->last)
                                            <span class="absolute left-5 top-5 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        @endif
                                      <div class="relative flex items-start space-x-3">
                                        <div class="relative">
                                          <img class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-400 ring-8 ring-white" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}">
                                          </span>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                          <div>
                                            <div class="text-sm">
                                              <a href="#" class="font-medium text-gray-900">{{$timeline->user->employee_information->full_name}}</a>
                                            </div>
                                            <p class="mt-0.5 text-sm text-gray-500">Assigned: {{Carbon\Carbon::parse($timeline->created_at)->format('F d, Y h:i A')}}</p>
                                          </div>
                                          <div class="mt-2 text-sm text-gray-700">
                                            <p class="italic">Supply code has been assigned and to be forwarded to accounting for verification</p>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </li>
                                  @endif
                                @empty
                                <h3 class="flex justify-between text-sm font-semibold text-primary-800"> Nothing to show</h3>
                                @endforelse

                              {{-- <li>
                                <div class="relative pb-8">
                                  <span class="absolute left-5 top-5 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                  <div class="relative flex items-start space-x-3">
                                    <div>
                                      <div class="relative px-1">
                                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 ring-8 ring-white">
                                          <svg class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-5.5-2.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0ZM10 12a5.99 5.99 0 0 0-4.793 2.39A6.483 6.483 0 0 0 10 16.5a6.483 6.483 0 0 0 4.793-2.11A5.99 5.99 0 0 0 10 12Z" clip-rule="evenodd" />
                                          </svg>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="min-w-0 flex-1 py-1.5">
                                      <div class="text-sm text-gray-500">
                                        <a href="#" class="font-medium text-gray-900">Hilary Mahy</a>
                                        assigned
                                        <a href="#" class="font-medium text-gray-900">Kristin Watson</a>
                                        <span class="whitespace-nowrap">2d ago</span>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </li>
                              <li>
                                <div class="relative pb-8">
                                  <div class="relative flex items-start space-x-3">
                                    <div>
                                      <div class="relative px-1">
                                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 ring-8 ring-white">
                                          <svg class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                                            <path fill-rule="evenodd" d="M4.5 2A2.5 2.5 0 0 0 2 4.5v3.879a2.5 2.5 0 0 0 .732 1.767l7.5 7.5a2.5 2.5 0 0 0 3.536 0l3.878-3.878a2.5 2.5 0 0 0 0-3.536l-7.5-7.5A2.5 2.5 0 0 0 8.38 2H4.5ZM5 6a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
                                          </svg>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="min-w-0 flex-1 py-0">
                                      <div class="text-sm leading-8 text-gray-500">
                                        <span class="mr-0.5">
                                          <a href="#" class="font-medium text-gray-900">Hilary Mahy</a>
                                          added tags
                                        </span>
                                        <span class="mr-0.5">
                                          <a href="#" class="inline-flex items-center gap-x-1.5 rounded-full px-2 py-1 text-xs font-medium text-gray-900 ring-1 ring-inset ring-gray-200">
                                            <svg class="h-1.5 w-1.5 fill-red-500" viewBox="0 0 6 6" aria-hidden="true">
                                              <circle cx="3" cy="3" r="3" />
                                            </svg>
                                            Bug
                                          </a>
                                          <a href="#" class="inline-flex items-center gap-x-1.5 rounded-full px-2 py-1 text-xs font-medium text-gray-900 ring-1 ring-inset ring-gray-200">
                                            <svg class="h-1.5 w-1.5 fill-indigo-500" viewBox="0 0 6 6" aria-hidden="true">
                                              <circle cx="3" cy="3" r="3" />
                                            </svg>
                                            Accessibility
                                          </a>
                                        </span>
                                        <span class="whitespace-nowrap">6h ago</span>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </li> --}}
                            </ul>
                          </div>
                    </div>
                    {{-- end remarks --}}

{{--
                    <li class="py-5">
                        <div class="relative focus-within:ring-2 focus-within:ring-indigo-500">
                            <h3 class="flex justify-between text-sm font-semibold text-primary-800">
                                <span class="absolute inset-0" aria-hidden="true"></span>

                                    <span class="flex uppercase"></span>

                    <li class="py-5">
                        <div class="relative focus-within:ring-2 focus-within:ring-indigo-500">
                            <h3 class="text-sm italic font-light text-primary-300">
                                <span class="absolute inset-0" aria-hidden="true"></span>
                                Nothing to show
                            </h3>
                        </div>
                    </li> --}}
                    {{-- @endif --}}
                    </h3>
                    {{-- <p class="mt-1 text-sm text-primary-600 line-clamp-2">
                    </p> --}}
            </div>
            </li>
            </ul>
        </div>
    </div>
    <div class="flex justify-end">
        @if ($record->status == 'Pending')
        <a href="{{route('wfp.request-supply-list')}}" class="mr-1 px-3 py-2.5  bg-white rounded-md font-normal capitalize text-primary-600 text-sm">Cancel</a>
        <button wire:click="forwardToSupply" class="mr-1 px-3 py-2.5  bg-primary-600 rounded-md font-normal capitalize text-white text-sm">Forward to Supply</button>
        @elseif($record->status == 'Forwarded to Supply' && !$isSupplyChief)
        <a href="{{route('wfp.request-supply-list')}}" class="mr-1 px-3 py-2.5  bg-white rounded-md font-normal capitalize text-primary-600 text-sm">Cancel</a>
        @elseif($isSupplyChief && $record->supply_code == null)
        <a href="{{route('wfp.supply-requested-suppluies')}}" class="mr-1 px-3 py-2.5  bg-white rounded-md font-normal capitalize text-primary-600 text-sm">Cancel</a>
        <button class="mr-1 px-3 py-2.5  bg-yellow-600 rounded-md font-normal capitalize text-white text-sm">Modify Request</button>
        <button class="mr-1 px-3 py-2.5  bg-red-600 rounded-md font-normal capitalize text-white text-sm">Reject Request</button>
        @elseif($isSupplyChief && $record->supply_code != null)
        <a href="{{route('wfp.supply-requested-suppluies')}}" class="mr-1 px-3 py-2.5  bg-white rounded-md font-normal capitalize text-primary-600 text-sm">Cancel</a>
        <button wire:click="forwardToAccounting" class="mr-1 px-3 py-2.5  bg-primary-600 rounded-md font-normal capitalize text-white text-sm">Forward to Accounting</button>
        @endif
    </div>



    <x-modal.card title="Asssign Supply Code" align="center" blur wire:model.defer="assignSupplyCode">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            <div class="col-span-1 px-8 sm:col-span-2">
                <label for="supply_code" class="block text-sm font-medium leading-6 text-gray-900">Supply Code</label>
                <input wire:model="supply_code" id="supply_code" name="supply_code" type="text" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>
        </div>

        <x-slot name="footer">
            <div class="flex justify-end gap-x-4">
                <div class="flex">
                    <x-button flat label="Cancel" x-on:click="close" />
                    <x-button primary label="Save" spinner="updateSupplyCode" wire:click="updateSupplyCode" />
                </div>
            </div>
        </x-slot>
    </x-modal.card>

</div>

</div>
