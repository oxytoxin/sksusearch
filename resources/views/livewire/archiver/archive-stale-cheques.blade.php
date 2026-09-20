<div x-data x-cloak >
    <h2 class="mb-4 font-light capitalize text-primary-600">Archives/ Upload stale/cancelled documents</h2>
    <form wire:submit='save' class="flex flex-col gap-4">
        <div>
            {{ $this->form }}
        </div>
        <div class="grid w-full justify-items-stretch">
            <div class="justify-self-end">
                <x-filament::button type="submit" wire:target='save' class="flex">Save Cheque Details</x-filament::button>
            </div>
        </div>
    </form>
</div>
