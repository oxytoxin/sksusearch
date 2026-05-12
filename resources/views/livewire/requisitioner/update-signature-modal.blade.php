<div>
    {{-- Load SignaturePad once with the page so it's ready before the modal opens --}}
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>

    {{-- Smart Upload: Alpine component for AI background removal --}}
    <script>
        window.smartSignatureUpload = function () {
            return {
                state: 'idle',           // idle | processing | result | error
                dragging: false,
                status: '',
                detail: '',
                originalUrl: null,
                cleanedUrl: null,
                cleanedDataUrl: null,
                elapsed: '0.0',
                removeBackground: null,
                _startTime: 0,

                async init() {
                    // Lazy-load the @imgly background removal module on first tab open
                    if (window.__imglyBgRemoval) {
                        this.removeBackground = window.__imglyBgRemoval;
                        return;
                    }
                    try {
                        const mod = await import('https://cdn.jsdelivr.net/npm/@imgly/background-removal@1.6.0/+esm');
                        window.__imglyBgRemoval = mod.removeBackground;
                        this.removeBackground = mod.removeBackground;
                    } catch (e) {
                        console.error('Failed to load background-removal module', e);
                    }
                },

                async handleFile(file) {
                    if (!file) return;
                    if (!file.type.startsWith('image/')) {
                        this.state = 'error';
                        this.status = 'Please choose an image file (PNG or JPG).';
                        return;
                    }
                    if (file.size > 2 * 1024 * 1024) {
                        this.state = 'error';
                        this.status = 'Image size must not exceed 2MB.';
                        return;
                    }
                    if (!this.removeBackground) {
                        this.state = 'error';
                        this.status = 'Background removal not available. Check your internet connection and try again.';
                        return;
                    }

                    this.originalUrl = URL.createObjectURL(file);
                    this.state = 'processing';
                    this.status = 'Loading AI model…';
                    this.detail = 'First run downloads ~40MB (cached after)';
                    this._startTime = performance.now();

                    try {
                        const blob = await this.removeBackground(file, {
                            debug: false,
                            progress: (key, current, total) => {
                                const phase = (key || '').split(':')[0];
                                if (phase === 'fetch') {
                                    const pct = total ? Math.round((current / total) * 100) : 0;
                                    this.status = 'Loading AI model…';
                                    this.detail = 'Downloading weights · ' + pct + '%';
                                } else if (phase === 'compute') {
                                    this.status = 'Removing background…';
                                    this.detail = 'Running inference';
                                }
                            },
                            output: { format: 'image/png', quality: 1.0 }
                        });

                        // Refine alpha edges (same easing used in reference)
                        const refined = await this._smoothEdges(blob);

                        this.cleanedUrl = URL.createObjectURL(refined);
                        this.cleanedDataUrl = await this._blobToDataUrl(refined);
                        this.elapsed = ((performance.now() - this._startTime) / 1000).toFixed(1);
                        this.state = 'result';
                    } catch (err) {
                        console.error(err);
                        this.state = 'error';
                        this.status = 'Could not process image: ' + (err.message || 'unknown error');
                    }
                },

                _blobToDataUrl(blob) {
                    return new Promise((resolve, reject) => {
                        const reader = new FileReader();
                        reader.onloadend = () => resolve(reader.result);
                        reader.onerror = reject;
                        reader.readAsDataURL(blob);
                    });
                },

                _smoothEdges(blob) {
                    return new Promise((resolve) => {
                        const img = new Image();
                        img.onload = () => {
                            const canvas = document.createElement('canvas');
                            canvas.width = img.naturalWidth;
                            canvas.height = img.naturalHeight;
                            const c = canvas.getContext('2d');
                            c.drawImage(img, 0, 0);
                            const imgData = c.getImageData(0, 0, canvas.width, canvas.height);
                            const data = imgData.data;
                            for (let i = 3; i < data.length; i += 4) {
                                const a = data[i];
                                if (a < 12) data[i] = 0;
                                else if (a > 243) data[i] = 255;
                                else {
                                    const t = (a - 12) / (243 - 12);
                                    const eased = t * t * (3 - 2 * t);
                                    data[i] = Math.round(eased * 255);
                                }
                            }
                            c.putImageData(imgData, 0, 0);
                            canvas.toBlob((b) => resolve(b || blob), 'image/png');
                        };
                        img.onerror = () => resolve(blob);
                        img.src = URL.createObjectURL(blob);
                    });
                },

                reset() {
                    if (this.originalUrl) URL.revokeObjectURL(this.originalUrl);
                    if (this.cleanedUrl) URL.revokeObjectURL(this.cleanedUrl);
                    this.originalUrl = null;
                    this.cleanedUrl = null;
                    this.cleanedDataUrl = null;
                    this.status = '';
                    this.detail = '';
                    this.elapsed = '0.0';
                    this.state = 'idle';
                    this.$refs.fileInput.value = '';
                },
            };
        };
    </script>

    @if ($showModal)
        <div class="relative z-10" role="dialog" aria-labelledby="update-signature-title" aria-modal="true">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0">
                    <div class="flex-shrink-0 w-full max-w-lg">
                        <div class="relative w-full flex-shrink-0 overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl">

                            {{-- Saving overlay — covers whole modal while a save is in flight --}}
                            <div wire:loading wire:target="saveSignature,saveUploadedSignature"
                                class="absolute inset-0 bg-white bg-opacity-95 flex items-center justify-center z-50 rounded-lg">
                                <div class="flex flex-col items-center gap-3">
                                    <svg class="animate-spin h-10 w-10 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <p class="text-sm font-medium text-gray-700">Saving signature…</p>
                                    <p class="text-xs text-gray-500">Please wait, the page will refresh.</p>
                                </div>
                            </div>

                            {{-- Close (X) --}}
                            <button type="button" wire:click="closeModal"
                                wire:loading.attr="disabled" wire:target="saveSignature,saveUploadedSignature"
                                class="absolute top-2 right-3 text-gray-400 hover:text-gray-600 text-3xl leading-none focus:outline-none"
                                aria-label="Close and keep current signature"
                                title="Close and keep current signature">
                                &times;
                            </button>

                            <h3 id="update-signature-title" class="text-lg font-bold mb-1">Update E-Signature</h3>
                            <p class="text-xs text-gray-600 mb-3">
                                Saving will <strong>replace</strong> your current signature. Click <strong>×</strong> to cancel.
                            </p>

                            {{-- Tabs --}}
                            <div class="flex gap-2 border-b border-gray-200 mb-4" role="tablist">
                                <button type="button" wire:click="setActiveTab('draw')"
                                    role="tab"
                                    aria-selected="{{ $activeTab === 'draw' ? 'true' : 'false' }}"
                                    title="Sign using your mouse, finger, or stylus"
                                    class="px-4 py-2 text-sm font-medium -mb-px border-b-2 transition-colors
                                        {{ $activeTab === 'draw'
                                            ? 'border-primary-600 text-primary-700'
                                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                    ✏️ Draw Signature
                                </button>
                                <button type="button" wire:click="setActiveTab('upload')"
                                    role="tab"
                                    aria-selected="{{ $activeTab === 'upload' ? 'true' : 'false' }}"
                                    title="Upload a PNG or JPG image of your signature"
                                    class="px-4 py-2 text-sm font-medium -mb-px border-b-2 transition-colors
                                        {{ $activeTab === 'upload'
                                            ? 'border-primary-600 text-primary-700'
                                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                    📁 Upload Signature
                                </button>
                                <button type="button" wire:click="setActiveTab('smart')"
                                    role="tab"
                                    aria-selected="{{ $activeTab === 'smart' ? 'true' : 'false' }}"
                                    title="Upload signature and automatically remove the background"
                                    class="px-4 py-2 text-sm font-medium -mb-px border-b-2 transition-colors
                                        {{ $activeTab === 'smart'
                                            ? 'border-primary-600 text-primary-700'
                                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                    🪄 Smart Upload
                                </button>
                            </div>

                            {{-- DRAW TAB --}}
                            @if ($activeTab === 'draw')
                                <div wire:key="draw-tab" x-data="{ sig: null }"
                                    x-init="$nextTick(() => {
                                        const canvas = document.querySelector('#update-signature-canvas');
                                        const ratio = Math.max(window.devicePixelRatio || 1, 1);
                                        canvas.width = canvas.offsetWidth * ratio;
                                        canvas.height = canvas.offsetHeight * ratio;
                                        canvas.getContext('2d').scale(ratio, ratio);
                                        sig = new SignaturePad(canvas);
                                    })">
                                    <p class="text-xs text-gray-600 mb-2">
                                        Sign in the box below using your <strong>mouse, finger, or stylus</strong>.
                                    </p>
                                    <canvas class="block bg-red-200 w-full rounded" id="update-signature-canvas" style="height: 220px;"></canvas>
                                    <div class="mt-3 flex items-center justify-evenly gap-4">
                                        <x-filament-support::button class="w-full" type="button" color="danger"
                                            @click="sig.clear()">Clear</x-filament-support::button>
                                        <x-filament-support::button class="w-full" type="button" wire:target="saveSignature"
                                            @click="$wire.saveSignature(sig.toDataURL('image/png'))">Save Drawing</x-filament-support::button>
                                    </div>
                                </div>
                            @endif

                            {{-- UPLOAD TAB --}}
                            @if ($activeTab === 'upload')
                                <div wire:key="upload-tab" x-data="{ dragging: false }">
                                    <p class="text-xs text-gray-600 mb-2">
                                        PNG or JPG, max 2&nbsp;MB. Transparent PNG works best.
                                    </p>

                                    {{-- IDLE: drop zone (shown when no file selected and not uploading) --}}
                                    @if (!$uploadedSignature)
                                        <div
                                            @click="$refs.uploadFileInput.click()"
                                            @dragover.prevent="dragging = true"
                                            @dragleave.prevent="dragging = false"
                                            @drop.prevent="dragging = false; $refs.uploadFileInput.files = $event.dataTransfer.files; $refs.uploadFileInput.dispatchEvent(new Event('change'))"
                                            :class="dragging ? 'border-primary-500 bg-primary-50' : 'border-gray-300 bg-gray-50'"
                                            class="border-2 border-dashed rounded-lg p-6 text-center cursor-pointer hover:bg-gray-100 transition-colors mb-3">
                                            <div class="text-4xl mb-2">📁</div>
                                            <p class="text-sm font-medium text-gray-700">Drop signature image here</p>
                                            <p class="text-xs text-gray-500 mt-1">or click to select · PNG or JPG · max 2MB</p>
                                        </div>
                                    @endif

                                    <input type="file" x-ref="uploadFileInput" hidden accept="image/png,image/jpeg"
                                        wire:model="uploadedSignature">

                                    {{-- UPLOADING --}}
                                    <div wire:loading wire:target="uploadedSignature" class="py-8 text-center">
                                        <svg class="animate-spin h-8 w-8 mx-auto text-primary-600 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <p class="text-sm font-medium text-gray-700">Uploading…</p>
                                    </div>

                                    @error('uploadedSignature')
                                        <div wire:loading.remove wire:target="uploadedSignature" class="py-4">
                                            <p class="text-sm text-red-600 mb-2">{{ $message }}</p>
                                            <button type="button" wire:click="$set('uploadedSignature', null)"
                                                class="text-xs text-primary-600 underline">
                                                Try again
                                            </button>
                                        </div>
                                    @enderror

                                    {{-- PREVIEW: after upload succeeds --}}
                                    @if ($uploadedSignature && !$errors->has('uploadedSignature'))
                                        <div wire:loading.remove wire:target="uploadedSignature">
                                            <p class="text-xs text-gray-500 text-center mb-1">Preview</p>
                                            <div class="border border-gray-200 rounded p-2 h-32 flex items-center justify-center mb-3"
                                                style="background-image: linear-gradient(45deg, #e5e7eb 25%, transparent 25%), linear-gradient(-45deg, #e5e7eb 25%, transparent 25%), linear-gradient(45deg, transparent 75%, #e5e7eb 75%), linear-gradient(-45deg, transparent 75%, #e5e7eb 75%); background-size: 12px 12px; background-position: 0 0, 0 6px, 6px -6px, 6px 0;">
                                                <img src="{{ $uploadedSignature->temporaryUrl() }}" alt="Signature preview"
                                                    class="max-h-full max-w-full object-contain">
                                            </div>

                                            <div class="flex gap-2">
                                                <x-filament-support::button class="w-full" type="button" color="secondary"
                                                    wire:click="$set('uploadedSignature', null)"
                                                    wire:loading.attr="disabled" wire:target="saveUploadedSignature">
                                                    Choose Another
                                                </x-filament-support::button>
                                                <x-filament-support::button class="w-full" type="button"
                                                    wire:click="saveUploadedSignature" wire:loading.attr="disabled"
                                                    wire:target="saveUploadedSignature">
                                                    <span wire:loading.remove wire:target="saveUploadedSignature">Save Image</span>
                                                    <span wire:loading wire:target="saveUploadedSignature">Saving…</span>
                                                </x-filament-support::button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            {{-- SMART UPLOAD TAB --}}
                            @if ($activeTab === 'smart')
                                <div wire:key="smart-tab" x-data="smartSignatureUpload()" x-init="init()">
                                    <p class="text-xs text-gray-600 mb-2">
                                        Upload your signature and we'll <strong>auto-remove the background</strong>.
                                        Works best with signatures on white paper.
                                    </p>

                                    {{-- IDLE: drop zone --}}
                                    <div x-show="state === 'idle'"
                                        @click="$refs.fileInput.click()"
                                        @dragover.prevent="dragging = true"
                                        @dragleave.prevent="dragging = false"
                                        @drop.prevent="dragging = false; handleFile($event.dataTransfer.files[0])"
                                        :class="dragging ? 'border-primary-500 bg-primary-50' : 'border-gray-300 bg-gray-50'"
                                        class="border-2 border-dashed rounded-lg p-6 text-center cursor-pointer hover:bg-gray-100 transition-colors">
                                        <div class="text-4xl mb-2">🪄</div>
                                        <p class="text-sm font-medium text-gray-700">Drop signature image here</p>
                                        <p class="text-xs text-gray-500 mt-1">or click to select · PNG or JPG · max 2MB</p>
                                    </div>
                                    <input type="file" x-ref="fileInput" hidden accept="image/png,image/jpeg"
                                        @change="handleFile($event.target.files[0])">

                                    {{-- PROCESSING: show progress --}}
                                    <div x-show="state === 'processing'" class="py-8 text-center">
                                        <svg class="animate-spin h-8 w-8 mx-auto text-primary-600 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <p class="text-sm font-medium text-gray-700" x-text="status"></p>
                                        <p class="text-xs text-gray-500 mt-1" x-text="detail"></p>
                                    </div>

                                    {{-- ERROR --}}
                                    <div x-show="state === 'error'" class="py-4">
                                        <p class="text-sm text-red-600 mb-2" x-text="status"></p>
                                        <button type="button" @click="reset()" class="text-xs text-primary-600 underline">
                                            Try again
                                        </button>
                                    </div>

                                    {{-- RESULT: before/after preview --}}
                                    <div x-show="state === 'result'" x-cloak>
                                        <div class="grid grid-cols-2 gap-2 mb-3">
                                            <div>
                                                <p class="text-xs text-gray-500 text-center mb-1">Original</p>
                                                <div class="border border-gray-200 rounded bg-gray-50 p-2 h-32 flex items-center justify-center">
                                                    <img :src="originalUrl" alt="Original signature"
                                                        class="max-h-full max-w-full object-contain">
                                                </div>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 text-center mb-1">Background Removed</p>
                                                <div class="border border-gray-200 rounded p-2 h-32 flex items-center justify-center"
                                                    style="background-image: linear-gradient(45deg, #e5e7eb 25%, transparent 25%), linear-gradient(-45deg, #e5e7eb 25%, transparent 25%), linear-gradient(45deg, transparent 75%, #e5e7eb 75%), linear-gradient(-45deg, transparent 75%, #e5e7eb 75%); background-size: 12px 12px; background-position: 0 0, 0 6px, 6px -6px, 6px 0;">
                                                    <img :src="cleanedUrl" alt="Cleaned signature"
                                                        class="max-h-full max-w-full object-contain">
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-xs text-green-600 mb-3" x-text="'✓ Processed in ' + elapsed + 's'"></p>

                                        <div class="flex gap-2">
                                            <x-filament-support::button class="w-full" type="button" color="secondary"
                                                @click="reset()">
                                                Try Another
                                            </x-filament-support::button>
                                            <x-filament-support::button class="w-full" type="button"
                                                @click="$wire.saveSignature(cleanedDataUrl)">
                                                Save Cleaned Signature
                                            </x-filament-support::button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
