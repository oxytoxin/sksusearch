<div class="">
    <div class="grid grid-cols-1 lg:grid-cols-3">
        <div class="flex-row col-span-1 lg:col-span-2">
            <div class="px-4 py-5 bg-white border-b rounded-md border-primary-200 sm:px-6 lg:rounded-none lg:rounded-tl-lg">
                <div class="flex flex-wrap items-center justify-between -mt-4 -ml-4 sm:flex-nowrap">
                    <div class="w-full mt-4 ml-4">
                        <h3 class="text-lg font-medium leading-6 text-primary-900">Vehicle Request Details</h3>
                        <p class="mt-4 text-sm text-primary-500">Requisitioner: {{$request->requested_by->name}}</p>
                        <p class="mt-1 text-sm text-primary-500">Travel Order : {{$request->travel_order_id == null ? 'No' : 'Yes'}}</p>
                        <p class="mt-1 text-sm text-primary-500">Date : {{\Carbon\Carbon::parse($request->date_of_travel_from)->format('F d, Y')}}
                           to {{\Carbon\Carbon::parse($request->date_of_travel_to)->format('F d, Y')}}
                            </p>
                        <p class="mt-1 text-sm text-primary-500">Time : 
                            {{($request->time_start == null || $request->time_end == null) ? 'Not yet set' :
                                 \Carbon\Carbon::parse($request->time_start)->format('h: i A') . ' to ' . \Carbon\Carbon::parse($request->time_end)->format('h: i A') }}
                            </p>
                        <p class="mt-1 text-sm text-primary-500">Vehicle : 
                            {{$request->vehicle_id == null ? 'Not yet set' : $request->vehicle->model }}
                            </p>                            
                                <p class="mt-1 text-sm text-primary-500">Destination : {{ $request->other_details != null ? $request->other_details . ', ' : '' }}
                                    {{ $request->philippine_city->city_municipality_description }},
                                    {{ $request->philippine_province->province_description }},
                                    {{ $request->philippine_region->region_description }}
                                   </p>
                        <p class="mt-1 text-sm text-primary-500">Driver : 
                            {{$request->driver_id == null ? 'Not yet set' : $request->driver->full_name }}
                            </p>    
                        <p class="mt-1 text-sm text-primary-500">Passengers :  
                            @foreach($request->applicants()->get() as $index => $applicant)
                            {{ $applicant->employee_information->full_name }}
                            @if($index < count($request->applicants()->get()) - 1)
                                ,
                            @endif
                            @endforeach</p>       
                        <p class="mt-1 text-sm text-primary-500">Purpose :  {{ $request->purpose }}</p>
                        <p class="mt-1 text-sm whitespace-pre-line text-primary-500"></p>
                        @if(auth()->user()->id == 64 && $request->status == 'Pending')
                        <div class="flex justify-between w-full mt-10">
                                    <span>&nbsp;</span>
                                    <div class="flex space-x-3">
                                        <button class="flex text-sm text-primary-600 hover:text-primary-400" wire:click.prevent="approveRequest({{$request->id}})">
                                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                      d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                                      clip-rule="evenodd" />
                                            </svg>
                                            <span class="">Approve Vehicle Request</span>
                                        </button>
                                        <button class="flex text-sm text-red-500 hover:text-red-300" wire:click="$set('rejectModal',true)">
                                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path
                                                      d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                                            </svg>
                                            <span class="">Reject Vehicle Request</span>
                                        </button>
                                    </div>
                        </div>
                        @endif
                        @php
                        $motorpool_head = App\Models\Office::where('name', 'like', '%Motorpool%')->first();
                        @endphp

                        @if ($motorpool_head?->head_id == auth()->user()->id || $motorpool_head?->admin_user_id == auth()->user()->id)
                            @if($request->driver_id == null && $request->vehicle_id != null)
                            <a class="flex float-right mt-4 mx-2 px-4 py-2 text-sm rounded-full bg-primary-600 text-primary-100 hover:text-primary-100 hover:bg-primary-900 active:ring-primary-700 w-fit active:ring-2 active:ring-offset-2"
                                wire:click="$set('assignDriverModal',true)" target="_blank">
                                <svg class="w-5 h-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>

                                <span class="pl-2">
                                    Assign Driver
                            </a>
                            @endif
                            @if($request->vehicle_id == null)
                            <a class="flex float-right mx-2 mt-4 px-4 py-2 text-sm rounded-full bg-primary-600 text-primary-100 hover:text-primary-100 hover:bg-primary-900 active:ring-primary-700 w-fit active:ring-2 active:ring-offset-2"
                            wire:click="$set('assignVehicleModal',true)" target="_blank">
                               <svg class="w-5 h-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                               </svg>
                                <span class="pl-2">
                                    Assign Vehicle
                            </a>
                            @endif
                            @if($request->driver_id != null && $request->vehicle_id != null)
                            <a class="flex float-right px-4 py-2 text-sm rounded-full bg-primary-600 text-primary-100 hover:text-primary-100 hover:bg-primary-900 active:ring-primary-700 w-fit active:ring-2 active:ring-offset-2"
                               href="{{route('motorpool.request.show', $request)}}" target="_blank">
                               
                                <svg class="w-5 h-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="pl-2">
                                    Print Driver's Trip Ticket
                                </span>
                            </a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <div class="px-4 py-5 mt-5 bg-white border-b rounded-md border-primary-200 sm:px-6 lg:rounded-none lg:rounded-bl-lg">
                <div class="flex flex-wrap items-center justify-between -mt-4 -ml-4 sm:flex-nowrap">
                    <div class="mt-4 ml-4">
                        <h3 class="text-lg font-medium leading-6 text-primary-900">Status</h3>
                      
                            <p class="mt-4 text-sm text-primary-500">Signatory: SAMSON L. MOLAO, EdD 
                               </p>
                            <p class="mt-1 text-sm text-primary-500">Approval Status: {{$request->status}}
                               </p>
                            @if($request->status == 'Approved')
                            <p class="mt-1 text-sm text-primary-500">Date Approved:
                                {{\Carbon\Carbon::parse($request->approved_at)->format('F d, Y')}}
                            </p>
                            <p class="mt-1 text-sm text-primary-500">Time Approved:
                                {{\Carbon\Carbon::parse($request->approved_at)->format('h: i A')}}
                            </p>
                            @elseif($request->status == 'Rejected')
                            <p class="mt-1 text-sm text-primary-500">Date Rejected:
                                {{\Carbon\Carbon::parse($request->rejected_at)->format('F d, Y')}}
                                </p>
                                <p class="mt-1 text-sm text-primary-500">Time Rejected:
                                {{\Carbon\Carbon::parse($request->rejected_at)->format('h: i A')}}
                                </p>
                            @endif

                     

                    </div>
                </div>
            </div>
        </div>

        <div class="col-span-1 px-4 py-5 mt-4 overflow-y-auto bg-white border-b rounded-md lg:mt-0 lg:ml-4 border-primary-300 max-h-screen-70 soft-scrollbar">
            <div class="flow-root mt-6">
                <ul class="-my-5 divide-y divide-primary-200" role="list">
                    <div class="flex justify-between w-full">
                        <h3 class="text-lg font-semibold text-primary-600">Remarks</h3>

                    </div>

                  
                        <li class="py-5">
                            <div class="relative focus-within:ring-2 focus-within:ring-indigo-500">
                                <h3 class="flex justify-between text-sm font-semibold text-primary-800">
                                    <span class="absolute inset-0" aria-hidden="true"></span>
                                    @if($request->remarks != null)
                                    <span class="flex uppercase">{{$request->remarks}}</span>
                                    @else
                                    <li class="py-5">
                            <div class="relative focus-within:ring-2 focus-within:ring-indigo-500">
                                <h3 class="text-sm italic font-light text-primary-300">
                                    <span class="absolute inset-0" aria-hidden="true"></span>
                                    Nothing to show
                                </h3>
                            </div>
                            </li>
                                    @endif
                                </h3>
                                <p class="mt-1 text-sm text-primary-600 line-clamp-2">
                                   </p>
                            </div>
                        </li>
                   
                      
                  

                </ul>
            </div>
        </div>

        <x-modal.card align="center" title="Reject Vehicle Request" blur wire:model.defer="rejectModal">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    
            <div class="col-span-1 sm:col-span-2">
                <x-textarea label="Remarks" placeholder="Reason for rejection..." wire:model="remarks" />
            </div>
        </div>
    
        <x-slot name="footer">
            <div class="flex justify-end gap-x-4">    
                <div class="flex">
                    <x-button flat label="Cancel" x-on:click="close" />
                    <x-button primary label="Save" wire:click="rejectRequest({{$request->id}})" />
                </div>
            </div>
        </x-slot>
        </x-modal.card>

        <x-modal.card align="center" title="Assign Driver" blur wire:model.defer="assignDriverModal">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    
            <div class="col-span-1 sm:col-span-2">
            <x-native-select label="Driver" wire:model="driverss">
                <option>Select Driver</option>
                @foreach($driver as $drive)
                <option value="{{$drive->id}}">{{$drive->full_name}}</option>
                @endforeach
            </x-native-select>
            </div>
        </div>
    
        <x-slot name="footer">
            <div class="flex justify-end gap-x-4">    
                <div class="flex">
                    <x-button flat label="Cancel" x-on:click="close" />
                    <x-button primary label="Save" wire:click="assignDriver({{$request->id}})" />
                </div>
            </div>
        </x-slot>
        </x-modal.card>

        <x-modal.card align="center" title="Assign Vehicle" blur wire:model.defer="assignVehicleModal">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    
            <div class="col-span-1 px-8 sm:col-span-2">
            <x-native-select label="Vehicle" wire:model="vehicless">
                <option>Select Vehicle</option>
                @foreach($vehicles as $vehicle)
                <option value="{{$vehicle->id}}">{{$vehicle->model}}</option>
                @endforeach
            </x-native-select>
            <div class="grid grid-cols-2 p-4 gap-14">
            <x-time-picker class="col-span-1" label="Time Start" placeholder="12:00 AM" wire:model.defer="time_start"/>
            <x-time-picker class="col-span-1" label="Time End" placeholder="12:00 AM" wire:model.defer="time_end"/>
            </div>                
            </div>
            </div>
    
        <x-slot name="footer">
            <div class="flex justify-end gap-x-4">    
                <div class="flex">
                    <x-button flat label="Cancel" x-on:click="close" />
                    <x-button primary label="Save" spinner="assignVehicle({{$request->id}})" wire:click="assignVehicle({{$request->id}})" />
                </div>
            </div>
        </x-slot>
        </x-modal.card>

    </div>

</div>