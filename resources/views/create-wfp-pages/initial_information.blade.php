<div class="bg-gray-100 px-2 py-4 rounded-lg">
    <div class="text-2xl text-gray-800">
        Initial Information
    </div>
    <div class="mt-4 space-y-4 bg-white p-3 rounded-lg">
        <div class="sm:col-span-4">
            <label for="fund_description" class="block text-sm font-medium leading-6 text-gray-900">Fund Description</label>
            <div class="mt-2">
              <input wire:model.defer="fund_description" id="fund_description" name="fund_description" type="text" autocomplete="email" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>
        </div>
        <div class="relative">
            <div class="sm:col-span-3">
                <label for="source_fund" class="block text-sm font-medium leading-6 text-gray-900">Source Fund</label>
                <div class="mt-2">
                <select wire:model="source_fund" id="source_fund" name="source_fund" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-full sm:text-sm sm:leading-6">
                    <option value="TUITION FEE - RESEARCH FUND">TUITION FEE - RESEARCH FUND</option>
                    <option value="TUITION FEE - EXTENSION FUND">TUITION FEE - EXTENSION FUND</option>
                    <option value="TUITION FEE - EXTENSION FUND">TUITION FEE - EXTENSION FUND</option>
                    <option value="TUITION FEE - FACILITIES DEVELOPMENT">TUITION FEE - FACILITIES DEVELOPMENT</option>
                    <option value="TUITION FEE - CURRICULUM DEVELOPMENT">TUITION FEE - CURRICULUM DEVELOPMENT</option>
                    <option value="MISCELLANEOUS/FIDUCIARY FEE">MISCELLANEOUS/FIDUCIARY FEE</option>
                </select>
                </div>
              </div>
            {{-- <div
           class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
              <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M10
           12l-5-5 1.41-1.41L10 9.17l3.59-3.58L15 7z"/></svg>
            </div> --}}
          </div>

        @if ($source_fund == 'MISCELLANEOUS/FIDUCIARY FEE')
        <div class="sm:col-span-4">
            <label for="confirm_fund_source" class="block text-sm font-medium leading-6 text-gray-900">if miscellaneous/fiduciary fee, please specify</label>
            <div class="mt-2">
              <input wire:model="confirm_fund_source" id="confirm_fund_source" name="confirm_fund_source" type="text" autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>
        </div>
        @endif
        {{-- <div class="sm:col-span-4">
            <label for="specify_fund_source" class="block text-sm font-medium leading-6 text-gray-900">Specific Fund Source</label>
            <div class="mt-2">
              <input wire:model.defer="specify_fund_source" id="specify_fund_source" name="specify_fund_source" type="text"
              autocomplete="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>
        </div> --}}
    </div>
</div>
