<div class="bg-gray-100 px-2 py-4 rounded-lg">
    <div class="grid grid-cols-2">
        @php
        $name = App\Models\BudgetCategory::where('id', 6)->first()->name;
        @endphp
        <div class="col-span-1 text-xl text-gray-800">
            {{$name}}
        </div>
        <div class="col-span-1">
            {{$this->form}}
            {{-- <button wire:click="showPSDetails" type="button" class="flex hover:bg-yellow-500 p-2 bg-yellow-600 rounded-md font-light capitalize text-white text-sm">
                Show Details
            </button> --}}
        </div>
    </div>

    <div class="mt-4 space-y-4 bg-white p-3 rounded-lg">
        <div class="grid grid-cols-3 space-x-4">
            <div class="sm:col-span-2">
                <div class="flex justify-between">
                    <label for="ps_particulars" class="block text-sm font-medium leading-6 text-gray-900">Particulars</label>
                    @if ($ps_particular_id)
                    <label class="block text-xs font-medium leading-4 text-red-900 underline cursor-pointer"><a href="{{route('wfp.report-supply',  ['record' => $ps_particular_id])}}">Report Supply</a></label>
                    @endif
                    {{-- <label class="block text-xs font-medium leading-4 text-green-900 underline cursor-pointer"><a href="{{route('wfp.request-supply')}}">Request Supply</a></label> --}}
                </div>
                <div class="mt-2">
                <select wire:model="ps_particular_id" disabled id="ps_particular_id" name="ps_particulars" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-full sm:text-sm sm:leading-6">
                    <option value="">Select One</option>
                    @foreach ($ps_particulars as $item)
                    <option value="{{$item->id}}">{{ Str::limit($item->particulars, 50, '...') }}</option>
                    @endforeach
                </select>
                </div>
                @error('ps_particular_id')
                <p class="mt-2 text-sm text-red-600" id="email-error">{{ $message }}</p>
                @enderror
              </div>
              <div class="sm:col-span-1">
                <div class="flex justify-between">
                    <label for="ps_code" class="block text-sm font-medium leading-6 text-gray-900">Supply Code</label>
                    <div class="flex items center">
                        <input {{$ps_ppmp ? 'checked' : ''}} disabled wire:model="ps_ppmp" id="ps_ppmp" name="ps_ppmp" type="checkbox" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <label for="ps_ppmp" class="ml-2 block text-sm font-medium text-gray-900">PPMP</label>
                    </div>
                </div>
                <div class="mt-2">
                  <input wire:model.defer="ps_code" disabled id="ps_code" name="ps_code" type="text" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-600 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
            </div>
        </div>
        {{-- specifications --}}
        <div class="sm:col-span-1">
            <label for="ps_specs" class="block text-sm font-medium leading-6 text-gray-900">Specifications</label>
            <div class="mt-2">
            <input wire:model.defer="ps_specs" disabled id="ps_specs" name="ps_specs" type="text" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-600 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>
        </div>
          <div class="grid grid-cols-3 space-x-4 justify-center">
            <div class="sm:col-span-1">
                <label for="ps_uacs" class="block text-sm font-medium leading-6 text-gray-900">UACS Code</label>
                <div class="mt-2">
                  <input wire:model.defer="ps_uacs" disabled id="ps_uacs" name="ps_uacs" type="text" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-600 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
            </div>
            <div class="sm:col-span-1">
                <label for="ps_account_title" class="block text-sm font-medium leading-6 text-gray-900">Account Title</label>
                <div class="mt-2">
                  <input wire:model.defer="ps_account_title" disabled id="ps_account_title" name="ps_account_title" type="text" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-600 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
            </div>
            <div class="sm:col-span-1">
                <label for="ps_title_group" class="block text-sm font-medium leading-6 text-gray-900">Title Group</label>
                <div class="mt-2">
                  <input wire:model.defer="ps_title_group" disabled id="ps_title_group" name="ps_title_group" type="text" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-600 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
            </div>
          </div>
          <div>
            <div class="flex items center">
                <input wire:model="ps_is_remarks" id="ps_is_remarks" name="ps_is_remarks" type="checkbox" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                <label for="ps_is_remarks" class="ml-2 block text-sm font-medium text-gray-900">Add Remarks</label>
            </div>
            @if ($ps_is_remarks)
            <div class="mt-2 w-full">
                <textarea id="about" wire:model="ps_remarks" name="about" rows="4" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                {{-- <p class="mt-3 text-sm leading-6 text-gray-600">Write a few sentences about yourself.</p> --}}
            </div>
            @endif
        </div>
          {{-- quantity table --}}
          <div>
            <div class="px-1 sm:px-6 lg:px-2">
                <div class="mt-2 flow-root">
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
                            @foreach ($ps_quantity as $index => $value)
                                <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">
                                    <input {{$ps_particular_id === null ? 'disabled' : ''}} wire:model="ps_quantity.{{$index}}" id="{{$index}}" name="{{$index}}" type="number" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
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
                    <input {{$ps_ppmp ? 'checked' : ''}} disabled wire:model="ps_ppmp" id="ps_ppmp" name="ps_ppmp" type="checkbox" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <label for="ps_ppmp" class="ml-2 block text-sm font-medium text-gray-900">PPMP</label>
                </div>
          </div> --}}
          <div class="mt-3 grid grid-cols-4 space-x-4 justify-center">
            <div class="sm:col-span-1">
                <label for="ps_total_quantity" class="block text-sm font-medium leading-6 text-gray-900">Total Quantity</label>
                <div class="mt-2">
                  <input disabled wire:model="ps_total_quantity" disabled id="ps_total_quantity" name="ps_total_quantity" type="text" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
                @error('ps_total_quantity')
                <p class="mt-2 text-sm text-red-600" id="email-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="sm:col-span-1">
                <label for="ps_uom" class="block text-sm font-medium leading-6 text-gray-900">UOM</label>
                <div class="mt-2">
                  <input wire:model.defer="ps_uom" disabled id="ps_uom" name="ps_uom" type="text" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
                {{-- <div class="mt-2">
                    <select wire:model="ps_uom" id="ps_uom" name="ps_uom" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-full sm:text-sm sm:leading-6">
                        <option value="">Select One</option>
                        <option value="pcs">pcs</option>
                        <option value="box">box</option>
                        <option value="pax">pax</option>
                        <option value="lot">lot</option>
                        <option value="van">van</option>
                    </select>
                </div> --}}
                @error('ps_uom')
                <p class="mt-2 text-sm text-red-600" id="email-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="sm:col-span-1">
                <label for="ps_cost_per_unit" class="block text-sm font-medium leading-6 text-gray-900">Cost per unit</label>
                <div class="mt-2">
                  <input wire:model="ps_cost_per_unit" {{$ps_ppmp || $ps_particular_id === null ? 'disabled' : ''}} id="ps_cost_per_unit" name="ps_cost_per_unit" type="number" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
                @error('ps_cost_per_unit')
                <p class="mt-2 text-sm text-red-600" id="email-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="sm:col-span-1">
                <label for="ps_estimated_budget" class="block text-sm font-medium leading-6 text-gray-900">Estimated Budget</label>
                <div class="mt-2">
                  <input wire:model.defer="ps_estimated_budget" disabled id="ps_estimated_budget" name="ps_estimated_budget" type="text" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
            </div>
          </div>
    </div>
</div>
{{-- add button --}}
    <div class="mt-3 w-full">
         @php
            $supply = App\Models\Supply::find($ps_particular_id);
            if ($supply) {
            $budget_category_id = App\Models\BudgetCategory::where('id', $supply->categoryItems()->first()->budget_category_id)->first()->id;
            }else{
            $budget_category_id = null;
            }
        @endphp
        <x-button wire:click="addDetail({{ $budget_category_id }})" full emerald label="Add" />
    </div>
