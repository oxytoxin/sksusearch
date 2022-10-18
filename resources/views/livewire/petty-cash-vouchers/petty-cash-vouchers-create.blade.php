<div>
    <form x-data x-cloak wire:submit.prevent="save">
        {{ $this->form }}
        <div class="flex justify-end mt-4">
            <x-filament::button wire:target="save" type="submit">
                Save
            </x-filament::button>
        </div>
    </form>
</div>
