<div class="mt-4">
    <div class="mb-2 w-full rounded-xl border border-gray-300 bg-white p-5 shadow-sm">

        <div class="flex rounded-md py-1">
            <div class="w-full">
                <div class="flex">
                    <input class="w-full rounded-md py-1" id="tracking_num_from_scan" id="tracking_num_from_scan" name="tracking_num_from_scan" type="text" x-ref="tracking_num_from_scan" wire:model.lazy="tracking_num_from_scan" placeholder="Click me to start Scan-to-Receive">
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
