<div x-data x-cloak>
    <h2 class="mb-4 font-light capitalize text-primary-600">Report Supply</h2>
    <form wire:submit.prevent='save' class="flex flex-col gap-4">
        <div>
            {{ $this->form }}
        </div>
        <div class="flex justify-end">
            <a href="{{route('wfp.report-supply-list')}}" class="mr-1 px-3 py-2.5  bg-white rounded-md font-normal capitalize text-primary-600 text-sm">Cancel</a>
            <x-filament-support::button type="submit" wire:target='save'>Save</x-filament-support::button>
        </div>
    </form>
</div>
