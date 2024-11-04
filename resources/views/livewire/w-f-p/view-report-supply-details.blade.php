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
                        <p class="mt-1 text-sm text-primary-500">UACS Code: {{$record->supply->categoryItems->uacs_code}}</p>
                        <p class="mt-1 text-sm text-primary-500">Account Title: {{$record->supply->categoryItems->name}}</p>
                        <p class="mt-1 text-sm text-primary-500">Title Group: {{$record->supply->categoryGroups->name}}</p>
                        <p class="mt-1 text-sm text-primary-500">UOM: {{$record->supply->uom}}</p>
                        <p class="mt-1 text-sm text-primary-500">Unit Cost : &#8369; {{ number_format($record->supply->unit_cost, 2) }}</p>
                        <p class="mt-1 text-sm text-primary-500">PPMP : {{$record->supply->is_ppmp ? 'Yes' : 'No'}}</p>
                        <p class="mt-1 text-sm text-primary-500">Date Reported : {{Carbon\Carbon::parse($record->created_at)->format('F d, Y h:i A')}}</p>
                    </div>
                </div>
                @php
                $isAccountant = auth()->user()->employee_information->position_id == 15 && auth()->user()->employee_information->office_id == 3;
                $isFinance = auth()->user()->employee_information->office_id == 25 && (auth()->user()->employee_information->position_id == 12 || auth()->user()->employee_information->position_id == 38);
                @endphp
                @if ($isFinance && $record->status == 'Pending')
                <div class="flex justify-end">
                    <button type="button" wire:click="modifySupply" class="mr-1 px-3 py-2.5  bg-yellow-600 rounded-md font-normal capitalize text-white text-sm">Modify</button>
                </div>
                @endif


            </div>

            <div class="px-4 py-5 mt-5 bg-white border-b rounded-md border-primary-200 sm:px-6 lg:rounded-none lg:rounded-bl-lg">
                <div class="flex flex-wrap items-center justify-between -mt-4 -ml-4 sm:flex-nowrap">
                    <div class="mt-4 ml-4">
                        <h3 class="text-lg font-medium leading-6 text-primary-900">Status</h3>

                        <p class="mt-4 text-sm text-primary-500">Pending</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-span-1 px-4 py-5 mt-4 overflow-y-auto bg-white border-b rounded-md lg:mt-0 lg:ml-4 border-primary-300 max-h-screen-70 soft-scrollbar">
            <div class="flow-root mt-6">
                <ul class="-my-5 divide-y divide-primary-200" role="list">
                    <div class="flex justify-between w-full">
                        <h3 class="text-lg font-semibold text-primary-600">Comments</h3>

                    </div>
                    {{-- remarks --}}
                    <div class="p-4">
                        <div class="mt-4">
                            <div class="space-y-4">
                                <p class="text-md font-semibold text-primary-600">{{ $record->note }}</p>
                                    @foreach ($record->replies as $reply)
                                    <div class="p-4 bg-gray-100 rounded-md">
                                        <div class="mt-2 space-y-2">
                                            <div class="ml-4 p-2 bg-white rounded-md">
                                                <div class="flex justify-between">
                                                    <span class="text-sm font-semibold text-gray-600">{{$reply->user->employee_information->full_name}}</span>
                                                    <span class="text-xs text-gray-600">{{Carbon\Carbon::parse($reply->created_at)->format('F d, Y h:i A')}}</span>
                                                </div>
                                                <p class="mt-1 text-sm text-gray-900">{{$reply->content}}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                            </div>
                            @if ($isFinance && $record->status == 'Pending')
                            <div class="mt-4">
                                <textarea wire:model="newReply" class="w-full p-2 border rounded-md" placeholder="Add a reply..."></textarea>
                                @error('newReply')
                                    <span class="text-sm text-red-600">{{$message}}</span>
                                @enderror
                                <x-button spinner="addReply" emerald label="Reply"
                                    x-on:confirm="{
                                        title : 'Are you sure you want to add this reply?',
                                        icon: 'warning',
                                        method: 'addReply',
                                        params: {{$record->id}}
                                    }" />
                            </div>
                            @else
                            <div class="mt-4">
                                <div class="text-center italic text-gray-500">
                                    <span>--- This report is resolved and replies are disabled ---</span>
                                </div>
                            @endif
                        </div>
        </div>
            </li>
            </ul>
        </div>
        <div class="flex justify-end space-x-3 mt-5 ">
            <a href="{{route('wfp.reported-supply-list')}}" class="mr-1 px-3 py-2.5  bg-gray-200 rounded-md font-normal capitalize text-primary-600 text-sm">Back</a>
            @if ($isFinance && $record->status == 'Pending')
            <button wire:click="resolveReport" type="button" class="mr-1 px-3 py-2.5  bg-green-500 rounded-md font-normal capitalize text-white text-sm">Resolve this report</button>
            @endif
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



    <x-modal.card title="Modify Supply Details" align="center" blur wire:model.defer="modifySupplyModal">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            <div class="col-span-1 px-2 sm:col-span-2">
                <label for="supply_code" class="block text-sm font-medium leading-6 text-gray-900">Particulars</label>
                <textarea id="about" wire:model="supply_particular" name="about" rows="4" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
            </div>
            <div class="col-span-1 px-2 sm:col-span-2">
                <label for="supply_codes" class="block text-sm font-medium leading-6 text-gray-900">Specification</label>
                <input wire:model="supply_specification" id="supply_specification" name="supply_codes" type="text" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>
            <div class="col-span-1 px-2 sm:col-span-2">
                <label for="supplies_uom" class="block text-sm font-medium leading-6 text-gray-900">Account Title</label>
                <div class="mt-2">
                    <select wire:model="supply_account_title" id="supplies_uom" name="supplies_uom" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-full sm:text-sm sm:leading-6">
                        <option value="">Select One</option>
                        @foreach ($account_titles as $item)
                        <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-span-1 px-2 sm:col-span-2">
                <label for="supplies_uom" class="block text-sm font-medium leading-6 text-gray-900">Title Group</label>
                <div class="mt-2">
                    <select wire:model="supply_title_group" id="supplies_uom" name="supplies_uom" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-full sm:text-sm sm:leading-6">
                        <option value="">Select One</option>
                        @foreach ($title_groups as $item)
                        <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-span-1 px-2 sm:col-span-2">
                <label for="supply_codes" class="block text-sm font-medium leading-6 text-gray-900">UOM</label>
                <input wire:model="supply_uom" id="supply_codes" name="supply_codes" type="text" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>
            <div class="col-span-1 px-2 sm:col-span-2">
                <label for="supply_codes" class="block text-sm font-medium leading-6 text-gray-900">Unit Cost</label>
                <input wire:model="supply_unit_cost" id="supply_codes" name="supply_codes" type="text" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>
            </div>

        <x-slot name="footer">
            <div class="flex justify-end gap-x-4">
                <div class="flex">
                    <x-button flat label="Cancel" x-on:click="close" />
                    <x-button primary label="Save" spinner="updateSupply" wire:click="updateSupply" />
                </div>
            </div>
        </x-slot>
    </x-modal.card>


</div>

</div>
