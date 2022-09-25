<div>
    <h4>Create Travel Order</h4>
   <div class="mt-4">
    <form wire:submit.prevent='save'>
        <div>
            {{ $this->form }}
        </div>
        <x-filament-support::button type="submit" wire:target='save' class="mt-5 hover:bg-primary">Save</x-filament-support::button>
    </form>
   </div>
</div>
