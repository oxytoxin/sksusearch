<div x-data x-cloak>
    <h2 class="mb-4 font-light capitalize text-primary-600">Liquidation Reports / Create Liquidation Report</h2>
    <form wire:submit.prevent='save' class="flex flex-col gap-4 p-4 bg-white rounded">
        <div>
            {{ $this->form }}
        </div>
    </form>
</div>
