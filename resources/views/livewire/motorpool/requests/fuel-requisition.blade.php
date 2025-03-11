<div>
    <div>
    {{ $this->form }}
</div>
<div class="mt-3 flex justify-end">
    <a href="{{ route('motorpool.request.fuel-requisition') }}"
        class="mr-1 px-3 py-2.5  bg-white rounded-md font-normal capitalize text-primary-600 text-sm">Cancel</a>
    <button wire:confirm="Are you sure you want to save this request?" wire:click="saveFuel" class="mr-1 px-3 py-2.5  bg-primary-600 rounded-md font-normal capitalize text-white text-sm">Save</button>
</div>
</div>
