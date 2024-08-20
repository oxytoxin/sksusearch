<div class="space-y-2">
    <div class="flex justify-between items-center">
        <h2 class="font-light capitalize text-primary-600">Create Work & Financial Plan</h2>
        {{-- <span>Total Allocated Fund: ₱ {{number_format($costCenter->fundAllocations->first()->amount, 2)}}</span> --}}
        {{-- <a href="{{ route('requisitioner.motorpool.create') }}"
            class="hover:bg-primary-500 p-2 bg-primary-600 rounded-md font-light capitalize text-white text-sm">New
            Request</a> --}}
    </div>
    <div class="p-3 bg-gray-50 rounded-lg">
        <form wire:submit.prevent="submit">
            {{ $this->form }}
            <div class="mt-5 p-3 border border-gray-300 rounded-lg">
                <div class="flex py-2 justify-end">
                    <span class="font-semibold">Grand Total: ₱ 100,000.00</span>
                </div>
                 <div class="flex py-2 justify-end">
                    <span class="font-semibold">Budget Allocation: ₱ {{number_format($costCenter->fundAllocations->first()->amount, 2)}}</span>
                </div>
                <div class="flex py-2 justify-end">
                    <span class="font-semibold">Balance forwarded from previous years: ₱ 15,000.00</span>
                </div>
                <div class="flex py-2 justify-end">
                    <span class="font-semibold border-gray-800 border-t-2 w-1/6"></span>
                </div>
                <div class="flex py-2 justify-end">
                    <span class="font-semibold">Unprogrammed Allocation: ₱ 88,660.00</span>
                </div>
            </div>
            <div class="mt-5 flex justify-end">

                <button class="hover:bg-primary-500 p-2 bg-primary-600 rounded-md font-light capitalize text-white text-sm" type="submit">
                    Submit
                </button>
            </div>
        </form>

    </div>
</div>
