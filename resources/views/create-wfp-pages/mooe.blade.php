<div wire:ignore.self class="bg-gray-100 px-2 py-4 rounded-lg">
    <div class="grid grid-cols-2">
        @php
        $name = App\Models\BudgetCategory::where('id', 2)->first()->name;
        @endphp
        <div class="col-span-1 text-xl text-gray-800">
            {{$name}}
        </div>
        <div class="col-span-1">
            {{$this->form}}
        </div>
    </div>


    <div class="mt-4 space-y-4 bg-white p-3 rounded-lg">
        <div class="grid grid-cols-3 space-x-4">
            <div class="sm:col-span-2">
                <div class="flex justify-between">
                    <label for="mooe_particulars" class="block text-sm font-medium leading-6 text-gray-900">Particulars</label>
                    @if ($mooe_particular_id)
                    <label class="block text-xs font-medium leading-4 text-red-900 underline cursor-pointer"><a href="{{route('wfp.report-supply',  ['record' => $mooe_particular_id])}}">Report Supply</a></label>
                    @endif
                    {{-- <label class="block text-xs font-medium leading-4 text-green-900 underline cursor-pointer"><a href="{{route('wfp.request-supply')}}">Request Supply</a></label> --}}
                </div>
                <div class="mt-2">
                <select wire:model="mooe_particular_id" disabled id="mooe_particular_id" name="mooe_particulars" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-full sm:text-sm sm:leading-6">
                    <option value="">Select One</option>
                    @foreach ($mooe_particulars as $item)
                    <option value="{{$item->id}}">{{ Str::limit($item->particulars, 50, '...') }}</option>
                    @endforeach
                </select>
                </div>
                @error('mooe_particular_id')
                <p class="mt-2 text-sm text-red-600" id="email-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="sm:col-span-1">
                <div class="flex justify-between">
                    <label for="mooe_code" class="block text-sm font-medium leading-6 text-gray-900">Supply Code</label>
                    <div class="flex items center">
                        <input {{$mooe_ppmp ? 'checked' : ''}} disabled wire:model="mooe_ppmp" id="mooe_ppmp" name="supplies_ppmp" type="checkbox" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <label for="mooe_ppmp" class="ml-2 block text-sm font-medium text-gray-900">PPMP</label>
                    </div>
                </div>
                <div class="mt-2">
                  <input wire:model.defer="mooe_code" disabled id="mooe_code" name="mooe_code" type="text" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-600 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
            </div>
        </div>
        {{-- specifications --}}
        <div class="sm:col-span-1">
            <label for="mooe_specs" class="block text-sm font-medium leading-6 text-gray-600">Specifications</label>
            <div class="mt-2">
            <input wire:model.defer="mooe_specs" disabled id="mooe_specs" name="mooe_specs" type="text" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-600 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>
        </div>
          <div class="grid grid-cols-3 space-x-4 justify-center">
            <div class="sm:col-span-1">
                <label for="mooe_uacs" class="block text-sm font-medium leading-6 text-gray-900">UACS Code</label>
                <div class="mt-2">
                  <input wire:model.defer="mooe_uacs" disabled id="mooe_uacs" name="mooe_uacs" type="text" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-600 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
            </div>
            <div class="sm:col-span-1">
                <label for="mooe_account_title" class="block text-sm font-medium leading-6 text-gray-900">Account Title</label>
                <div class="mt-2">
                  <input wire:model.defer="mooe_account_title" disabled id="mooe_account_title" name="mooe_account_title" type="text" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-600 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
            </div>
            <div class="sm:col-span-1">
                <label for="mooe_title_group" class="block text-sm font-medium leading-6 text-gray-900">Title Group</label>
                <div class="mt-2">
                  <input wire:model.defer="mooe_title_group" disabled id="mooe_title_group" name="mooe_title_group" type="text" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-600 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
            </div>
          </div>
          <div>
            <div class="flex items center">
                <input wire:model="mooe_is_remarks" id="mooe_is_remarks" name="mooe_is_remarks" type="checkbox" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                <label for="mooe_is_remarks" class="ml-2 block text-sm font-medium text-gray-900">Add Remarks</label>
            </div>
            @if ($mooe_is_remarks)
            <div class="mt-2 w-full">
                <textarea id="about" wire:model="mooe_remarks" name="about" rows="4" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                {{-- <p class="mt-3 text-sm leading-6 text-gray-600">Write a few sentences about yourself.</p> --}}
            </div>
            @endif
        </div>
          {{-- quantity table --}}
          <div>
            <div class="px-1 sm:px-6 lg:px-2">
                <div class="flow-root">
                  <div class=" -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                      <table class="min-w-full divide-y divide-gray-300">
                        <thead>
                          <tr>
                            <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Jan</th>
                            <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Feb</th>
                            <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Mar</th>
                            <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Apr</th>
                            <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">May</th>
                            <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Jun</th>
                            <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Jul</th>
                            <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Aug</th>
                            <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Sep</th>
                            <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Oct</th>
                            <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Nov</th>
                            <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Dec</th>
                          </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                          <tr>
                            @foreach ($mooe_quantity as $index => $value)
                                <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">
                                    <input {{$mooe_particular_id === null ? 'disabled' : ''}} wire:model="mooe_quantity.{{$index}}" id="{{$index}}" name="{{$index}}" type="number" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </td>
                            @endforeach
                            </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
          </div>
          {{-- end quantity table --}}
          <div>
            {{-- checkbox input --}}
            {{-- <div class="mt-4">
                <div class="flex items center">
                    <input {{$mooe_ppmp ? 'checked' : ''}} disabled wire:model="mooe_ppmp" id="mooe_ppmp" name="supplies_ppmp" type="checkbox" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <label for="mooe_ppmp" class="ml-2 block text-sm font-medium text-gray-900">PPMP</label>
                </div>
          </div> --}}
          <div class="mt-3 grid grid-cols-4 space-x-4 justify-center">
            <div class="sm:col-span-1">
                <label for="mooe_total_quantity" class="block text-sm font-medium leading-6 text-gray-900">Total Quantity</label>
                <div class="mt-2">
                  <input disabled wire:model="mooe_total_quantity" disabled id="mooe_total_quantity" name="mooe_total_quantity" type="text" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
                @error('mooe_total_quantity')
                <p class="mt-2 text-sm text-red-600" id="email-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="sm:col-span-1">
                <label for="mooe_uom" class="block text-sm font-medium leading-6 text-gray-900">UOM</label>
                <div class="mt-2">
                  <input wire:model.defer="mooe_uom" disabled id="mooe_uom" name="mooe_uom" type="text" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
                {{-- <div class="mt-2">
                    <select wire:model="mooe_uom" id="mooe_uom" name="mooe_uom" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-full sm:text-sm sm:leading-6">
                        <option value="">Select One</option>
                        <option value="pcs">pcs</option>
                        <option value="box">box</option>
                        <option value="pax">pax</option>
                        <option value="lot">lot</option>
                        <option value="van">van</option>
                    </select>
                </div> --}}
                @error('mooe_uom')
                <p class="mt-2 text-sm text-red-600" id="email-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="sm:col-span-1">
                <label for="mooe_cost_per_unit" class="block text-sm font-medium leading-6 text-gray-900">Cost per unit</label>
                <div class="mt-2">
                  <input wire:model="mooe_cost_per_unit" {{$mooe_ppmp || $mooe_particular_id === null ? 'disabled' : ''}} id="mooe_cost_per_unit" name="mooe_cost_per_unit" type="number" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
                @error('mooe_cost_per_unit')
                <p class="mt-2 text-sm text-red-600" id="email-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="sm:col-span-1">
                <label for="mooe_estimated_budget" class="block text-sm font-medium leading-6 text-gray-900">Estimated Budget</label>
                <div class="mt-2">
                  <input wire:model.defer="mooe_estimated_budget" disabled id="mooe_estimated_budget" name="mooe_estimated_budget" type="text" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
            </div>
          </div>
    </div>
