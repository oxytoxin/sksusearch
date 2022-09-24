<div class="flex flex-col items-center min-h-screen pt-6 bg-gray-100 sm:justify-center sm:pt-0">
    <div>
        {{ $logo }}
    </div>
    <div class="font-extrabold tracking-widest text-md text-primary md:text-2xl sm:text-md "><span class="uppercase">SKSU<span class="capitalize"> electronic archiving, retrieval <span class="lowercase">and</span> content handling</span></span></div>
    <div class="w-full px-6 py-4 mt-6 overflow-hidden bg-primary-400 shadow-md sm:max-w-md sm:rounded-lg">
        {{ $slot }}
    </div>
</div>
