<div>
    <form wire:submit.prevent="replenish">
        {{ $this->form }}

        <div class="flex justify-end mt-4">
            <x-filament::button type="submit" wire:target="replenish">Replenish</x-filament::button>
        </div>
    </form>
</div>
