
<div class="space-y-2">
    <div class="flex justify-between items-center">
        <h2 class="font-light capitalize text-primary-600">View WFP History</h2>
    </div>
    <div x-data="{ tab: 'wfp' }" x-cloak>
        <div class="mt-2 inline-flex flex-row">
            <button class="mt-2 flex items-center gap-2 rounded-t-lg px-4 py-2 text-lg font-semibold hover:bg-primary-300" @click="tab = 'wfp'" :class="tab == 'wfp' && 'bg-white -mt-2 text-primary-600'">
                WFP
            </button>
            <button class="mt-2 rounded-t-lg px-4 py-2 text-lg font-semibold hover:bg-primary-300" @click="tab = 'q1'" :class="tab == 'q1' && 'bg-white -mt-2 text-primary-600'">
                Supplemental Q1
            </button>
        </div>
        <div class="origin-top-left bg-white p-4" x-show="tab === 'wfp'" :class="tab == 'wfp' && 'rounded-b-lg rounded-r-lg'" x-transition:enter='transform ease-out duration-200' x-transition:enter-start='scale-0' x-transition:enter-end='scale-100'>
            <div x-show="tab === 'wfp'" x-transition:enter='transition fade-in duration-700' x-transition:enter-start='opacity-0' x-transition:enter-end='opacity-100'>
                <div>
                    <div>
                        {{ $this->table }}
                    </div>
                </div>
            </div>
        </div>

        <div class="origin-[10%_0] bg-white p-4" x-show="tab === 'q1'" :class="tab == 'q1' && 'rounded-b-lg rounded-r-lg'" x-transition:enter='transform ease-out duration-200' x-transition:enter-start='scale-0' x-transition:enter-end='scale-100'>
            <div x-show="tab === 'q1'" x-transition:enter='transition fade-in duration-700' x-transition:enter-start='opacity-0' x-transition:enter-end='opacity-100'>
                <livewire:w-f-p.wfp-history-q1 />
            </div>
        </div>
    </div>
</div>
