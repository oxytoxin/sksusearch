<div>
    @if ($liquidation_report->related_documents && filled($liquidation_report->related_documents))
        <h4 class="text-center capitalize">
            Liquidation Report for
            {{ str($liquidation_report->disbursement_voucher->voucher_subtype->voucher_type->name)->singular() }}
            ({{ $liquidation_report->disbursement_voucher->voucher_subtype->name }})</h4>
        <h5 class="mt-8 text-sm italic">Checklist for Documentary Requirements</h5>
        <ul class="mt-4">
            @forelse ($liquidation_report->disbursement_voucher->voucher_subtype->related_documents_list?->liquidation_report_documents as $document)
                <li class="flex gap-2">
                    @if (in_array($document, $liquidation_report->related_documents['verified_documents']))
                        <x-ri-checkbox-circle-fill class="text-primary-400" />
                    @else
                        <x-ri-close-circle-fill class="text-red-500" />
                    @endif
                    <span>{{ $document }}</span>
                </li>
            @empty
                <li>
                    No related documents required.
                </li>
            @endforelse
        </ul>
        <div class="mt-4 space-y-4">
            <h6>Remarks:</h6>
            @if ($liquidation_report->related_documents && filled($liquidation_report->related_documents['remarks']))
                <div>
                    {!! $liquidation_report->related_documents['remarks'] !!}
                </div>
            @else
                <p>No remarks.</p>
            @endif
        </div>
    @else
        <p>Liquidation Report documents are not yet verified by ICU.</p>
    @endif

</div>
