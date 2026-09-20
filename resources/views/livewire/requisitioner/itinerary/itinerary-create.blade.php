<div x-data x-cloak>
    <h4 class="mb-4 text-lg font-semibold">{{ $is_editing ? 'Edit Itinerary' : 'Create Itinerary' }}</h4>
    <form wire:submit='save' class="flex flex-col gap-4">
        <div>
            {{ $this->form }}
        </div>
        <div>
            @if ($travel_order_id)
                <x-filament::button type="submit" wire:target='save'>{{ $is_editing ? 'Resubmit' : 'Save' }}</x-filament::button>
            @endif
        </div>

    </form>
</div>
