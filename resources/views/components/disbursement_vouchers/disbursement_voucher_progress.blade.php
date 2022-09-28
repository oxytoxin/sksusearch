<div>
    <ul>
        <li class="flex gap-2">
            <x-ri-checkbox-circle-fill />
            <span>Disbursement Voucher created.</span>
        </li>
        @foreach ($steps as $step)
            <li class="flex gap-2">
                @if ($disbursement_voucher->current_step_id >= $step->id || $disbursement_voucher->previous_step_id >= $step->id)
                    <x-ri-checkbox-circle-fill />
                @endif
                <span>{{ implode(' ', [$step->process, $step->recipient, $step->sender]) }}</span>
            </li>
        @endforeach
    </ul>
</div>
