<div>
    {{ $this->form }}
</div>
<div class="mt-3 flex justify-end">
    <a href="{{ route('motorpool.request.index') }}"
        class="mr-1 px-3 py-2.5  bg-white rounded-md font-normal capitalize text-primary-600 text-sm">Cancel</a>
    <x-filament-support::button type="submit" wire:target='save'>Save</x-filament-support::button>
</div>
<div>
</div>
