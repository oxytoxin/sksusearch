<div>
    @if ($disbursement_voucher->related_documents && filled($disbursement_voucher->related_documents))
        <h4 class="text-center capitalize">{{ str($disbursement_voucher->voucher_subtype->voucher_type->name)->singular() }} for {{ $disbursement_voucher->voucher_subtype->name }}</h4>
        <h5 class="mt-8 text-sm italic">Checklist for Documentary Requirements</h5>
        <ul class="mt-4">
            @forelse ($disbursement_voucher->voucher_subtype->related_documents_list?->documents as $document)
                <li class="flex gap-2">
                    <span class="w-6 flex-shrink-0">
                        @if (in_array($document, $disbursement_voucher->related_documents['verified_documents']))
                            <x-ri-checkbox-circle-fill class="text-primary-400" />
                        @else
                            <x-ri-close-circle-fill class="text-red-500" />
                        @endif
                    </span>
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
            @if ($disbursement_voucher->related_documents && filled($disbursement_voucher->related_documents['remarks']))
                <div>
                    {!! $disbursement_voucher->related_documents['remarks'] !!}
                </div>
            @else
                <p>No remarks.</p>
            @endif
        </div>
        @if (auth()->user()->employee_information->office->office_group_id == 3)
            <div class="mt-4">
                <a href="{{ route('icu.verified_documents', ['disbursement_voucher' => $disbursement_voucher]) }}" target="_blank">
                    <x-filament-support::button>View Report</x-filament-support::button>
                </a>
            </div>
        @endif
    @else
        <p>Disbursement Voucher documents are not yet verified by ICU.</p>
    @endif

</div>
