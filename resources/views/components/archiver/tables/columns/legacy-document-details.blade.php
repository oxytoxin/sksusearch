<div class="overflow-hidden bg-white shadow sm:rounded-lg">
	<div class="px-4 py-5 sm:px-6">
		<h3 class="text-lg font-medium leading-6 text-gray-900">Document Information</h3>
		<p class="mt-1 max-w-2xl text-sm text-gray-500">Archived document details and attachments.</p>
	</div>
	<div class="border-t border-gray-200 px-4 py-5 sm:px-6">
		<dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
			<div class="sm:col-span-1">
				<dt class="text-sm font-medium text-gray-500">Document Code</dt>
				<dd class="mt-1 text-sm text-gray-900">{{ $legacy_document->document_code }}</dd>
			</div>
			<div class="sm:col-span-1">
				<dt class="text-sm font-medium text-gray-500">Disbursement Voucher Number</dt>
				<dd class="mt-1 text-sm text-gray-900">{{ $legacy_document->dv_number }}</dd>
			</div>
			<div class="sm:col-span-1">
				<dt class="text-sm font-medium text-gray-500">Document Category</dt>
				<dd class="mt-1 text-sm text-gray-900 capitalize">
					@switch($legacy_document->document_category )
					@case('1')
						disbursment voucher
					@break

					@case('2')
						liquidation report
					@break

					@default
					@endswitch
				</dd>
			</div>
			<div class="sm:col-span-1">
				<dt class="text-sm font-medium text-gray-500">Payee</dt>
				<dd class="mt-1 text-sm text-gray-900">{{ $legacy_document->payee_name }}</dd>
			</div>
			<div class="sm:col-span-1">
				<dt class="text-sm font-medium text-gray-500">Journal Date</dt>
				<dd class="mt-1 text-sm text-gray-900">{{ $legacy_document->journal_date }}</dd>
			</div>
			<div class="sm:col-span-1">
				<dt class="text-sm font-medium text-gray-500">Fund Cluster</dt>
				<dd class="mt-1 text-sm text-gray-900">{{ $legacy_document->fund_cluster->name }}</dd>
			</div>
			<div class="sm:col-span-2">
				<dt class="text-md font-medium text-gray-700">Cheque Details</dt>
				<div class="grid grid-cols-2">
					<div class="col-span-1">
						<dt class="mt-1 text-sm font-medium text-gray-500">Cheque Number</dt>
						<dd class="text-sm text-gray-900">{{ $legacy_document->cheque_number }} </dd>
					</div>
					<div class="col-span-1">
						<dt class="mt-1 text-sm font-medium text-gray-500">Cheque Date</dt>
						<dd class=" text-sm text-gray-900">{{ $legacy_document->cheque_date }} </dd>
					</div>
					<div class="col-span-1">
						<dt class="mt-1 text-sm font-medium text-gray-500">Cheque Amount</dt>
						<dd class="text-sm text-gray-900">{{ $legacy_document->cheque_amount }} </dd>
					</div>
					<div class="col-span-1">
						<dt class="mt-1 text-sm font-medium text-gray-500">Cheque State</dt>
						<dd class=" text-sm text-gray-900 capitalize">
							@switch($legacy_document->cheque_state)
								@case('1')
									Encashed
								@break

								@case('2')
									Cancelled
								@break
								@case('3')
									Stale
								@break

								@default
							@endswitch
						</dd>
					</div>
				</div>
			</div>
			<div class="sm:col-span-2">
				<dt class="text-sm font-medium text-gray-500">Particulars</dt>
				@foreach ($legacy_document->particulars as $particular)
					@foreach ($particular as $particular_content)
					<dd class="mt-1 text-sm text-gray-900">
						{{$particular_content}}
					</dd>
					@endforeach
				@endforeach
			</div>
			<div class="sm:col-span-2">
				<dt class="text-sm font-medium text-gray-500">Attachments</dt>
				<dd class="mt-1 text-sm text-gray-900">
					<ul role="list" class="divide-y divide-gray-200 rounded-md border border-gray-200">
						@foreach ($legacy_document->scanned_documents as $scanned_document)
							
							<li class="flex items-center justify-between py-3 pl-3 pr-4 text-sm">
								<div class="flex w-0 flex-1 items-center">
									<!-- Heroicon name: mini/paper-clip -->
									<svg class="h-5 w-5 flex-shrink-0 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
										fill="currentColor" aria-hidden="true">
										<path fill-rule="evenodd"
											d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z"
											clip-rule="evenodd" />
									</svg>
									<span class="ml-2 w-0 flex-1 truncate">{{ $scanned_document->document_name }}</span>
								</div>
								<div class="ml-4 flex-shrink-0">
									<a href="{{ asset('storage/'.$scanned_document->path) }}" target="_blank" class="font-medium text-indigo-600 hover:text-indigo-500">View Attachment</a>
								</div>
							</li>
						@endforeach
					</ul>
				</dd>
			</div>
		</dl>
	</div>
</div>
