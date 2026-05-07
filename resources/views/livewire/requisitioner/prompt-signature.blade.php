<div>
    <div class="relative z-10" role="dialog" aria-labelledby="modal-title" aria-modal="true">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="flex-shrink-0 w-full max-w-lg" x-data="{ sig: null }" x-init="sig = new SignaturePad(document.querySelector('#signature'))">
                    <div class="relative w-full flex-shrink-0 overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl">
                        <h3 class="text-lg font-bold mb-1">Create E-Signature</h3>
                        <p class="text-xs text-gray-600 mb-4">
                            Choose <strong>one</strong> of the options below to set up your signature. You can change it later from your profile.
                        </p>

                        {{-- Option 1: Draw --}}
                        <div class="mb-4">
                            <h4 class="font-semibold text-sm mb-1">Option 1 — Draw your signature</h4>
                            <ul class="text-xs text-gray-600 mb-2 list-disc list-inside space-y-0.5">
                                <li>Use your <strong>mouse</strong> on a computer, or your <strong>finger / stylus</strong> on a touch device.</li>
                                <li>Press <strong>Clear</strong> to start over, then <strong>Save Drawing</strong> when done.</li>
                            </ul>
                            <canvas class="mx-auto bg-red-200 w-full" id="signature" height="300"></canvas>
                            <div class="mt-3 flex items-center justify-evenly gap-4">
                                <x-filament-support::button class="w-full" type="button" color="danger" @click="sig.clear()">Clear</x-filament-support::button>
                                <x-filament-support::button class="w-full" type="button" wire:target="saveSignature" @click="$wire.saveSignature(sig.toDataURL('image/png'))">Save Drawing</x-filament-support::button>
                            </div>
                        </div>

                        {{-- OR Divider --}}
                        <div class="flex items-center my-4">
                            <div class="flex-grow border-t border-gray-300"></div>
                            <span class="mx-4 text-xs font-semibold text-gray-500">OR</span>
                            <div class="flex-grow border-t border-gray-300"></div>
                        </div>

                        {{-- Option 2: Upload --}}
                        <div>
                            <h4 class="font-semibold text-sm mb-1">Option 2 — Upload signature image</h4>
                            <ul class="text-xs text-gray-600 mb-2 list-disc list-inside space-y-0.5">
                                <li>Accepted formats: <strong>PNG</strong> or <strong>JPG</strong>. Max size: <strong>2MB</strong>.</li>
                                <li>For best results, use a <strong>PNG with transparent background</strong> so it overlays cleanly on documents.</li>
                                <li>Crop the image close to the signature itself — avoid extra white space.</li>
                            </ul>
                            <input type="file" accept="image/png,image/jpeg" wire:model="uploadedSignature"
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none p-2 mb-2">

                            <div wire:loading wire:target="uploadedSignature" class="text-xs text-gray-500 mb-2">
                                Uploading…
                            </div>

                            @error('uploadedSignature')
                                <p class="text-xs text-red-600 mb-2">{{ $message }}</p>
                            @enderror

                            @if ($uploadedSignature && !$errors->has('uploadedSignature'))
                                <div class="mb-2 p-2 border border-gray-200 rounded bg-gray-50">
                                    <p class="text-xs text-gray-600 mb-1">Preview:</p>
                                    <img src="{{ $uploadedSignature->temporaryUrl() }}" alt="Signature preview" class="max-h-32 mx-auto">
                                </div>
                            @endif

                            <x-filament-support::button class="w-full" type="button" wire:click="saveUploadedSignature" wire:loading.attr="disabled" wire:target="saveUploadedSignature">
                                <span wire:loading.remove wire:target="saveUploadedSignature">Save Uploaded Image</span>
                                <span wire:loading wire:target="saveUploadedSignature">Saving…</span>
                            </x-filament-support::button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
</div>
