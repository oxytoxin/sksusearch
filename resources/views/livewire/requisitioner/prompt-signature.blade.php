<div>
    <div class="relative z-10" role="dialog" aria-labelledby="modal-title" aria-modal="true">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="flex-shrink-0" x-data="{ sig: null }" x-init="sig = new SignaturePad(document.querySelector('#signature'))">
                    <div class="relative w-full flex-shrink-0 overflow-hidden rounded-lg bg-white px-4 pb-3 pt-5 text-left shadow-xl">
                        <h3>Create E-Signature</h3>
                        <canvas class="mx-auto bg-red-200" id="signature" height="300"></canvas>
                        <div class="flex items-stretch justify-stretch">
                        </div>
                        <div class="mt-5 flex items-center justify-evenly gap-4">
                            <x-filament-support::button class="w-full" type="button" color="danger" @click="sig.clear()">Clear</x-filament-support::button>
                            <x-filament-support::button class="w-full" type="button" wire:target="saveSignature" @click="$wire.saveSignature(sig.toDataURL('image/png'))">Save</x-filament-support::button>
                            <!-- <button class="inline-flex w-full justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:text-sm" type="button" wire:click="saveNumber">Proceed</button> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
</div>
