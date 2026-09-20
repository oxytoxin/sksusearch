<div x-data x-cloak>
    <h2 class="mb-4 font-light capitalize text-primary-600">Report Item</h2>
    <form wire:submit='save' class="flex flex-col gap-4">
        <div>
            {{ $this->form }}
        </div>
        <div class="flex justify-end">
            <a href="{{route('wfp.report-supply-list')}}" class="mr-1 px-3 py-2.5  bg-white rounded-md font-normal capitalize text-primary-600 text-sm">Cancel</a>
            <x-filament::button type="submit" wire:target='save'>Save</x-filament::button>
        </div>
    </form>
</div>
