<div class="space-y-2">
    <div class="flex justify-between items-center">
        <h2 class="font-light capitalize text-primary-600">Deactivated Pricelists</h2>
        <a href="{{ route('wfp.wfp-submissions') }}"
            class="hover:bg-primary-500 p-2 bg-primary-600 rounded-md font-light capitalize text-white text-sm">Back</a>
    </div>
    <div>
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="mt-8 flow-root">
              <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                  {{-- <table class="min-w-full divide-y divide-gray-500">
                    <thead>
                      <tr class="divide-x divide-gray-500">
                        <th scope="col" class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pl-0">Cost Center</th>
                        <th scope="col" class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900">Head</th>
                        <th scope="col" class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900">Created By</th>
                        <th scope="col" class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900">Deactivated Items</th>
                        <th scope="col" class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pr-0">Created At</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-500 bg-transparent">
                        @forelse ($record as $item)
                        <tr class="divide-x divide-gray-500">
                            <td class="whitespace-nowrap py-4 pl-4 pr-4 text-sm font-medium text-gray-900 sm:pl-0">{{$item->costCenter->name}}</td>
                            <td class="whitespace-nowrap p-4 text-sm text-gray-500">{{$item->costCenter->office->head_employee->full_name}}</td>
                            <td class="whitespace-nowrap p-4 text-sm text-gray-500">{{$item->user->employee_information->full_name}}</td>
                            <td class="whitespace-nowrap py-4 pl-4 pr-4 text-sm text-gray-500 sm:pr-0">
                                <ul>
                                    @foreach($item->wfpDetails->filter(fn($detail) => $detail->supply->is_active === 0) as $detail)
                                    <li>
                                        {{$detail->supply->particulars}}
                                    </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="whitespace-nowrap py-4 pl-4 pr-4 text-sm text-gray-500 sm:pr-0">{{Carbon\Carbon::parse($item->created_at)->format('F d, Y h:i A')}}</td>
                          </tr>
                          @empty
                          <tr class="divide-x divide-gray-500">
                            <td colspan="5" class="whitespace-nowrap py-4 pl-4 pr-4 text-md italic text-gray-500 sm:pr-0 text-center">
                            No Record
                            </td>   
                          </tr>
                          @endforelse
                    </tbody>
                  </table> --}}
                </div>
                {{-- <div>
                  <button wire:click="updateAmounts" class="bg-green-600 p-4 text-gray-50 rounded-lg">Update</button>
                </div> --}}
                {{-- <div>
                  <button wire:click="removeAmounts" class="mt-4 bg-green-600 p-4 text-gray-50 rounded-lg">Remove</button>
                </div> --}}
                <table class="min-w-full divide-y divide-gray-500 mt-10">
                  <thead>
                    <tr class="divide-x divide-gray-500">
                      <th scope="col" class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pl-0">Cost Center</th>
                      <th scope="col" class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900">Head</th>
                      <th scope="col" class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900">Fund Cluster</th>
                      <th scope="col" class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900">Fund Allocations</th>
                      <th scope="col" class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900">Fund Item Total</th>
                      <th scope="col" class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pr-0">Fund Amount Total</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-500 bg-transparent">
                    @forelse ($costCenters as $costCenter)
                    <tr class="divide-x divide-gray-500">
                        <td class="whitespace-nowrap py-4 pl-4 pr-4 text-sm font-medium text-gray-900 sm:pl-0">
                            {{ $costCenter->name }}
                        </td>
                        <td class="whitespace-nowrap p-4 text-sm text-gray-500">
                            {{ $costCenter->office->head_employee?->full_name }}
                        </td>
                        <td class="whitespace-nowrap p-4 text-sm text-gray-500">
                          {{ $costCenter->fundAllocations()->first()->fundClusterWFP->name }}
                        </td>
                        <td class="whitespace-nowrap p-4 text-sm text-gray-500">
                          @foreach ($costCenter->fundAllocations->where('initial_amount', '!=' ,0) as $fundAllocation)
                                  <div class="mb-4">
                                      <strong>Allocation ID:</strong> {{ $fundAllocation->id }}
                                      <br>
                                      <strong>Title Group: </strong>{{$fundAllocation->category_group_id}} - {{ $fundAllocation->categoryGroup?->name }}
                                      <br>
                                      <strong>Initial Amount: </strong>{{$fundAllocation->initial_amount}}
                                      
                                  </div>
                          @endforeach
                      </td>
                        <td class="whitespace-nowrap p-4 text-sm text-gray-500">
                            @foreach ($costCenter->fundAllocations as $fundAllocation)
                                @foreach ($fundAllocation->fundDrafts as $fundDraft)
                                    <div class="mb-4">
                                        <strong>Draft ID:</strong> {{ $fundDraft->id }}
                                        <br>
                                        <strong>Draft Items by Title Group:</strong>
                                        <ul>
                                            @foreach ($fundDraft->draft_items as $item)
                                                <li>
                                                    Title Group: {{ $item->title_group }} - 
                                                    Total Budget: {{ number_format($item->total_budget, 2) }}
                                                </li>
                                            @endforeach
                                        </ul>
                                        </ul>
                                    </div>
                                @endforeach
                            @endforeach
                        </td>
                        <td class="whitespace-nowrap p-4 text-sm text-gray-500">
                          @foreach ($costCenter->fundAllocations as $fundAllocation)
                                @foreach ($fundAllocation->fundDrafts as $fundDraft)
                                    <div class="mb-4">
                                        <strong>Draft ID:</strong> {{ $fundDraft->id }}
                                        <br>
                                        <strong>Draft Amounts by Category Item:</strong>
                                        <ul>
                                            @foreach ($fundDraft->draft_amounts as $amount)
                                                <li>
                                                    Title Group: {{ $amount->category_group_id }} - 
                                                    Total Amount: {{ number_format($amount->total_amount, 2) }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            @endforeach
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-gray-500">No cost centers found.</td>
                    </tr>
                @endforelse
                
                   
        
                  </tbody>
                </table>

              </div>
            </div>
          </div>
          
    </div>
</div>
