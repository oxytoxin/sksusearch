<div x-data x-cloak>
    <h2 class="font-light capitalize text-primary-600 mb-4">Add vehicle</h2>
    <form wire:submit.prevent='save' class="flex flex-col gap-4">
        <div>
            {{ $this->form }}
        </div>
        <div>
            <a href="{{ route('motorpool.vehicle.index') }}"
                class="mr-1 px-3 py-2.5  bg-white rounded-md font-normal capitalize text-primary-600 text-sm">Cancel</a>
            <x-filament-support::button type="submit" wire:target='save'>Save</x-filament-support::button>
        </div>
    </form>
</div>
