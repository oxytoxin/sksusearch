<div class="space-y-2">
    <div class="flex">
        <h2 class="font-light capitalize text-primary-600">Legacy Document Count</h2>
    </div>
    <div class="w-full rounded-lg bg-white p-4">
        <div class="flex justify-between">
            <div>
                <x-filament-support::button wire:click="redirectBack">Back</x-filament-support::button>
            </div>
            <div class="flex space-x-4">
                <x-select label="Year" placeholder="" wire:model="year">
                    <x-select.option label="All" value="all" />
                    @foreach ($legacy_document_years as $item)
                    <x-select.option label="{{$item->year}}" value="{{$item->year}}" />
                    @endforeach
                </x-select>
            </div>
        </div>
        <div class="mt-10 font-medium capitalize text-primary-600 text-lg text-right">
            Total Count: {{$init_count}}
        </div>
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="mt-8 flow-root">
              <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                  <table class="min-w-full divide-y divide-gray-300">
                    <thead>
                      <tr>
                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Document Code</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">DV Number</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Payee</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Journal Date</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Cheque Date</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Fund Cluster</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Document Category</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($legacy_docs as $doc)
                        <tr>
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">{{$doc->document_code}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{$doc->dv_number}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{$doc->payee_name}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{Carbon\Carbon::parse($doc->journal_date)->format('F d, Y')}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{Carbon\Carbon::parse($doc->cheque_date)->format('F d, Y')}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{$doc->fund_cluster->name}}</td>
                            @if ($doc->document_category == 1)
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Disbursement Voucher</td>
                            @elseif($doc->document_category == 2)
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Liquidation Report</td>
                            @endif

                          </tr>
                        @endforeach

                      <!-- More people... -->
                    </tbody>
                  </table>
                  {{$legacy_docs->links()}}
                </div>
              </div>
            </div>
          </div>

    </div>
</div>
