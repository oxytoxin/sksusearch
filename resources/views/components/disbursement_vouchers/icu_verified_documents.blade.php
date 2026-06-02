<div>
    @if ($disbursement_voucher->related_documents && filled($disbursement_voucher->related_documents))
        <h4 class="text-center capitalize">{{ str($disbursement_voucher->voucher_subtype->voucher_type->name)->singular() }} for {{ $disbursement_voucher->voucher_subtype->name }}</h4>
        <h5 class="mt-8 text-sm italic">Checklist for Documentary Requirements</h5>
        <ul class="mt-4 space-y-2">
            @forelse ($disbursement_voucher->getRelatedDocumentItems() as $item)
                <li class="flex flex-col gap-1">
                    <div class="flex gap-2">
                        <span class="w-6 flex-shrink-0">
                            @switch($item['status'])
                                @case('required')
                                    <x-ri-checkbox-circle-fill class="text-primary-400" title="Required (verified)" />
                                    @break
                                @case('not_required')
                                    <x-ri-close-circle-fill class="text-red-500" title="For Compliance" />
                                    @break
                                @case('not_applicable')
                                    <x-ri-indeterminate-circle-fill class="text-gray-400" title="Not Applicable" />
                                    @break
                                @default
                                    <x-ri-question-fill class="text-gray-300" />
                            @endswitch
                        </span>
                        <span>
                            {{ $item['document'] }}
                            <span class="ml-2 text-xs italic text-gray-500">
                                @switch($item['status'])
                                    @case('required') (Required) @break
                                    @case('not_required') (For Compliance) @break
                                    @case('not_applicable') (Not Applicable) @break
                                @endswitch
                            </span>
                        </span>
                    </div>
                    @if (!empty($item['remarks']))
                        <div class="ml-8 text-xs italic text-gray-600">
                            Remark: {{ $item['remarks'] }}
                        </div>
                    @endif
                </li>
            @empty
                <li>
                    No related documents required.
                </li>
            @endforelse
        </ul>
        <div class="mt-4 space-y-4">
            <h6>General Remarks:</h6>
            @if (filled($disbursement_voucher->getRelatedDocumentsGeneralRemarks()))
                <div>
                    {!! $disbursement_voucher->getRelatedDocumentsGeneralRemarks() !!}
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
