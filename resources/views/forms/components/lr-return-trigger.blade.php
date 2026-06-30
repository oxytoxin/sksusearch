<div class="flex justify-end mt-2" wire:loading.remove wire:target="openReturnFromVerify">
    <button
        type="button"
        wire:click="openReturnFromVerify('{{ $recordId }}')"
        wire:loading.attr="disabled"
        wire:target="openReturnFromVerify"
        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-danger-600 border border-transparent rounded-lg shadow-sm hover:bg-danger-500 focus:outline-none focus:ring-2 focus:ring-danger-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
    >
        <x-ri-arrow-go-back-line class="w-4 h-4" />
        <span>Return Document</span>
    </button>
</div>
<div class="flex justify-end mt-2" wire:loading wire:target="openReturnFromVerify">
    <span class="text-sm text-gray-500 italic">Opening return form...</span>
</div>
