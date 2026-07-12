<div x-data>
    <h4 class="mb-4 text-lg font-semibold">{{ $is_editing ? 'Edit Itinerary' : 'Create Itinerary' }}</h4>
    <form wire:submit.prevent='save' class="flex flex-col gap-4">
        <div>
            {{ $this->form }}
        </div>
        <div>
            @if ($travel_order_id)
                <x-filament-support::button type="submit" wire:target='save'>{{ $is_editing ? 'Resubmit' : 'Save' }}</x-filament-support::button>
            @endif
        </div>

    </form>
</div>
