<div class="mt-4">
    <div class="w-full p-5 mb-2 bg-white border border-gray-300 shadow-sm rounded-xl">

        <div class="flex py-1 rounded-md">
            <div class="w-full">
                <div class="flex">
                    <input class="w-full py-1 rounded-md" id="tracking_num_from_scan" id="tracking_num_from_scan" name="tracking_num_from_scan" type="text" x-ref="tracking_num_from_scan"
                           wire:model.debounce.750ms="tracking_num_from_scan" placeholder="Click me to start Scan-to-Receive">
                </div>
            </div>
        </div>
        <div class="flex">
            <div class="ml-1">
                <p class="text-blue-600"><strong class="italic">Note:</strong> If it's not working, try clicking the text box above before scanning the qr. </p>
            </div>
        </div>
    </div>
    {{ $this->table }}
</div>
