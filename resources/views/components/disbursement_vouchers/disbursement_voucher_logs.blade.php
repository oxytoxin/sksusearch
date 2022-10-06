<div class="p-8 bg-primary-100">
    <ul class="relative space-y-8 isolate">
        <div class="absolute w-1 h-full -z-10 left-8 bg-primary-700"></div>
        @foreach ($disbursement_voucher->activity_logs as $log)
            <li>
                <div class="flex gap-2">
                    <div>
                        <div class="flex flex-col items-center justify-center p-2 text-xs bg-white rounded whitespace-nowrap">
                            <p>{{ $log->created_at->format('h:i A') }}</p>
                            <p>{{ $log->created_at->format('F j, Y') }}</p>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex">
                            <h4 class="px-3 py-1 text-sm text-white whitespace-pre-line rounded md:rounded-full bg-primary-700">{{ $log->description }}</h4>
                        </div>
                        @if ($log->remarks)
                            <div class="p-2 mt-2 bg-white rounded shadow">
                                <h5>Remarks:</h5>
                                <p class="text-sm">{!! $log->remarks !!}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
</div>