</div>
{{-- add button --}}
    <div class="mt-3 w-full">
        <x-button wire:click="addMooe" full emerald label="Add" />
</div>

{{-- modal --}}
<x-modal.card title="MOOE" fullscreen blur wire:model.defer="mooeDetailModal">

    <div >
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="sm:flex sm:items-center">
              <div class="sm:flex-auto">
                <h1 class="text-base font-semibold leading-6 text-gray-900">Work & Financial Plan</h1>

              </div>
            </div>
            <div class="mt-2 grid grid-cols-3">
                <div class="col-span-1">
                    <p class="mt-2 text-sm text-gray-700">{{$wfp_type->description}}</p>
                    <p class="mt-2 text-sm text-gray-700">Fund: {{$wfp_fund->name}}</p>
                </div>
                <div class="col-span-1">
                    <p class="mt-2 text-sm text-gray-700">Source of Fund: {{$wfp_fund->name}}</p>
                    <p class="mt-2 text-sm text-gray-700">if miscellaneous/fiduciary fee, please specify: {{$wfp_fund->name}}</p>
                </div>
                <div class="col-span-1">
                    <p class="mt-2 text-sm text-gray-700">Cost Center: {{$wfp_fund->name}}</p>
                    <p class="mt-2 text-sm text-gray-700">Cost Center Head: {{$wfp_fund->name}}</p>
                </div>
            </div>

            <div class="mt-8 flow-root">
              <div class="my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-2">
                  <table class="min-w-full">
                    <thead class="bg-gray-400">
                        <tr class="border-t border-gray-200">
                            <th colspan="21" scope="colgroup" class="bg-green-700 py-2 pl-4 pr-3 text-left text-sm font-semibold text-gray-50 sm:pl-3">Specific Fund Source: </th>
                          </tr>
                    </thead>
                    <thead class="bg-white">
                      <tr>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">UACS Code</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Account Title</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Particulars</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Qty</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">UOM</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Unit Cost</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Estimated Budget</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">Jan</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">Feb</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">Mar</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">Apr</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">May</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">Jun</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">Jul</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">Aug</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">Sep</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">Oct</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">Nov</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900  bg-gray-200 border-x border-gray-400">Dec</th>
                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-3">
                          <span class="sr-only">Edit</span>
                        </th>
                      </tr>
                    </thead>
                    <tbody class="bg-white">
                        <tr class="border-t border-gray-200">
                            <th colspan="20" scope="colgroup" class="bg-yellow-100 py-2 pl-4 pr-3
                            text-left text-sm font-semibold text-gray-900 sm:pl-3">Supplies & Semi-Expendables</th>
                          </tr>
                          @forelse ($supplies as $item)
                          <tr class="border-t border-gray-300">
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-3">{{$item['uacs']}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-wrap">{{$item['account_title']}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-wrap">{{$item['particular']}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-wrap">{{$item['total_quantity']}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{$item['uom']}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">₱ {{number_format($item['cost_per_unit'], 2)}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">₱ {{number_format($item['estimated_budget'], 2)}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][0]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][1]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][2]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][3]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][4]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][5]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][6]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][7]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][8]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][9]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][10]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][11]}}</td>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-3">
                              <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit<span class="sr-only">, Lindsay Walton</span></a>
                            </td>
                          </tr>
                        @empty
                        <tr class="border-t border-gray-200">
                            <th colspan="20" scope="colgroup" class="bg-gray-100 py-2 pl-4 pr-3 text-center text-sm font-semibold text-gray-900 sm:pl-3">No Record</th>
                          </tr>
                        @endforelse
                        <tr class="border-t border-gray-200">
                            <th colspan="20" scope="colgroup" class="bg-yellow-100 py-2 pl-4 pr-3
                            text-left text-sm font-semibold text-gray-900 sm:pl-3">MOOE</th>
                          </tr>
                          @forelse ($mooe as $item)
                          <tr class="border-t border-gray-300">
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-3">{{$item['uacs']}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-wrap">{{$item['account_title']}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-wrap">{{$item['particular']}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-wrap">{{$item['total_quantity']}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{$item['uom']}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">₱ {{number_format($item['cost_per_unit'], 2)}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">₱ {{number_format($item['estimated_budget'], 2)}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][0]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][1]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][2]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][3]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][4]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][5]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][6]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][7]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][8]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][9]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][10]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][11]}}</td>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-3">
                              <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit<span class="sr-only">, Lindsay Walton</span></a>
                            </td>
                          </tr>
                        @empty
                        <tr class="border-t border-gray-200">
                            <th colspan="20" scope="colgroup" class="bg-gray-100 py-2 pl-4 pr-3 text-center text-sm font-semibold text-gray-900 sm:pl-3">No Record</th>
                          </tr>
                        @endforelse
                        <tr class="border-t border-gray-200">
                            <th colspan="20" scope="colgroup" class="bg-yellow-100 py-2 pl-4 pr-3
                            text-left text-sm font-semibold text-gray-900 sm:pl-3">Trainings</th>
                          </tr>
                          @forelse ($trainings as $item)
                          <tr class="border-t border-gray-300">
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-3">{{$item['uacs']}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-wrap">{{$item['account_title']}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-wrap">{{$item['particular']}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-wrap">{{$item['total_quantity']}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{$item['uom']}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">₱ {{number_format($item['cost_per_unit'], 2)}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">₱ {{number_format($item['estimated_budget'], 2)}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][0]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][1]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][2]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][3]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][4]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][5]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][6]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][7]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][8]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][9]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][10]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][11]}}</td>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-3">
                              <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit<span class="sr-only">, Lindsay Walton</span></a>
                            </td>
                          </tr>
                        @empty
                        <tr class="border-t border-gray-200">
                            <th colspan="20" scope="colgroup" class="bg-gray-100 py-2 pl-4 pr-3 text-center text-sm font-semibold text-gray-900 sm:pl-3">No Record</th>
                          </tr>
                        @endforelse
                        <tr class="border-t border-gray-200">
                            <th colspan="20" scope="colgroup" class="bg-yellow-100 py-2 pl-4 pr-3
                            text-left text-sm font-semibold text-gray-900 sm:pl-3">Machine & Equipment / Furniture & Fixtures / Bio / Vehicles</th>
                          </tr>
                          @forelse ($machines as $item)
                          <tr class="border-t border-gray-300">
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-3">{{$item['uacs']}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-wrap">{{$item['account_title']}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-wrap">{{$item['particular']}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-wrap">{{$item['total_quantity']}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{$item['uom']}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">₱ {{number_format($item['cost_per_unit'], 2)}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">₱ {{number_format($item['estimated_budget'], 2)}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][0]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][1]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][2]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][3]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][4]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][5]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][6]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][7]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][8]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][9]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][10]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][11]}}</td>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-3">
                              <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit<span class="sr-only">, Lindsay Walton</span></a>
                            </td>
                          </tr>
                        @empty
                        <tr class="border-t border-gray-200">
                            <th colspan="20" scope="colgroup" class="bg-gray-100 py-2 pl-4 pr-3 text-center text-sm font-semibold text-gray-900 sm:pl-3">No Record</th>
                          </tr>
                        @endforelse
                        <tr class="border-t border-gray-200">
                            <th colspan="20" scope="colgroup" class="bg-yellow-100 py-2 pl-4 pr-3
                            text-left text-sm font-semibold text-gray-900 sm:pl-3">Building & Infrastructure</th>
                          </tr>
                          @forelse ($buildings as $item)
                          <tr class="border-t border-gray-300">
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-3">{{$item['uacs']}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-wrap">{{$item['account_title']}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-wrap">{{$item['particular']}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-wrap">{{$item['total_quantity']}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{$item['uom']}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">₱ {{number_format($item['cost_per_unit'], 2)}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">₱ {{number_format($item['estimated_budget'], 2)}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][0]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][1]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][2]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][3]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][4]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][5]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][6]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][7]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][8]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][9]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][10]}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 border-x border-gray-400">{{$item['quantity'][11]}}</td>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-3">
                              <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit<span class="sr-only">, Lindsay Walton</span></a>
                            </td>
                          </tr>
                        @empty
                        <tr class="border-t border-gray-200">
                            <th colspan="20" scope="colgroup" class="bg-gray-100 py-2 pl-4 pr-3 text-center text-sm font-semibold text-gray-900 sm:pl-3">No Record</th>
                          </tr>
                        @endforelse
                    </tbody>
                  </table>
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
                <x-button primary label="Save" wire:click="save" />
            </div>
        </div>
    </x-slot>
</x-modal.card>
{{-- end modal --}}

