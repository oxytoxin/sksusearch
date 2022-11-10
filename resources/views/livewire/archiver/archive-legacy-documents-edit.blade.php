<div x-data="{ codeSent: @entangle('codeSent'), invalidCode: @entangle('invalid') }" x-cloak>

    @if ($show_Edit == false)
    <div>
        <h2 class="mb-4 font-light capitalize text-primary-600">Archives/ Legacy / Edit Uploaded Information / Input Edit
            Verification Code</h2>
        <div class="flex" >
            <div class="flex flex-col p-5 rounded-lg shadow-md bg-primary-200 shadow-primary-600">
                <label for="enteredCode" class="block font-bold text-md text-primary-700">Verification Code</label>
                <div class="mt-1" x-show="codeSent"> 
                    <input type="text" name="enteredCode" id="enteredCode" wire:model.debounce.700ms="enteredCode" wire:key='asdafsdsdasdghjfas'
                        class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <span id="error" x-show="invalidCode" class="text-sm italic tracking-wide text-red-500">Code is invalid</span>
                </div>
                <div class="flex justify-end gap-2 mt-2">
                    <button x-show="codeSent" type="button" x-on:click="$wire.sendCode()" 
                        class="inline-flex items-center px-4 py-1 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        <!-- Heroicon name: mini/envelope -->
                        <svg class="w-4 h-4 mr-3 -ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                            aria-hidden="true">
                            <path d="M3 4a2 2 0 00-2 2v1.161l8.441 4.221a1.25 1.25 0 001.118 0L19 7.162V6a2 2 0 00-2-2H3z" />
                            <path d="M19 8.839l-7.77 3.885a2.75 2.75 0 01-2.46 0L1 8.839V14a2 2 0 002 2h14a2 2 0 002-2V8.839z" />
                        </svg>
                        Resend Code
                    </button>
                    <button x-show="codeSent == false" x-on:click="$wire.sendCode()"
                        class="inline-flex items-center px-4 py-1 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        <!-- Heroicon name: mini/envelope -->
                        <svg class="w-4 h-4 mr-3 -ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                            aria-hidden="true">
                            <path d="M3 4a2 2 0 00-2 2v1.161l8.441 4.221a1.25 1.25 0 001.118 0L19 7.162V6a2 2 0 00-2-2H3z" />
                            <path d="M19 8.839l-7.77 3.885a2.75 2.75 0 01-2.46 0L1 8.839V14a2 2 0 002 2h14a2 2 0 002-2V8.839z" />
                        </svg>
                        Send Code
                    </button>
                </div>
            </div>
        </div>
    </div>
    @else
    <div>
        <div class="flex justify-between mb-3">
            <h2 class="mb-4 font-light capitalize text-primary-600">Archives/ Legacy / Edit Uploaded Information</h2>
        <div>
            <x-filament-support::button type="submit" wire:target='save'
                class="float-right capitalize border border-primary-500 hover:text-primary-100 bg-primary-300 text-primary-800 hover:shadow-md hover:shadow-slate-700">
                Save updated Information</x-filament-support::button>
        </div>
        </div>
        <form wire:submit.prevent='save' class="flex flex-col gap-4">
          
            <div>
                {{ $this->form }}
            </div>
        </form>
    </div>
    @endif
</div>
