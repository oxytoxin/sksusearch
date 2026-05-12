<div>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <div class="relative z-10" role="dialog" aria-labelledby="modal-title" aria-modal="true">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="flex-shrink-0 w-full max-w-lg" x-data="{ sig: null, activeTab: 'draw' }">
                    <div class="relative w-full flex-shrink-0 overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl">

                        {{-- Saving overlay --}}
                        <div wire:loading wire:target="saveSignature,saveUploadedSignature"
                            class="absolute inset-0 bg-white bg-opacity-95 flex items-center justify-center z-50 rounded-lg">
                            <div class="flex flex-col items-center gap-3">
                                <svg class="animate-spin h-10 w-10 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <p class="text-sm font-medium text-gray-700">Saving signature...</p>
                            </div>
                        </div>

                        {{-- Close button — only shown when the user already has a signature --}}
                        @if (auth()->user()->signature()->exists())
                            <a href="{{ route('requisitioner.dashboard') }}"
                                class="absolute top-2 right-3 text-gray-400 hover:text-gray-600 text-3xl leading-none focus:outline-none"
                                aria-label="Close and keep current signature"
                                title="Close and keep current signature">
                                &times;
                            </a>
                        @endif

                        <h3 class="text-lg font-bold mb-1">{{ auth()->user()->signature()->exists() ? 'Update E-Signature' : 'Create E-Signature' }}</h3>
                        <p class="text-xs text-gray-600 mb-3">
                            @if (auth()->user()->signature()->exists())
                                Saving will <strong>replace</strong> your current signature. Click <strong>&times;</strong> to cancel.
                            @else
                                Choose <strong>one</strong> of the options below to set up your signature. You can change it later from your profile.
                            @endif
                        </p>

                        {{-- Tabs --}}
                        <div class="flex gap-2 border-b border-gray-200 mb-4" role="tablist">
                            <button type="button" @click="activeTab = 'draw'"
                                role="tab"
                                :aria-selected="activeTab === 'draw' ? 'true' : 'false'"
                                title="Sign using your mouse, finger, or stylus"
                                :class="activeTab === 'draw'
                                    ? 'border-primary-600 text-primary-700'
                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="px-4 py-2 text-sm font-medium -mb-px border-b-2 transition-colors">
                                Draw Signature
                            </button>
                            <button type="button" @click="activeTab = 'upload'"
                                role="tab"
                                :aria-selected="activeTab === 'upload' ? 'true' : 'false'"
                                title="Upload a PNG or JPG image of your signature"
                                :class="activeTab === 'upload'
                                    ? 'border-primary-600 text-primary-700'
                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="px-4 py-2 text-sm font-medium -mb-px border-b-2 transition-colors">
                                Upload Signature
                            </button>
                        </div>

                        {{-- DRAW TAB --}}
                        <div x-show="activeTab === 'draw'" x-init="$nextTick(() => {
                            const canvas = document.querySelector('#signature');
                            if (canvas) {
                                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                                canvas.width = canvas.offsetWidth * ratio;
                                canvas.height = canvas.offsetHeight * ratio;
                                canvas.getContext('2d').scale(ratio, ratio);
                                sig = new SignaturePad(canvas);
                            }
                        })">
                            <p class="text-xs text-gray-600 mb-2">
                                Sign in the box below using your <strong>mouse, finger, or stylus</strong>.
                            </p>
                            <canvas class="block bg-red-200 w-full rounded" id="signature" style="height: 220px;"></canvas>
                            <div class="mt-3 flex items-center justify-evenly gap-4">
                                <x-filament-support::button class="w-full" type="button" color="danger" @click="sig.clear()">Clear</x-filament-support::button>
                                <x-filament-support::button class="w-full" type="button" wire:target="saveSignature" @click="$wire.saveSignature(sig.toDataURL('image/png'))">Save Drawing</x-filament-support::button>
                            </div>
                        </div>

                        {{-- UPLOAD TAB --}}
                        <div x-show="activeTab === 'upload'">
                            <p class="text-xs text-gray-600 mb-2">
                                PNG or JPG, max 2 MB. Transparent PNG works best.
                            </p>
                            <input type="file" accept="image/png,image/jpeg" wire:model="uploadedSignature"
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none p-2 mb-2">

                            <div wire:loading wire:target="uploadedSignature" class="text-xs text-gray-500 mb-2">
                                Uploading...
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
                                <span wire:loading wire:target="saveUploadedSignature">Saving...</span>
                            </x-filament-support::button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
