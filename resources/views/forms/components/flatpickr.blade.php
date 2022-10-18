<x-forms::field-wrapper :id="$getId()" :label="$getLabel()" :label-sr-only="$isLabelHidden()" :helper-text="$getHelperText()" :hint="$getHint()" :hint-icon="$getHintIcon()" :required="$isRequired()" :state-path="$getStatePath()">
    <div wire:ignore>
        @php
            $altFormat = '';
            if (!$shouldDisableDate()) {
                $altFormat = 'F j, Y ';
            }
            if (!$shouldDisableTime()) {
                $altFormat .= 'h:i K';
            }
        @endphp
        <input type="text" placeholder="{{ $getPlaceholder() }}"
            {{ $attributes->class([
                'block w-full transition duration-75 rounded-lg shadow-sm focus:border-primary-600 focus:ring-1 focus:ring-inset focus:ring-primary-600 disabled:opacity-70',
                'dark:bg-gray-700 dark:text-white dark:focus:border-primary-600' => config('forms.dark_mode'),
                'border-gray-300' => !$errors->has($getStatePath()),
                'dark:border-gray-600' => !$errors->has($getStatePath()) && config('forms.dark_mode'),
                'border-danger-600 ring-danger-600' => $errors->has($getStatePath()),
            ]) }}
            x-data="{
                value: @entangle($getStatePath()),
                instance: undefined,
                init() {
                    $watch('value', value => this.instance.setDate(value, false));
                    this.instance = flatpickr($el, {
                        enableTime: {{ $shouldDisableTime() ? 'false' : 'true' }},
                        noCalendar: {{ $shouldDisableDate() ? 'true' : 'false' }},
                        altInput: true,
                        altFormat: '{{ $altFormat }}',
                        defaultDate: this.value,
                        disableMobile: true
                    });
                }
            }" x-model.throttle="value" />
    </div>
</x-forms::field-wrapper>
