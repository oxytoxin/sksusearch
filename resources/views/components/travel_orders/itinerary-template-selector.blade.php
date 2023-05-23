<div>
    @forelse ($itineraries as $itinerary)
        <div class="flex items-center gap-4">
            <p>{{ $itinerary->user->employee_information->full_name }}'S ITINERARY</p>
            <x-filament::button icon="ri-file-copy-line" wire:click="copyItinerary({{ $itinerary->id }})">
                <span>Copy</span>
            </x-filament::button>
        </div>
    @empty
        <p>No itineraries created yet.</p>
    @endforelse
    <x-filament::button class="mt-4" icon="ri-refresh-line" color="warning" wire:click="clearItinerary">Reset Itinerary</x-filament::button>
</div>
