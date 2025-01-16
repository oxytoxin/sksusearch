<div class="space-y-2">
    <div class="flex justify-between items-center">
        <h2 class="font-light capitalize text-primary-600">Dev Page</h2>
    </div>
    <div>
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="mt-8 flow-root">
              <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">

                <div>
                  <button wire:click="addCategoryBudget" class="bg-green-600 p-4 text-gray-50 rounded-lg">Update</button>
                </div>
                {{-- <div>
                  <button wire:click="removeAmounts" class="mt-4 bg-green-600 p-4 text-gray-50 rounded-lg">Remove</button>
                </div>
                <div>
                    <button wire:click="deleteItems" class="mt-4 bg-green-600 p-4 text-gray-50 rounded-lg">Delete Items</button>
                  </div> --}}
                <table class="min-w-full divide-y divide-gray-500 mt-10">
                  <thead>
                    <tr class="divide-x divide-gray-500">
                        <th scope="col" class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900">Budget Category</th>
                        <th scope="col" class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pl-0">UACS Code</th>
                        <th scope="col" class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900">Name</th>
                        <th scope="col" class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pl-0">UACS Code Budget</th>
                        <th scope="col" class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900">Name Budget</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-500 bg-transparent">
                    @forelse ($merged_titles as $item)
                    <tr class="divide-x divide-gray-500">
                        <td class="whitespace-nowrap py-4 pl-4 pr-4 text-sm font-medium text-gray-900 sm:pl-0">
                            {{ $item['budget_category'] }}
                        </td>
                        <td class="whitespace-nowrap py-4 pl-4 pr-4 text-sm font-medium text-gray-900 sm:pl-0">
                            {{ $item['category_item_uacs'] }}
                        </td>
                        <td class="whitespace-nowrap py-4 pl-4 pr-4 text-sm font-medium text-gray-900 sm:pl-0">
                            {{ $item['category_item_name'] }}
                        </td>
                        <td class="whitespace-nowrap py-4 pl-4 pr-4 text-sm font-medium text-gray-900 sm:pl-0">
                            {{ $item['budget_item_uacs'] }}
                        </td>
                        <td class="whitespace-nowrap py-4 pl-4 pr-4 text-sm font-medium text-gray-900 sm:pl-0">
                            {{ $item['budget_item_name'] }}
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
