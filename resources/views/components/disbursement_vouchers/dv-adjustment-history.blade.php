@php
    $batches = $adjustments->groupBy('batch_id');
@endphp

@if($batches->isEmpty())
    <p class="text-sm text-gray-400">No adjustments have been made.</p>
@else
    <div class="space-y-4">
        @foreach($batches as $batchId => $batch)
            @php
                $first = $batch->first();
                $user = $first->adjusted_by_user;
            @endphp
            <div class="rounded-lg border border-gray-200 p-4">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-gray-500 uppercase">
                        {{ $first->created_at->format('M d, Y — h:i A') }}
                    </span>
                    <span class="text-xs text-gray-500">
                        by <strong>{{ $user?->employee_information?->full_name ?? 'Unknown' }}</strong>
                    </span>
                </div>
                <div class="space-y-2">
                    @foreach($batch as $adj)
                        <div class="flex items-start gap-3 text-sm">
                            <span class="inline-flex items-center rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-800 whitespace-nowrap">
                                {{ $adj->field }}
                            </span>
                            <div class="flex-1 min-w-0">
                                @if($adj->old_value && $adj->new_value)
                                    <span class="text-red-600 line-through">{{ $adj->old_value }}</span>
                                    <span class="mx-1 text-gray-400">→</span>
                                    <span class="text-green-700 font-medium">{{ $adj->new_value }}</span>
                                @elseif($adj->old_value)
                                    <span class="text-red-600">{{ $adj->old_value }}</span>
                                @elseif($adj->new_value)
                                    <span class="text-green-700 font-medium">{{ $adj->new_value }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endif
