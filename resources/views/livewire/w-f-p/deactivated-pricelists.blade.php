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
                  <table class="min-w-full divide-y divide-gray-500">
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
                     
          
                      <!-- More people... -->
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          
    </div>
</div>
