<div>
    @include('components.forms.ctc-preview', [
        'travel_order' => $ctc->travel_order,
        'ctc' => $ctc,
        'condition' => $ctc->condition,
        'explanation' => $ctc->explanation,
        'employee' => $ctc->user->employee_information->full_name,
        'supervisor' => $ctc->signatory->employee_information->full_name,
        'or_number' => $ctc->details ? $ctc->details['or_number'] : '',
        'or_date' => $ctc->details ? date_format(date_create($ctc->details['or_date']), 'F d, Y') : '',
        'refund_amount' => $ctc->details ? $ctc->details['or_amount'] : null,
    ])
</div>
