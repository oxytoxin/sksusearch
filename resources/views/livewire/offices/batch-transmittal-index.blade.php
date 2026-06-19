<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold text-primary-600">Batch Transmittal</h2>
        <a href="{{ route('office.batch-transmittal.create') }}"
           class="inline-flex items-center gap-1 rounded-md bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Create Batch
        </a>
    </div>

    <div x-data="{ tab: @entangle('tab') }" x-cloak>
        <div class="inline-flex flex-row">
            <button class="mt-2 rounded-t-lg px-4 py-2 text-lg font-semibold hover:bg-primary-300"
                    @click="tab = 'incoming'"
                    :class="tab == 'incoming' && 'bg-white -mt-2 text-primary-600'">
                Incoming Batches
            </button>
            <button class="mt-2 rounded-t-lg px-4 py-2 text-lg font-semibold hover:bg-primary-300"
                    @click="tab = 'my_batches'"
                    :class="tab == 'my_batches' && 'bg-white -mt-2 text-primary-600'">
                My Batches
            </button>
        </div>
        <div class="rounded-b-lg rounded-r-lg bg-white p-4">
            {{ $this->table }}
        </div>
    </div>
</div>
