<div class="grid cols-1">
    <div class="col-span-1 mx-auto">
       
        <figure id="my_figure" class="flex-col">
            <img src="https://api.qrserver.com/v1/create-qr-code/?data={{ $legacy_document->document_code }}&amp;size=100x100"
                download />
            <figcaption class="mx-2 text-xs font-bold tracking-wider text-center" id="leg_code">{{ $legacy_document->document_code }}</figcaption>
        </figure>
    </div>
    <a download href="https://api.qrserver.com/v1/create-qr-code/?data={{ $legacy_document->document_code }}"
        class="w-full p-2 mt-4 text-center text-white rounded-md bg-primary-600" download target="_blank">Download</a>
</div>

