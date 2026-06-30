<div>
    @if ($liquidation_report->related_documents && filled($liquidation_report->related_documents))
        @php($items = $liquidation_report->getRelatedDocumentItems())
        <h4 class="text-center capitalize">
            Liquidation Report for
            {{ str($liquidation_report->disbursement_voucher->voucher_subtype->voucher_type->name)->singular() }}
            ({{ $liquidation_report->disbursement_voucher->voucher_subtype->name }})</h4>
        <h5 class="mt-8 text-sm italic">Checklist for Documentary Requirements</h5>

        <div class="mt-2 flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-gray-600">
            <span class="font-semibold uppercase">Legend:</span>
            <span class="flex items-center gap-1"><x-ri-checkbox-circle-fill class="text-primary-400" /> Required (verified present)</span>
            <span class="flex items-center gap-1"><x-ri-close-circle-fill class="text-red-500" /> For Compliance</span>
            <span class="flex items-center gap-1"><x-ri-checkbox-indeterminate-fill class="text-gray-400" /> Not Applicable</span>
        </div>

        <ul class="mt-4 space-y-1">
            @forelse ($items as $item)
                <li class="flex gap-2">
                    @if ($item['status'] === 'required')
                        <x-ri-checkbox-circle-fill class="text-primary-400 shrink-0" />
                    @elseif ($item['status'] === 'not_applicable')
                        <x-ri-checkbox-indeterminate-fill class="text-gray-400 shrink-0" />
                    @else
                        <x-ri-close-circle-fill class="text-red-500 shrink-0" />
                    @endif
                    <span>
                        {{ $item['document'] }}
                        @if (filled($item['remarks'] ?? null))
                            <span class="block text-xs italic text-gray-500">{{ $item['remarks'] }}</span>
                        @endif
                    </span>
                </li>
            @empty
                <li>
                    No related documents required.
                </li>
            @endforelse
        </ul>
        <div class="mt-4 space-y-4">
            <h6>Remarks:</h6>
            @if (filled($liquidation_report->getRelatedDocumentsGeneralRemarks()))
                <div>
                    {!! $liquidation_report->getRelatedDocumentsGeneralRemarks() !!}
                </div>
            @else
                <p>No remarks.</p>
            @endif
        </div>
    @else
        <p>Liquidation Report documents are not yet verified by ICU.</p>
    @endif

</div>
