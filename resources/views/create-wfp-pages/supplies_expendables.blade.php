<div wire:ignore.self class="bg-gray-100 px-2 py-4 rounded-lg">
    <div class="grid grid-cols-2">
        @php
            $name = App\Models\BudgetCategory::where('id', 1)->first()->name;
        @endphp
        <div class="col-span-1 text-xl text-gray-800">
            {{$name}}
        </div>
        <div class="col-span-1">
            {{$this->form}}
            {{-- @if ($supplies)
            <button wire:click="$set('suppliesDetailModal',true)" type="button" class="flex hover:bg-yellow-500 p-2 bg-yellow-600 rounded-md font-light capitalize text-white text-sm">
                Show Details
            </button>
            @endif --}}
        </div>
    </div>


    <div class="mt-4 space-y-4 bg-white p-3 rounded-lg">
        <div class="grid grid-cols-3 space-x-4">
            <div class="sm:col-span-2">
                <div class="flex justify-between">
                    <label for="supplies_particulars" class="block text-sm font-medium leading-6 text-gray-900">Particulars</label>
                    @if ($supplies_particular_id)
                    <label class="block text-xs font-medium leading-4 text-red-900 underline cursor-pointer"><a href="{{route('wfp.report-supply',  ['record' => $supplies_particular_id])}}">Report Supply</a></label>
                    @endif
                </div>
                <div class="mt-2">
                <select wire:model="supplies_particular_id" disabled id="supplies_particular_id" name="supplies_particulars" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-full sm:text-sm sm:leading-6">
                    <option value="">Select One</option>
                    @foreach ($supplies_particulars as $item)
                    <option value="{{$item->id}}">{{ Str::limit($item->particulars, 50, '...') }}</option>
                    @endforeach
                </select>
                </div>
                @error('supplies_particular_id')
                <p class="mt-2 text-sm text-red-600" id="email-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="sm:col-span-1">
                <div class="flex justify-between">
                    <label for="supplies_code" class="block text-sm font-medium leading-6 text-gray-900">Supply Code</label>
                    <div class="flex items center">
                        <input {{$supplies_ppmp ? 'checked' : ''}} disabled wire:model="supplies_ppmp" id="supplies_ppmp" name="supplies_ppmp" type="checkbox" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <label for="supplies_ppmp" class="ml-2 block text-sm font-medium text-gray-900">PPMP</label>
                    </div>
                </div>

                <div class="mt-2">
                  <input wire:model.defer="supplies_code" disabled id="supplies_code" name="supplies_code" type="text" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-600 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
            </div>
        </div>
        {{-- specifications --}}
        <div class="sm:col-span-1">
            <label for="supplies_specs" class="block text-sm font-medium leading-6 text-gray-900">Specifications</label>
            <div class="mt-2">
              <input wire:model.defer="supplies_specs" disabled id="supplies_specs" name="supplies_specs" type="text" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-600 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>
        </div>
          <div class="grid grid-cols-3 space-x-4 justify-center">
            <div class="sm:col-span-1">
                <label for="supplies_uacs" class="block text-sm font-medium leading-6 text-gray-900">UACS Code</label>
                <div class="mt-2">
                  <input wire:model.defer="supplies_uacs" disabled id="supplies_uacs" name="supplies_uacs" type="text" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-600 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
            </div>
            <div class="sm:col-span-1">
                <label for="supplies_account_title" class="block text-sm font-medium leading-6 text-gray-900">Account Title</label>
                <div class="mt-2">
                  <input wire:model.defer="supplies_account_title" disabled id="supplies_account_title" name="supplies_account_title" type="text" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-600 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
            </div>
            <div class="sm:col-span-1">
                <label for="supplies_title_group" class="block text-sm font-medium leading-6 text-gray-900">Title Group</label>
                <div class="mt-2">
                  <input wire:model.defer="supplies_title_group" disabled id="supplies_title_group" name="supplies_title_group" type="text" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-600 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
            </div>
          </div>
          <div>
            <div class="flex items center">
                <input wire:model="supplies_is_remarks" id="supplies_is_remarks" name="supplies_is_remarks" type="checkbox" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                <label for="supplies_is_remarks" class="ml-2 block text-sm font-medium text-gray-900">Add Remarks</label>
            </div>
            @if ($supplies_is_remarks)
            <div class="mt-2 w-full">
                <textarea id="about" wire:model="supplies_remarks" name="about" rows="4" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
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
                            @foreach ($supplies_quantity as $index => $value)
                                <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">
                                    <input {{$supplies_particular_id === null ? 'disabled' : ''}} wire:model="supplies_quantity.{{$index}}" id="{{$index}}" name="{{$index}}" type="number" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
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
                    <input {{$supplies_ppmp ? 'checked' : ''}} disabled wire:model="supplies_ppmp" id="supplies_ppmp" name="supplies_ppmp" type="checkbox" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <label for="supplies_ppmp" class="ml-2 block text-sm font-medium text-gray-900">PPMP</label>
                </div>
          </div> --}}
          <div class="mt-3 grid grid-cols-4 space-x-4 justify-center">
            <div class="sm:col-span-1">
                <label for="supplies_total_quantity" class="block text-sm font-medium leading-6 text-gray-900">Total Quantity</label>
                <div class="mt-2">
                  <input disabled wire:model="supplies_total_quantity" disabled id="supplies_total_quantity" name="supplies_total_quantity" type="text" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
                @error('supplies_total_quantity')
                <p class="mt-2 text-sm text-red-600" id="email-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="sm:col-span-1">
                <label for="supplies_uom" class="block text-sm font-medium leading-6 text-gray-900">UOM</label>
                <div class="mt-2">
                  <input wire:model.defer="supplies_uom" disabled id="supplies_uom" name="supplies_uom" type="text" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
                {{-- <div class="mt-2">
                    <select wire:model="supplies_uom" id="supplies_uom" name="supplies_uom" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-full sm:text-sm sm:leading-6">
                        <option value="">Select One</option>
                        <option value="pcs">pcs</option>
                        <option value="box">box</option>
                        <option value="pax">pax</option>
                        <option value="lot">lot</option>
                        <option value="van">van</option>
                    </select>
                </div> --}}
                @error('supplies_uom')
                <p class="mt-2 text-sm text-red-600" id="email-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="sm:col-span-1">
                <label for="supplies_cost_per_unit" class="block text-sm font-medium leading-6 text-gray-900">Cost per unit</label>
                <div class="mt-2">
                  <input wire:model="supplies_cost_per_unit" {{$supplies_ppmp || $supplies_particular_id === null ? 'disabled' : ''}} id="supplies_cost_per_unit" name="supplies_cost_per_unit" type="number" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
                @error('supplies_cost_per_unit')
                <p class="mt-2 text-sm text-red-600" id="email-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="sm:col-span-1">
                <label for="supplies_estimated_budget" class="block text-sm font-medium leading-6 text-gray-900">Estimated Budget</label>
                <div class="mt-2">
                  <input wire:model.defer="supplies_estimated_budget" disabled id="supplies_estimated_budget" name="supplies_estimated_budget" type="text" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
            </div>
          </div>
    </div>
</div>

{{-- add button --}}
    <div class="mt-3 w-full">
       @php
            $supply = App\Models\Supply::find($supplies_particular_id);
            if ($supply) {
            $budget_category_id = App\Models\BudgetCategory::where('id', $supply->categoryItems()->first()->budget_category_id)->first()->id;
            }else{
            $budget_category_id = null;
            }
        @endphp
        <x-button wire:click="addDetail({{ $budget_category_id }})" full emerald label="Add" />
</div>

    {{-- modal --}}

    {{-- end modal --}}


