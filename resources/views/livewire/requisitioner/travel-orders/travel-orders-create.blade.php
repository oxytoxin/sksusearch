<div x-data x-cloak>
    <h2 class="mb-4 font-light capitalize text-primary-600">{{ $is_editing ? 'Edit Travel Order' : 'Create Travel Order' }}</h2>
    <form wire:submit.prevent='save' class="flex flex-col gap-4">
        <div>
            {{ $this->form }}
        </div>
        <div>
            <x-filament-support::button type="submit" wire:target='save'>{{ $is_editing ? 'Resubmit' : 'Save' }}</x-filament-support::button>
        </div>
    </form>
</div>
