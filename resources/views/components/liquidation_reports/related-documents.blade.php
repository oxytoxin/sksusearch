<div>
    @if ($voucher_subtype)
        <div class="space-y-4">
            <p class="text-sm italic tracking-wider">Below are the list of related documents required. Please ensure all
                documents are complete and valid before proceeding.</p>
            <ul class="list-disc pl-5">
                @forelse ($voucher_subtype->related_documents_list?->liquidation_report_documents ?? [] as $document)
                    <li>
                        {{ $document }}
                    </li>
                @empty
                    <li>
                        No related documents required.
                    </li>
                @endforelse
            </ul>
        </div>
    @endif
</div>
