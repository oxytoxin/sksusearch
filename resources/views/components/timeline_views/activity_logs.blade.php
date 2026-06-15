@php
    $safeColors = $safeColors ?? false;
    $containerClass = $safeColors ? '' : 'bg-primary-100';
    $lineClass = $safeColors ? '' : 'bg-primary-700';
    $badgeClass = $safeColors ? '' : 'bg-primary-700';
    $containerStyle = $safeColors ? 'background-color: #eef2ff;' : null;
    $lineStyle = $safeColors ? 'background-color: #4338ca;' : null;
    $badgeStyle = $safeColors ? 'background-color: #4338ca; color: #ffffff;' : null;
@endphp

<div class="p-8 {{ $containerClass }}" @if ($containerStyle) style="{{ $containerStyle }}" @endif>
    <ul class="relative space-y-8 isolate">
        <div class="absolute w-1 h-full -z-10 left-8 {{ $lineClass }}"
             @if ($lineStyle) style="{{ $lineStyle }}" @endif></div>
        @foreach ($record->activity_logs as $log)
            <li>
                <div class="flex gap-2">
                    <div>
                        <div class="flex flex-col min-w-[8rem] items-center justify-center p-2 text-xs bg-white rounded whitespace-nowrap">
                            <p>{{ $log->created_at->format('h:i A') }}</p>
                            <p>{{ $log->created_at->format('F j, Y') }}</p>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center">
                            <h4 class="relative flex px-3 py-1 text-sm text-white whitespace-pre-line rounded md:rounded-full {{ $badgeClass }}"
                                @if ($badgeStyle) style="{{ $badgeStyle }}" @endif>
                                <p>{{ $log->description }}</p>
                            </h4>
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
