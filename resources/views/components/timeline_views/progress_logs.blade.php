<div class="p-5 m-5 rounded-md bg-primary-200">
    <ul class="space-y-1 text-primary-500 text-md">
        <li class="flex gap-2">
            <x-ri-checkbox-circle-fill class="text-indigo-600" />
            <span>Document created.</span>
        </li>
        @foreach ($steps as $step)
            @if ($record->previous_step_id == $step->id && $record->previous_step_id > $record->current_step_id)
                <li class="flex gap-2 text-red-700">
                    <x-ri-close-circle-fill class="text-red-500" />
                    <span
                        class="capitalize">{{ implode(' ', [$step->process, $step->recipient, $step->sender]) }}.</span>
                </li>
            @elseif ($record->current_step_id >= $step->id || $record->previous_step_id >= $step->id)
                @if ($record->current_step_id == $step->id)
                    @if ($record->for_cancellation)
                        <li class="flex gap-1 -ml-8 bg-red-600 rounded-md">
                            <x-ri-close-circle-fill class="text-white" />
                            <span class="text-white capitalize">Document requested for cancellation.</span>
                        </li>
                    @else
                        <li class="flex gap-1 -ml-8 rounded-md bg-primary-600">
                            <x-ri-arrow-right-s-line class="text-white" />
                            <span
                                class="text-white capitalize">{{ implode(' ', [$step->process, $step->recipient, $step->sender]) }}.</span>
                        </li>
                    @endif
                @else
                    <li class="flex gap-2">
                        <x-ri-checkbox-circle-fill class="text-indigo-600" />
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
