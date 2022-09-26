<div>
    <h4 class="mb-4 text-lg font-semibold">Create Itinerary</h4>
    <form wire:submit.prevent='save' class="flex flex-col gap-4">
        <div>
            {{ $this->form }}
        </div>
        <div>
            @if ($travel_order_id)
                <x-filament-support::button type="submit" wire:target='save'>Save</x-filament-support::button>
            @endif
        </div>

    </form>
</div>
