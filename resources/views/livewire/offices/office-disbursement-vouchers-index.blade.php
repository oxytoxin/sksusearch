<div class="mt-4">
	<div class="w-full p-5 mb-2 bg-white border border-gray-300 shadow-sm rounded-xl">
		
		<div class="flex py-1 rounded-md">
			<div class="w-full">
				<div class="flex">
					<input x-ref="tracking_num_from_scan" type="text" id="tracking_num_from_scan" class="w-full py-1 rounded-md"
						name="tracking_num_from_scan" wire:model.debounce.750ms="tracking_num_from_scan" id="tracking_num_from_scan" placeholder="Click me to start Scan-to-Recieve">
				</div>
			</div>
		</div>
		<div class="flex">
            <div class="ml-1">
                <p class="text-blue-600"><strong class="italic">Note:</strong>  Ifit's not working, try clicking the text box above before scanning the qr. </p>
            </div>
		</div>
	</div>
	{{ $this->table }}
</div>
