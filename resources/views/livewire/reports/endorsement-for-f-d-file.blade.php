<div class="p-4 bg-white rounded-lg shadow">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-gray-800">Formal Demand Document</h2>
        <div class="text-sm text-gray-600">
            <span class="font-medium">Disbursement Voucher:</span> {{ $record->tracking_number ?? 'N/A' }}
            <span class="ml-4 font-medium">Accountable Person:</span> {{ $record->user->name ?? 'N/A' }}
        </div>
    </div>

    @if($record->cash_advance_reminder && $record->cash_advance_reminder->auditor_attachment)
        <div class="mb-4">
            <div class="border rounded-lg overflow-hidden relative">
                @php
                    $filePath = $record->cash_advance_reminder->auditor_attachment;
                    $fileUrl = Storage::disk('public')->url($filePath);
                @endphp
                <div id="pdf-loading" class="absolute inset-0 flex flex-col items-center justify-center bg-gray-100 z-10">
                    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-primary-500"></div>
                    <span class="ml-3 text-gray-700 mt-2">Loading document...</span>
                    <p class="text-xs text-gray-500 mt-2 max-w-xs text-center">PDF documents may take a moment to load depending on file size</p>
                </div>
                <iframe id="pdf-viewer" src="{{ $fileUrl }}#toolbar=0&view=FitH" width="100%" height="700px" style="border: none;" class="z-0" onload="document.getElementById('pdf-loading').style.display='none';" loading="lazy"></iframe>
            </div>
            <div class="mt-2 flex justify-between items-center">
                <div>
                    <a href="{{ $fileUrl }}" target="_blank" class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                        <i class="fas fa-external-link-alt mr-1"></i> Open in new tab
                    </a>
                </div>
                <div class="flex items-center">
                    <button onclick="resizeFrame('-')" class="px-2 py-1 bg-gray-200 hover:bg-gray-300 rounded text-sm mr-2">
                        <i class="fas fa-search-minus"></i>
                    </button>
                    <button onclick="resizeFrame('+')" class="px-2 py-1 bg-gray-200 hover:bg-gray-300 rounded text-sm">
                        <i class="fas fa-search-plus"></i>
                    </button>
                </div>
            </div>
        </div>
    @else
        <div class="p-6 bg-gray-100 rounded-lg text-center text-gray-600">
            <i class="fas fa-file-pdf text-4xl mb-3 text-gray-400"></i>
            <p>No auditor attachment available for this formal demand.</p>
        </div>
    @endif

    @if($record->cash_advance_reminder && $record->cash_advance_reminder->auditor_deadline)
        <div class="mt-4 p-3 bg-primary-100 border border-primary-200 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-calendar-alt text-primary-600 mr-2"></i>
                <div>
                    <h3 class=" text-primary-800">Response Deadline</h3>
                    <p class="text-primary-700">{{ \Carbon\Carbon::parse($record->cash_advance_reminder->auditor_deadline)->format('F d, Y') }}</p>
                    <p class="text-sm text-danger-600 mt-1">
                        {{ \Carbon\Carbon::now()->diffForHumans(\Carbon\Carbon::parse($record->cash_advance_reminder->auditor_deadline), ['parts' => 1]) }} remaining
                    </p>
                </div>
            </div>
        </div>
    @endif

    <script>
        function resizeFrame(action) {
            const frame = document.getElementById('pdf-viewer');
            let height = parseInt(frame.height);

            if (action === '+') {
                height += 100;
            } else if (action === '-' && height > 300) {
                height -= 100;
            }

            frame.height = height + 'px';
        }
    </script>
</div>
