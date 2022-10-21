<div>
    <div class="flex">
        <h2 class="font-light capitalize text-primary-600">Petty Cash Vouchers / Replenish Petty Cash Fund</h2>
    </div>
    <div class="p-4 mt-4 bg-white rounded shadow">
        <form x-data x-cloak wire:submit.prevent="replenish">
            {{ $this->form }}

            <div class="flex justify-end mt-4">
                <x-filament::button type="submit" wire:target="replenish">Replenish</x-filament::button>
            </div>
        </form>
    </div>
    <div class="mt-4">
        {{ $this->table }}
    </div>
</div>
