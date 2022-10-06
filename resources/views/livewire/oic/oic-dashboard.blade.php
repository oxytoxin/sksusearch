<div class="space-y-4">
    <div class="flex gap-4">
        <a class="underline" href="{{ route('oic.assign') }}">Assign OIC</a>
        <a class="underline" href="{{ route('oic.designations') }}">My OIC Designations</a>
    </div>
    <div>
        <livewire:oic.oic-office-disbursement-vouchers />
    </div>
    <div>
        <livewire:oic.oic-signatory-disbursement-vouchers />
    </div>
    <div>
        <livewire:oic.oic-signatory-travel-orders />
    </div>
</div>
