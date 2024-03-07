<div class="space-y-2">
    <div class="flex">
        <h2 class="font-light capitalize text-primary-600">Legacy Document Count</h2>
    </div>
    <div class="w-1/2 rounded-lg bg-white p-4">
        <div class="flex justify-between">
            <div>
                <x-filament-support::button wire:click="redirectBack">Back</x-filament-support::button>
            </div>
            <div class="flex space-x-4">
                <x-select label="Year" placeholder="" wire:model="year">
                    <x-select.option label="All" value="all" />
                    @foreach ($legacy_document_years as $item)
                    <x-select.option label="{{$item->year}}" value="{{$item->year}}" />
                    @endforeach
                </x-select>
            </div>
        </div>
        <div class="mt-10 font-medium capitalize text-primary-600 text-lg text-center">
            Total Count: {{$init_count}}
        </div>
    </div>
</div>
