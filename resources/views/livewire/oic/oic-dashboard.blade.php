<div class="space-y-4">
    <div class="flex gap-4">
        <a class="p-2 text-white rounded bg-primary-500 hover:bg-primary-700" href="{{ route('oic.assign') }}">Assign OIC</a>
        <a class="p-2 text-white rounded bg-primary-500 hover:bg-primary-700" href="{{ route('oic.designations') }}">My OIC Designations</a>
    </div>
    <div x-data="{ tab: 'oic_dv' }" x-cloak>
        <div class="inline-flex flex-col border-2 border-black divide-black md:divide-x-2 md:flex-row">
            <button @click="tab = 'oic_dv'" :class="tab == 'oic_dv' && 'bg-white'" class="p-2 text-lg font-semibold">Office Disbursement Vouchers</button>
            <button @click="tab = 'signatory_dv'" :class="tab == 'signatory_dv' && 'bg-white'" class="p-2 text-lg font-semibold">Signatory Disbursement Vouchers</button>
            <button @click="tab = 'signatory_to'" :class="tab == 'signatory_to' && 'bg-white'" class="p-2 text-lg font-semibold">Signatory Travel Orders</button>
        </div>
        <div x-show="tab == 'oic_dv'" class="p-4 bg-white">
            <livewire:oic.oic-office-disbursement-vouchers />
        </div>
        <div x-show="tab == 'signatory_dv'" class="p-4 bg-white">
            <livewire:oic.oic-signatory-disbursement-vouchers />
        </div>
        <div x-show="tab == 'signatory_to'" class="p-4 bg-white">
            <livewire:oic.oic-signatory-travel-orders />
        </div>
    </div>
</div>
