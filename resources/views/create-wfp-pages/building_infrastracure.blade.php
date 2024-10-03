<div class="bg-gray-100 px-2 py-4 rounded-lg">
    <div class="flex justify-between">
        <div class="text-2xl text-gray-800">
            Building & Infrastructure
        </div>
        <div>
            {{-- <button wire:click="showBuildingDetails" type="button" class="flex hover:bg-yellow-500 p-2 bg-yellow-600 rounded-md font-light capitalize text-white text-sm">
                Show Details
            </button> --}}
        </div>
    </div>


    <div class="mt-4 space-y-4 bg-white p-3 rounded-lg">
        <div>
            <div class="flex items center">
                <input wire:model="building_is_remarks" id="building_is_remarks" name="building_is_remarks" type="checkbox" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                <label for="building_is_remarks" class="ml-2 block text-sm font-medium text-gray-900">Add Remarks</label>
            </div>
            @if ($building_is_remarks)
            <div class="mt-2 w-full">
                <textarea id="about" wire:model="building_remarks" name="about" rows="4" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                {{-- <p class="mt-3 text-sm leading-6 text-gray-600">Write a few sentences about yourself.</p> --}}
            </div>
            @endif
        </div>
        <div class="grid grid-cols-3 space-x-4">
            <div class="sm:col-span-2">
                <label for="building_particulars" class="block text-sm font-medium leading-6 text-gray-900">Particulars</label>
                <div class="mt-2">
                <select wire:model="building_particular_id" id="building_particular_id" name="building_particulars" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-full sm:text-sm sm:leading-6">
                    <option value="">Select One</option>
                    @foreach ($building_particulars as $item)
                    <option value="{{$item->id}}">{{$item->particulars}}</option>
                    @endforeach
                </select>
                </div>
                @error('building_particular_id')
                <p class="mt-2 text-sm text-red-600" id="email-error">{{ $message }}</p>
                @enderror
              </div>
              <div class="sm:col-span-1">
                <label for="building_code" class="block text-sm font-medium leading-6 text-gray-900">Supply Code</label>
                <div class="mt-2">
                  <input wire:model.defer="building_code" disabled id="building_code" name="building_code" type="text" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
            </div>
        </div>

          <div class="grid grid-cols-3 space-x-4 justify-center">
            <div class="sm:col-span-1">
                <label for="building_uacs" class="block text-sm font-medium leading-6 text-gray-900">UACS Code</label>
                <div class="mt-2">
                  <input wire:model.defer="building_uacs" disabled id="building_uacs" name="building_uacs" type="text" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
            </div>
            <div class="sm:col-span-1">
                <label for="building_title_group" class="block text-sm font-medium leading-6 text-gray-900">Title Group</label>
                <div class="mt-2">
                  <input wire:model.defer="building_title_group" disabled id="building_title_group" name="building_title_group" type="text" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
            </div>
            <div class="sm:col-span-1">
                <label for="building_account_title" class="block text-sm font-medium leading-6 text-gray-900">Account Title</label>
                <div class="mt-2">
                  <input wire:model.defer="building_account_title" disabled id="building_account_title" name="building_account_title" type="text" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
            </div>
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
                            @foreach ($building_quantity as $index => $value)
                                <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">
                                    <input {{$building_particular_id === null ? 'disabled' : ''}} wire:model="building_quantity.{{$index}}" id="{{$index}}" name="{{$index}}" type="number" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
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
            <div class="mt-4">
                <div class="flex items center">
                    <input {{$building_ppmp ? 'checked' : ''}} disabled wire:model="building_ppmp" id="building_ppmp" name="building_ppmp" type="checkbox" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <label for="building_ppmp" class="ml-2 block text-sm font-medium text-gray-900">PPMP</label>
                </div>
          </div>
          <div class="mt-3 grid grid-cols-4 space-x-4 justify-center">
            <div class="sm:col-span-1">
                <label for="building_total_quantity" class="block text-sm font-medium leading-6 text-gray-900">Total Quantity</label>
                <div class="mt-2">
                  <input disabled wire:model="building_total_quantity" disabled id="building_total_quantity" name="building_total_quantity" type="text" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
            </div>
            <div class="sm:col-span-1">
                <label for="building_uom" class="block text-sm font-medium leading-6 text-gray-900">UOM</label>
                <div class="mt-2">
                    <select wire:model="building_uom" id="building_uom" name="building_uom" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-full sm:text-sm sm:leading-6">
                        <option value="">Select One</option>
                        <option value="pcs">pcs</option>
                        <option value="box">box</option>
                        <option value="pax">pax</option>
                        <option value="lot">lot</option>
                        <option value="van">van</option>
                    </select>
                </div>
                @error('building_uom')
                <p class="mt-2 text-sm text-red-600" id="email-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="sm:col-span-1">
                <label for="building_cost_per_unit" class="block text-sm font-medium leading-6 text-gray-900">Cost per unit</label>
                <div class="mt-2">
                  <input wire:model="building_cost_per_unit" {{$building_ppmp || $building_particular_id === null ? 'disabled' : ''}} id="building_cost_per_unit" name="building_cost_per_unit" type="number" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
                @error('building_cost_per_unit')
                <p class="mt-2 text-sm text-red-600" id="email-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="sm:col-span-1">
                <label for="building_estimated_budget" class="block text-sm font-medium leading-6 text-gray-900">Estimated Budget</label>
                <div class="mt-2">
                  <input wire:model.defer="building_estimated_budget" disabled id="building_estimated_budget" name="building_estimated_budget" type="text" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
            </div>
          </div>
    </div>
</div>
{{-- add button --}}
    <div class="mt-3 w-full">
        <x-button wire:click="addMachine" full emerald label="Add" />
</div>

