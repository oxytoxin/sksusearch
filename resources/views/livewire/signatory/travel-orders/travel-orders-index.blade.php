<div class="space-y-2">
    <div class="flex">
        <h2 class="font-light capitalize text-primary-600">Travel Orders / Signatory Travel Orders</h2>
    </div>
    <div x-data="{ tab: 'for_signature' }" x-cloak>
        <div class="mt-2 inline-flex flex-row">
            <button class="mt-2 flex items-center gap-2 rounded-t-lg px-4 py-2 text-lg font-semibold hover:bg-primary-300" @click="tab = 'for_signature'" :class="tab == 'for_signature' && 'bg-white -mt-2 text-primary-600'">
                For Signature
            </button>
            <button class="mt-2 flex items-center gap-2 rounded-t-lg px-4 py-2 text-lg font-semibold hover:bg-primary-300" @click="tab = 'signed'" :class="tab == 'signed' && 'bg-white -mt-2 text-primary-600'">
                Signed
            </button>
        </div>
        <div class="origin-top-left bg-white p-4" x-show="tab == 'for_signature'" :class="tab == 'for_signature' && 'rounded-b-lg rounded-r-lg'" x-transition:enter='transform ease-out duration-200' x-transition:enter-start='scale-0' x-transition:enter-end='scale-100'>
            <div x-show="tab == 'for_signature'" x-transition:enter='transition fade-in duration-700' x-transition:enter-start='opacity-0' x-transition:enter-end='opacity-100'>
                {{ $this->table }}
            </div>
        </div>
        <div class="origin-[10%_0] bg-white p-4" x-show="tab == 'signed'" :class="tab == 'signed' && 'rounded-b-lg rounded-r-lg'" x-transition:enter='transform ease-out duration-200' x-transition:enter-start='scale-0' x-transition:enter-end='scale-100'>
            <div x-show="tab == 'signed'" x-transition:enter='transition fade-in duration-700' x-transition:enter-start='opacity-0' x-transition:enter-end='opacity-100'>
                <livewire:signatory.travel-orders.travel-orders-signed wire:key="travel-orders-signed" />
            </div>
        </div>
    </div>
</div>
