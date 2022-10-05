<div class="space-y-4">
    <div class="p-4 bg-white rounded-lg shadow">
        <h4>Assign OIC</h4>
        <form class="mt-4" x-data x-cloak wire:submit.prevent="assign">
            {{ $this->form }}
            <div class="mt-4">
                <x-filament-support::button type="submit" wire:target="assign">Assign</x-filament-support::button>
            </div>
        </form>
    </div>
    <div>
        {{ $this->table }}
    </div>
</div>
