@php
    $safeColors = $safeColors ?? false;
    $containerClass = $safeColors ? '' : 'bg-primary-200';
    $textClass = $safeColors ? '' : 'text-primary-500';
    $currentClass = $safeColors ? '' : 'bg-primary-600';
    $containerStyle = $safeColors ? 'background-color: #eef2ff;' : null;
    $textStyle = $safeColors ? 'color: #4338ca;' : null;
    $currentStyle = $safeColors ? 'background-color: #4f46e5;' : null;
@endphp

<div class="p-5 m-5 rounded-md {{ $containerClass }}" @if ($containerStyle) style="{{ $containerStyle }}" @endif>
    <ul class="space-y-1 {{ $textClass }} text-md" @if ($textStyle) style="{{ $textStyle }}" @endif>
        <li class="flex gap-2">
            <x-ri-checkbox-circle-fill class="w-5 h-5 shrink-0 text-indigo-600" />
            <span>Document created.</span>
        </li>
        @foreach ($steps as $step)
            @if ($record->previous_step_id == $step->id && $record->previous_step_id > $record->current_step_id)
                <li class="flex gap-2 text-red-700">
                    <x-ri-close-circle-fill class="w-5 h-5 shrink-0 text-red-500" />
                    <span
                        class="capitalize">{{ implode(' ', [$step->process, $step->recipient, $step->sender]) }}.</span>
                </li>
            @elseif ($record->current_step_id >= $step->id || $record->previous_step_id >= $step->id)
                @if ($record->current_step_id == $step->id)
                    @if ($record->for_cancellation)
                        <li class="flex gap-1 -ml-8 bg-red-600 rounded-md">
                            <x-ri-close-circle-fill class="w-5 h-5 shrink-0 text-white" />
                            <span class="text-white capitalize">Document requested for cancellation.</span>
                        </li>
                    @elseif (filled($record->pending_return_step_id))
                        <li class="flex gap-1 -ml-8 bg-red-600 rounded-md">
                            <x-ri-arrow-go-back-line class="w-5 h-5 shrink-0 text-white" />
                            <span class="text-white capitalize">Returned to {{ $record->pending_return_step->recipient ?? 'Unknown' }} by {{ $step->recipient ?? $step->sender }}. Awaiting release.</span>
                        </li>
                    @else
                        <li class="flex gap-1 -ml-8 rounded-md {{ $currentClass }}" @if ($currentStyle) style="{{ $currentStyle }}" @endif>
                            <x-ri-arrow-right-s-line class="w-5 h-5 shrink-0 text-white" />
                            <span
                                class="text-white capitalize">{{ implode(' ', [$step->process, $step->recipient, $step->sender]) }}.</span>
                        </li>
                    @endif
                @else
                    <li class="flex gap-2">
                        <x-ri-checkbox-circle-fill class="w-5 h-5 shrink-0 text-indigo-600" />
                        <span
                            class="capitalize">{{ implode(' ', [$step->process, $step->recipient, $step->sender]) }}.</span>
                    </li>
                @endif
            @else
                <li class="flex gap-2 text-gray-600">
                    <span
                        class="capitalize">{{ implode(' ', [$step->process, $step->recipient, $step->sender]) }}.</span>
                </li>
            @endif
        @endforeach
    </ul>
</div>
