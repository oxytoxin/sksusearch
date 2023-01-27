<div>
<div class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
  <!--
    Background backdrop, show/hide based on modal state.

    Entering: "ease-out duration-300"
      From: "opacity-0"
      To: "opacity-100"
    Leaving: "ease-in duration-200"
      From: "opacity-100"
      To: "opacity-0"
  -->
  <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

  <div class="fixed inset-0 z-10 overflow-y-auto">
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
      <!--
        Modal panel, show/hide based on modal state.

        Entering: "ease-out duration-300"
          From: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          To: "opacity-100 translate-y-0 sm:scale-100"
        Leaving: "ease-in duration-200"
          From: "opacity-100 translate-y-0 sm:scale-100"
          To: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
      -->
      <form wire:submit.prevent='saveNumber'>
      <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pt-5 pb-3 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm sm:p-6">
        <div>
          <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
            <!-- Heroicon name: outline/check -->
            <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
            </svg>

          </div>
          <div class="mt-2 text-center sm:mt-3 ">
            <h1 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">Please enter your contact number first</h1>
            <div class="mt-2">
            <div class="relative mt-3 rounded-md border border-gray-300 px-3 py-2 shadow-sm focus-within:border-indigo-600 focus-within:ring-1 focus-within:ring-indigo-600">
            <input  wire:model.debounce="contact_number" type="number" name="name" id="name" autofocus class="text-center block w-full border-0 p-0 text-gray-900 placeholder-gray-500 focus:ring-0 sm:text-sm">
            </div>
            @error('contact_number') <span class="error text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
          </div>
        </div>
        <div class="mt-5 sm:mt-6 space-y-3">
          <x-filament-support::button type="submit" wire:target='saveNumber' class="w-full justify-center">Proceed</x-filament-support::button>
          <!-- <button wire:click="saveNumber" type="button" class="inline-flex w-full justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:text-sm">Proceed</button> -->
        </div>
      </div>
</form>
    </div>
  </div>
</div>
</div>
