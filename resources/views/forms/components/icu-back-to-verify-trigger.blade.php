<div class="flex items-center justify-start">
    <button
        type="button"
        wire:click="backToVerifyFromReturn('{{ $recordId }}')"
        wire:loading.attr="disabled"
        wire:target="backToVerifyFromReturn"
        class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
    >
        <x-ri-arrow-left-line class="w-4 h-4" />
        <span>Back to Verify Documents</span>
    </button>
</div>
