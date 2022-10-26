<div class="space-y-4">
    <div class="flex gap-4">
        <a class="p-2 text-white rounded bg-primary-500 hover:bg-primary-700" href="{{ route('oic.assign') }}">Assign OIC</a>
        <a class="p-2 text-white rounded bg-primary-500 hover:bg-primary-700" href="{{ route('oic.designations') }}">My OIC
            Designations</a>
    </div>
    <div x-data="{ tab: 'oic_dv' }" x-cloak>
        <div class="inline-flex flex-col mt-2 md:flex-row">
            <button @click="tab = 'oic_dv'" :class="tab == 'oic_dv' && 'bg-white -mt-2 text-primary-600'" class="px-4 py-2 mt-2 text-lg font-semibold rounded-t-lg hover:bg-primary-300">Office Disbursement Vouchers</button>
            <button @click="tab = 'signatory_dv'" :class="tab == 'signatory_dv' && 'bg-white -mt-2 text-primary-600'" class="px-4 py-2 mt-2 text-lg font-semibold rounded-t-lg hover:bg-primary-300">Signatory Disbursement
                Vouchers</button>
            <button @click="tab = 'signatory_to'" :class="tab == 'signatory_to' && 'bg-white -mt-2 text-primary-600'" class="px-4 py-2 mt-2 text-lg font-semibold rounded-t-lg hover:bg-primary-300">Signatory Travel Orders</button>
        </div>
        <div x-show="tab == 'oic_dv'" class="p-4 origin-top-left bg-white" :class="tab == 'oic_dv' && 'rounded-b-lg rounded-r-lg'" x-transition:enter='transform ease-out duration-200' x-transition:enter-start='scale-0'
            x-transition:enter-end='scale-100'>
            <div x-show="tab == 'oic_dv'" x-transition:enter='transition fade-in duration-700' x-transition:enter-start='opacity-0' x-transition:enter-end='opacity-100'>
                <livewire:oic.oic-office-disbursement-vouchers />
            </div>
        </div>
        <div x-show="tab == 'signatory_dv'" :class="tab == 'signatory_dv' && 'rounded-b-lg rounded-r-lg'" class="origin-[10%_0] bg-white p-4" x-transition:enter='transform ease-out duration-200' x-transition:enter-start='scale-0'
            x-transition:enter-end='scale-100'>
            <div x-show="tab == 'signatory_dv'" x-transition:enter='transition fade-in duration-700' x-transition:enter-start='opacity-0' x-transition:enter-end='opacity-100'>
                <livewire:oic.oic-signatory-disbursement-vouchers />
            </div>
        </div>
        <div x-show="tab == 'signatory_to'" :class="tab == 'signatory_to' && 'rounded-b-lg rounded-r-lg'" class="origin-[45%_0] bg-white p-4" x-transition:enter='transform ease-out duration-200' x-transition:enter-start='scale-0'
            x-transition:enter-end='scale-100'>
            <div x-show="tab == 'signatory_to'" x-transition:enter='transition fade-in duration-700' x-transition:enter-start='opacity-0' x-transition:enter-end='opacity-100'>
                <livewire:oic.oic-signatory-travel-orders />
            </div>
        </div>
    </div>
</div>
