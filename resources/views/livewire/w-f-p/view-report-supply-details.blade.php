<div class="">
    <div class="grid grid-cols-1 lg:grid-cols-3">
        <div class="flex-row col-span-1 lg:col-span-2">
            <div class="px-4 py-5 bg-white border-b rounded-md border-primary-200 sm:px-6 lg:rounded-none lg:rounded-tl-lg">
                <div class="flex flex-wrap items-center justify-between -mt-4 -ml-4 sm:flex-nowrap">
                    <div class="w-full mt-4 ml-4">
                        <h3 class="text-lg font-medium leading-6 text-primary-900">Reported Supply Details</h3>
                        <p class="mt-4 text-sm text-primary-500">Reported By: {{$record->user->employee_information->full_name}}</p>
                        <p class="mt-1 text-sm text-primary-500"> Particular:
                            <div class="text-sm text-primary-500 italic ml-5">
                             {!! $record->supply->particulars !!}
                            </div>
                        </p>
                        <p class="mt-1 text-sm text-primary-500">Specifications: {{$record->supply->specifications}}</p>
                        <p class="mt-1 text-sm text-primary-500">UOM: {{$record->supply->uom}}</p>
                        <p class="mt-1 text-sm text-primary-500">Unit Cost : &#8369; {{ number_format($record->supply->unit_cost, 2) }}</p>
                        <p class="mt-1 text-sm text-primary-500">PPMP : {{$record->supply->is_ppmp ? 'Yes' : 'No'}}</p>
                        <p class="mt-1 text-sm text-primary-500">Date Reported : {{Carbon\Carbon::parse($record->created_at)->format('F d, Y h:i A')}}</p>
                        {{-- <p class="mt-4 text-sm text-primary-500 ">Supply Code :
                            @if (($record->status == 'Pending' || $record->status == 'Forwarded to Supply' || $record->status == 'Request Modification' || $record->status == 'Request Rejected by Supply') && !$isSupplyChief && $record->supply_code == null)
                            <span class="italic underline ml-2 text-red-600">To be added by supply</span>
                            @elseif(($record->status == 'Pending' || $record->status == 'Forwarded to Supply') && $isSupplyChief && $record->supply_code == null)
                            <button class="italic underline ml-2 font-semibold" wire:click="$set('assignSupplyCode',true)">(Assign Supply Code)</button>
                            @else
                            <span class="mt-1 text-sm text-primary-500">{{$record->supply_code}}</span>
                            @if ($isSupplyChief && $record->status == 'Accounting Request Modification')
                            <button class="italic underline ml-2" wire:click="openModifySupplyCodeModal">(Click to modify)</button>
                            @endif
                            @endif
                        </p>
                        @if (($record->status == 'Forwarded to Accounting' || $record->status == 'Pending' || $record->status == 'Forwarded to Supply' || $record->status == 'Request Modification' || $record->status == 'Accounting Request Modification' || $record->status = 'Supply Code Assigned') && !$isAccountant && $record->category_item_id == null)
                        <p class="mt-4 text-sm text-primary-500 ">Budget Category : <span class="italic underline ml-2 text-red-600">To be added by accounting</span></p>
                        <p class="mt-1 text-sm text-primary-500 ">UACS Code : <span class="italic underline ml-2 text-red-600">To be added by accounting</span></p>
                        <p class="mt-1 text-sm text-primary-500 ">Account Title : <span class="italic underline ml-2 text-red-600">To be added by accounting</span></p>
                        <p class="mt-1 text-sm text-primary-500 ">Title Group : <span class="italic underline ml-2 text-red-600">To be added by accounting</span></p>
                        @elseif(($record->status == 'Forwarded to Accounting') && $isAccountant && $record->category_item_id == null)
                        <p class="mt-4 text-sm text-primary-500 ">Budget Category : <span class="italic underline ml-2 font-semibold">Not yet assigned</span>
                        <p class="mt-1 text-sm text-primary-500 ">UACS Code : <span class="italic underline ml-2 font-semibold">Not yet assigned</span>
                        <p class="mt-1 text-sm text-primary-500 ">Account Title : <span class="italic underline ml-2 font-semibold">Not yet assigned</span>
                        <p class="mt-1 text-sm text-primary-500 ">Title Group : <span class="italic underline ml-2 font-semibold">Not yet assigned</span>
                        <div class="flex justify-end">
                            <button class="px-4 py-2 text-sm font-semibold text-white bg-primary-600 rounded-md hover:bg-primary-500" wire:click="accountingAssign">Assign</button>
                        </div>
                        @elseif(($record->status == 'Accounting Assigned Data') && $isAccountant && $record->category_item_id != null)
                            <p class="mt-4 text-sm text-primary-500 ">Budget Category : {{$record->categoryItems->budgetCategory->name}}</p>
                            <p class="mt-1 text-sm text-primary-500 ">UACS Code : {{$record->categoryItems->uacs_code}}</p>
                            <p class="mt-1 text-sm text-primary-500 ">Account Title : {{$record->categoryItems->name}}</p>
                            <p class="mt-1 text-sm text-primary-500 ">Title Group : {{$record->categoryGroups->name}}</p>
                        @endif --}}
                    </div>
                </div>
            </div>

            <div class="px-4 py-5 mt-5 bg-white border-b rounded-md border-primary-200 sm:px-6 lg:rounded-none lg:rounded-bl-lg">
                <div class="flex flex-wrap items-center justify-between -mt-4 -ml-4 sm:flex-nowrap">
                    <div class="mt-4 ml-4">
                        <h3 class="text-lg font-medium leading-6 text-primary-900">Signatories</h3>

                        {{-- <p class="mt-4 text-sm text-primary-500">Supply: {{$supplyChief->full_name}}</p>
                        <p class="mt-1 text-sm text-primary-500">Approval Status: {{$record->is_approved_supply ? 'Approved' : 'Pending'}}</p>
                        <p class="mt-4 text-sm text-primary-500">Accounting: {{$accountant->full_name}}</p>
                        <p class="mt-1 text-sm text-primary-500">Approval Status: {{$record->is_approved_finance ? 'Approved' : 'Pending'}}</p> --}}
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
                        {{-- <div class="flow-root">
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
                                          <img class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-400 ring-8 ring-white" src="{{ $timeline->user->profile_photo_url }}" alt="{{ $timeline->user->name }}">
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
                                          <img class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-400 ring-8 ring-white" src="{{ $timeline->user->profile_photo_url }}" alt="{{ $timeline->user->name }}">
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
                                  @elseif($timeline->activity === 'Request Modification')
                                  <li>
                                    <div class="relative pb-8">
                                        @if (!$loop->last)
                                            <span class="absolute left-5 top-5 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        @endif
                                      <div class="relative flex items-start space-x-3">
                                        <div>
                                            <div class="relative px-1">
                                              <div class="flex h-8 w-8 items-center justify-center rounded-full bg-yellow-100 ring-8 ring-white">
                                                    <svg class="h-5 w-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                      </svg>

                                              </div>
                                            </div>
                                          </div>
                                        <div class="min-w-0 flex-1">
                                          <div>
                                            <div class="text-sm">
                                              <a href="#" class="font-medium text-gray-900">{{$timeline->activity}} by {{$timeline->user->employee_information->full_name}}</a>
                                            </div>
                                            <p class="mt-0.5 text-sm text-gray-500">Forwarded: {{Carbon\Carbon::parse($timeline->created_at)->format('F d, Y h:i A')}}</p>
                                          </div>
                                          <div class="mt-2 text-sm text-gray-700">
                                            <p class="italic">Note: {{$timeline->remarks}}</p>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </li>
                                  @elseif($timeline->activity === 'Request Rejected by Supply')
                                  <li>
                                    <div class="relative pb-8">
                                        @if (!$loop->last)
                                            <span class="absolute left-5 top-5 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        @endif
                                      <div class="relative flex items-start space-x-3">
                                        <div>
                                            <div class="relative px-1">
                                              <div class="flex h-8 w-8 items-center justify-center rounded-full bg-red-100 ring-8 ring-white">
                                                      <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                                      </svg>
                                              </div>
                                            </div>
                                          </div>
                                        <div class="min-w-0 flex-1">
                                          <div>
                                            <div class="text-sm">
                                              <a href="#" class="font-medium text-gray-900">Request Rejected by {{$timeline->user->employee_information->full_name}}</a>
                                            </div>
                                            <p class="mt-0.5 text-sm text-gray-500">Forwarded: {{Carbon\Carbon::parse($timeline->created_at)->format('F d, Y h:i A')}}</p>
                                          </div>
                                          <div class="mt-2 text-sm text-gray-700">
                                            <p class="italic">Note: {{$timeline->remarks}}</p>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </li>
                                  @elseif($timeline->activity === 'Forwarded to Accounting')
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
                                            <a href="#" class="font-medium text-gray-900">Forwarded to Accounting</a>
                                            <p class="mt-0.5 text-sm text-gray-500">Forwarded: {{Carbon\Carbon::parse($timeline->created_at)->format('F d, Y h:i A')}}</p>
                                            <div class="mt-2 text-sm text-gray-700">
                                                <p class="italic">To be assigned with UACS Code, Account Title, Title Group</p>
                                              </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </li>
                                  @elseif($timeline->activity === 'Accounting Assigned Data')
                                  <li>
                                    <div class="relative pb-8">
                                        @if (!$loop->last)
                                            <span class="absolute left-5 top-5 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        @endif
                                      <div class="relative flex items-start space-x-3">
                                        <div class="relative">
                                          <img class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-400 ring-8 ring-white" src="{{ $timeline->user->profile_photo_url }}" alt="{{ $timeline->user->name }}">
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
                                            <p class="italic">UACS Code, Budget Category, Account Title and Title Group is assigned by accounting and can be added to WFP creation.</p>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </li>
                                  @elseif($timeline->activity === 'Accounting Request Modification')
                                  <li>
                                    <div class="relative pb-8">
                                        @if (!$loop->last)
                                            <span class="absolute left-5 top-5 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        @endif
                                      <div class="relative flex items-start space-x-3">
                                        <div>
                                            <div class="relative px-1">
                                              <div class="flex h-8 w-8 items-center justify-center rounded-full bg-yellow-100 ring-8 ring-white">
                                                    <svg class="h-5 w-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                      </svg>

                                              </div>
                                            </div>
                                          </div>
                                        <div class="min-w-0 flex-1">
                                          <div>
                                            <div class="text-sm">
                                              <a href="#" class="font-medium text-gray-900">Request Modification by {{$timeline->user->employee_information->full_name}}</a>
                                            </div>
                                            <p class="mt-0.5 text-sm text-gray-500">Forwarded: {{Carbon\Carbon::parse($timeline->created_at)->format('F d, Y h:i A')}}</p>
                                          </div>
                                          <div class="mt-2 text-sm text-gray-700">
                                            <p class="italic">Note: {{$timeline->remarks}}</p>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </li>
                                  @elseif($timeline->activity === 'Request Rejected by Accounting')
                                  <li>
                                    <div class="relative pb-8">
                                        @if (!$loop->last)
                                            <span class="absolute left-5 top-5 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        @endif
                                      <div class="relative flex items-start space-x-3">
                                        <div>
                                            <div class="relative px-1">
                                              <div class="flex h-8 w-8 items-center justify-center rounded-full bg-red-100 ring-8 ring-white">
                                                      <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                                      </svg>
                                              </div>
                                            </div>
                                          </div>
                                        <div class="min-w-0 flex-1">
                                          <div>
                                            <div class="text-sm">
                                              <a href="#" class="font-medium text-gray-900">Request Rejected by {{$timeline->user->employee_information->full_name}}</a>
                                            </div>
                                            <p class="mt-0.5 text-sm text-gray-500">Forwarded: {{Carbon\Carbon::parse($timeline->created_at)->format('F d, Y h:i A')}}</p>
                                          </div>
                                          <div class="mt-2 text-sm text-gray-700">
                                            <p class="italic">Note: {{$timeline->remarks}}</p>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </li>
                                  @endif
                                @empty
                                <h3 class="flex justify-between text-sm font-semibold text-primary-800"> Nothing to show</h3>
                                @endforelse
            </div> --}}
        </div>
            </li>
            </ul>
        </div>
    </div>
    <div class="flex justify-end space-x-3 mt-5 ">
        {{-- @if ($record->status == 'Pending' || $record->status == 'Request Modification')
        <a href="{{route('wfp.request-supply-list')}}" class="mr-1 px-3 py-2.5  bg-white rounded-md font-normal capitalize text-primary-600 text-sm">Cancel</a>
        <button wire:click="forwardToSupply" class="mr-1 px-3 py-2.5  bg-primary-600 rounded-md font-normal capitalize text-white text-sm">Forward to Supply</button>
        @elseif(($record->status == 'Forwarded to Supply' || $record->status == 'Request Rejected by Supply' || $record->status == 'Forwarded to Accounting' || $record->status == 'Accounting Assigned Data' || $record->status == 'Accounting Request Modification' || $record->status == 'Request Rejected by Accounting' || $record->status == 'Supply Code Assigned') && !$isSupplyChief && !$isAccountant)
        <a href="{{route('wfp.request-supply-list')}}" class="mr-1 px-3 py-2.5  bg-white rounded-md font-normal capitalize text-primary-600 text-sm">Cancel</a>
        @elseif($isSupplyChief && $record->supply_code == null)
        <a href="{{route('wfp.supply-requested-suppluies')}}" class="mr-1 px-3 py-2.5  bg-white rounded-md font-normal capitalize text-primary-600 text-sm">Cancel</a>
        <button wire:click="modifyRequest" class="mr-1 px-3 py-2.5  bg-yellow-600 rounded-md font-normal capitalize text-white text-sm">Modify Request</button>
        <button wire:click="rejectRequest" class="mr-1 px-3 py-2.5  bg-red-600 rounded-md font-normal capitalize text-white text-sm">Reject Request</button>
        @elseif(($record->status == 'Forwarded to Supply' || $record->status == 'Supply Code Assigned') && $isSupplyChief && $record->supply_code != null)
        <a href="{{route('wfp.supply-requested-suppluies')}}" class="mr-1 px-3 py-2.5  bg-white rounded-md font-normal capitalize text-primary-600 text-sm">Cancel</a>
        <button wire:click="forwardToAccounting" class="mr-1 px-3 py-2.5  bg-primary-600 rounded-md font-normal capitalize text-white text-sm">Forward to Accounting</button>
        @elseif(($record->status == 'Forwarded to Accounting' || $record->status == 'Accounting Request Modification') && $isSupplyChief && $record->supply_code != null)
        <a href="{{route('wfp.supply-requested-suppluies')}}" class="mr-1 px-3 py-2.5  bg-white rounded-md font-normal capitalize text-primary-600 text-sm">Cancel</a>
        @elseif(($record->status == 'Forwarded to Accounting') && $isAccountant)
        <button wire:click="$set('forwardRequestToSupply', true)" class="mr-1 px-3 py-2.5  bg-yellow-600 rounded-md font-normal capitalize text-white text-sm">Forward Request to Supply</button>
        <button wire:click="rejectRequestAccountingModal" class="mr-1 px-3 py-2.5  bg-red-600 rounded-md font-normal capitalize text-white text-sm">Reject Request</button>
        @elseif(($record->status == 'Accounting Assigned Data') && $isAccountant)
        <a href="{{route('wfp.accounting-requested-suppluies')}}" class="mr-1 px-3 py-2.5  bg-white rounded-md font-normal capitalize text-primary-600 text-sm">Cancel</a>
        @endif --}}
    </div>


{{--
    <x-modal.card title="Assign Supply Code" align="center" blur wire:model.defer="assignSupplyCode">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            <div class="col-span-1 px-2 sm:col-span-2">
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
    </x-modal.card> --}}


</div>

</div>
