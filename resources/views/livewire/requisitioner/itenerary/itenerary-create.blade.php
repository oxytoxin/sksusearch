<div>
    <h4 class="mb-4 text-lg font-semibold">Create Itenerary</h4>
    <form wire:submit.prevent='save' class="flex flex-col gap-4">
        <div>
            {{ $this->form }}
        </div>
        <div>
            <x-filament-support::button type="submit" wire:target='save'>Save</x-filament-support::button>
        </div>
    </form>
</div>
