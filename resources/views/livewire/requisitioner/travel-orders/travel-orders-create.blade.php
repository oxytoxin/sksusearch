<div x-data x-cloak>
    <h2 class="mb-4 font-light capitalize text-primary-600">{{ $is_editing ? 'Edit Travel Order' : 'Create Travel Order' }}</h2>
    <form wire:submit='save' class="flex flex-col gap-4">
        <div>
            {{ $this->form }}
        </div>
        <div>
            <x-filament::button type="submit" wire:target='save'>{{ $is_editing ? 'Resubmit' : 'Save' }}</x-filament::button>
        </div>
    </form>
</div>
