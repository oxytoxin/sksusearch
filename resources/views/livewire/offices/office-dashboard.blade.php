<div>
    <h4 class="text-lg font-semibold">Office Dashboard for {{ auth()->user()->employee_information?->office->name ?? 'Unknown Office' }}</h4>
    <div class="mt-4">
        <h3 class="mb-4 font-semibold">Disbursement Vouchers to Sign</h3>
        {{ $this->table }}
    </div>
    <div>
        <livewire:offices.office-disbursement-vouchers-index wire:key="office-disbursement-vouchers-index" />
    </div>
</div>
