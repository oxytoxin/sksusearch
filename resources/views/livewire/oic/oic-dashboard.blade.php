<div class="space-y-4">
    <div class="flex gap-4">
        <a class="underline" href="{{ route('oic.assign') }}">Assign OIC</a>
        <a class="underline" href="{{ route('oic.designations') }}">My OIC Designations</a>
    </div>
    <div x-data x-cloak>
        <h4 class="mb-4 text-lg font-semibold">Disbursement Vouchers</h4>
        {{ $this->table }}
    </div>
</div>
