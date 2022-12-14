<div class="space-y-2">
    <div class="flex">
        <h2 class="font-light capitalize text-primary-600">Archives</h2>
    </div>
    <div x-data="{ tab: 'leg_docs' }" x-cloak>
        <div class="inline-flex flex-col mt-2 md:flex-row">
            <button @click="tab = 'nl_docs'" :class="tab == 'nl_docs' && 'bg-white -mt-2 text-primary-600'" class="px-4 py-2 mt-2 text-lg font-semibold rounded-t-lg hover:bg-primary-300">Non-legacy documents</button>
            <button @click="tab = 'leg_docs'" :class="tab == 'leg_docs' && 'bg-white -mt-2 text-primary-600'" class="px-4 py-2 mt-2 text-lg font-semibold rounded-t-lg hover:bg-primary-300">Legacy Documents</button>
        </div>
        <div x-show="tab == 'nl_docs'" class="p-4 origin-top-left bg-white" :class="tab == 'nl_docs' && 'rounded-b-lg rounded-r-lg'"
        x-transition:enter = 'transform ease-out duration-200'
        x-transition:enter-start =  'scale-0'
        x-transition:enter-end =  'scale-100'>
            <div x-show = "tab == 'nl_docs'" 
            x-transition:enter = 'transition fade-in duration-700'
            x-transition:enter-start =  'opacity-0'
            x-transition:enter-end =  'opacity-100' >
                {{ $this->table }}
            </div>
        </div>
        <div x-show="tab == 'leg_docs'"  :class="tab == 'leg_docs' && 'rounded-b-lg rounded-r-lg'" class="p-4 origin-[10%_0] bg-white"
        x-transition:enter = 'transform ease-out duration-200'
        x-transition:enter-start =  'scale-0'
        x-transition:enter-end =  'scale-100'>
            <div x-show ="tab == 'leg_docs'"
            x-transition:enter = 'transition fade-in duration-700'
            x-transition:enter-start =  'opacity-0'
            x-transition:enter-end =  'opacity-100'>
            @livewire('archiver.view-legacy-documents',[''])
            </div>
        </div>
    </div>
</div>
