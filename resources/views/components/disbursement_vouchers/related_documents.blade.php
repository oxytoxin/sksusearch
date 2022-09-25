<div>
    @php
        $voucher_subtype = $getLivewire()->voucher_subtype;
    @endphp

    <div class="space-y-4">
        <p class="text-sm italic tracking-wider">Below are the list of related documents for this voucher. Please ensure all documents are complete and valid before proceeding.</p>
        <ul>
            @forelse ($voucher_subtype->related_documents_list->documents as $document)
                <li>
                    {{ $document }}
                </li>
            @empty
                <li>
                    No related documents required for this voucher.
                </li>
            @endforelse
        </ul>
    </div>
</div>
