<div x-data x-cloak>
    <h2 class="font-light capitalize text-primary-600 mb-4">Archives / Upload Documents</h2>
    <form wire:submit.prevent='save' class="flex flex-col gap-4">
        <div>
            {{ $this->form }}
        </div>
        <div>
            <x-filament-support::button type="submit" wire:target='save'>Save</x-filament-support::button>
        </div>
    </form>
</div>
