<div>
    <h4 class="text-lg font-semibold">Cash Advance Dashboard</h4>

    <div x-data="{ tab: 'reminders' }" x-cloak>
        <div class="mt-2 inline-flex flex-row">
            <button class="mt-2 flex items-center gap-2 rounded-t-lg px-4 py-2 text-lg font-semibold hover:bg-primary-300" @click="tab = 'reminders'" :class="tab == 'reminders' && 'bg-white -mt-2 text-primary-600'">
                Reminders
            </button>
            <button class="mt-2 rounded-t-lg px-4 py-2 text-lg font-semibold hover:bg-primary-300" @click="tab = 'history'" :class="tab == 'history' && 'bg-white -mt-2 text-primary-600'">
                Notification History
            </button>
        </div>
        <div class="origin-top-left bg-white p-4" x-show="tab === 'reminders'" :class="tab == 'reminders' && 'rounded-b-lg rounded-r-lg'" x-transition:enter='transform ease-out duration-200' x-transition:enter-start='scale-0' x-transition:enter-end='scale-100'>
            <div x-show="tab === 'reminders'" x-transition:enter='transition fade-in duration-700' x-transition:enter-start='opacity-0' x-transition:enter-end='opacity-100'>
                <div class="space-y-2">
                    <div class="flex">
                        <h2 class="font-light capitalize text-primary-600">Cash Advance Reminders</h2>
                    </div>
                    <div>
                        {{ $this->table }}
                    </div>
                </div>
            </div>
        </div>
        <div class="origin-[10%_0] bg-white p-4" x-show="tab === 'history'" :class="tab == 'history' && 'rounded-b-lg rounded-r-lg'" x-transition:enter='transform ease-out duration-200' x-transition:enter-start='scale-0' x-transition:enter-end='scale-100'>
            <div x-show="tab === 'history'" x-transition:enter='transition fade-in duration-700' x-transition:enter-start='opacity-0' x-transition:enter-end='opacity-100'>
                <livewire:requisitioner.disbursement-vouchers.sent-notification-history />
            </div>
        </div>
    </div>
</div>
