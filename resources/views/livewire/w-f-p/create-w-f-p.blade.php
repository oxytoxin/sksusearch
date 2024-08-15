<div class="space-y-2">
    <div class="flex justify-between items-center">
        <h2 class="font-light capitalize text-primary-600">Create Work & Financial Plan</h2>
        <span>Total Allocated Fund: {{number_format($costCenter->fundAllocations->first()->amount, 2)}}</span>
        {{-- <a href="{{ route('requisitioner.motorpool.create') }}"
            class="hover:bg-primary-500 p-2 bg-primary-600 rounded-md font-light capitalize text-white text-sm">New
            Request</a> --}}
    </div>
    <div class="p-3 bg-gray-50 rounded-lg">
        <form wire:submit.prevent="submit">
            {{ $this->form }}

            <div class="mt-5 flex justify-end">
                <button class="hover:bg-primary-500 p-2 bg-primary-600 rounded-md font-light capitalize text-white text-sm" type="submit">
                    Submit
                </button>
            </div>
        </form>
    </div>
</div>
