<div>
  <div x-cloak x-data="{
         activeTab: localStorage.getItem('activeTab') || 'basic'
       }"
       x-init="$watch('activeTab', value => localStorage.setItem('activeTab', value))">
    <div>
        <div class="sm:hidden">
          <label for="tabs" class="sr-only">Select a tab</label>
          <!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->
          <select id="tabs" name="tabs" class="block w-full rounded-md border-gray-300 focus:border-green-500 focus:ring-green-500" x-model="activeTab">
            <option selected>Basic Information</option>

            <option>User Guide</option>

            <option>Memos</option>

            <option>Tutorials</option>
          </select>
        </div>
        <div class="hidden sm:block">
          <div class="border-b border-gray-200">
            <nav class="-mb-px flex" aria-label="Tabs">
              <!-- Current: "border-green-500 text-green-600", Default: "border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" -->
              <a href="#" class="border-green-500 text-green-600 hover:text-gray-700 hover:border-gray-300 w-1/4 py-4 px-1 text-center border-b-2 font-bold text-sm"
                :class="{ 'border-green-500 text-green-600': activeTab === 'basic', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'basic' }"
                @click.prevent="activeTab = 'basic'" aria-current="page">Basic Information</a>

              <a href="#" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 w-1/4 py-4 px-1 text-center border-b-2 font-bold text-sm"
                :class="{ 'border-green-500 text-green-600': activeTab === 'guide', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'history' }"
                @click.prevent="activeTab = 'guide'">User Guide</a>

              <a href="#" class="border-transparent text-gray-500 w-1/4 py-4 px-1 text-center border-b-2 font-bold text-sm"
                :class="{ 'border-green-500 text-green-600': activeTab === 'memos', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'guide' }"
                @click.prevent="activeTab = 'memos'">Memos</a>

              <a href="#" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 w-1/4 py-4 px-1 text-center border-b-2 font-bold text-sm"
                :class="{ 'border-green-500 text-green-600': activeTab === 'tutorials', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'memos' }"
                @click.prevent="activeTab = 'tutorials'">Tutorials</a>
            </nav>
          </div>
        </div>
      </div>

      <div x-show="activeTab === 'basic'">
        <div class="flex justify-center items-center mt-52">
            <div class="animate-pulse relative block w-full rounded-lg border-2 border-dashed border-gray-300 text-center focus:outline-none">
                <svg class="mx-auto h-24 w-24 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  {{-- <x-button emerald label="Upload 5" wire:click="uploadPricesFifth" spinner="uploadPricesFifth" /> --}}
                <span class="mt-2 block text-2xl font-semibold text-gray-600">Content Coming Soon</span>
            </div>
        </div>
      </div>

      <div x-show="activeTab === 'guide'">
        <div class="flex justify-center items-center mt-52">
            <div class="animate-pulse relative block w-full rounded-lg border-2 border-dashed border-gray-300 text-center focus:outline-none">
                <svg class="mx-auto h-24 w-24 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                <span class="mt-2 block text-2xl font-semibold text-gray-600">Content Coming Soon</span>
            </div>
        </div>
      </div>

      <div x-show="activeTab === 'memos'">
        <div class="flex justify-center items-center mt-52">
            <div class="animate-pulse relative block w-full rounded-lg border-2 border-dashed border-gray-300 text-center focus:outline-none">
                <svg class="mx-auto h-24 w-24 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                <span class="mt-2 block text-2xl font-semibold text-gray-600">Content Coming Soon</span>
            </div>
        </div>
      </div>

      <div x-show="activeTab === 'tutorials'">
        <div class="italic py-2 underline">
            <span>Click an image to watch the tutorial video</span>
        </div>
        <div class="flex justify-center items-center">
            <div class="relative block w-full rounded-lg text-center focus:outline-none">

                   <livewire:requisitioner.tutorial-index />
                {{-- <span class="mt-2 block text-2xl font-semibold text-gray-600">Content Coming Soon</span> --}}
            </div>
        </div>
      </div>

    </div>
</div>
