<div>
    <h4>Create Travel Order</h4>
    <form wire:submit.prevent='save'>
        <div>
            {{ $this->form }}
        </div>
        <x-filament-support::button type="submit" wire:target='save'>Save</x-filament-support::button>
    </form>
</div>
