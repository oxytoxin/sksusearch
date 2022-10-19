<div class="grid cols-1">
    <div class="col-span-1 mx-auto">
        <img src="https://api.qrserver.com/v1/create-qr-code/?data={{ $legacy_document->document_code }}&amp;size=100x100"
            download />
        <span class="mx-2 text-xs text-center">{{ $legacy_document->document_code }}</span>
    </div>
    <a download href="https://api.qrserver.com/v1/create-qr-code/?data={{ $legacy_document->document_code }}"
        class="w-full p-2 mt-4 text-center text-white rounded-md bg-primary-600">Download</a>
</div>
