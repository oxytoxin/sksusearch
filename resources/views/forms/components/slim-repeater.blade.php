<x-forms::field-wrapper class="p-4 bg-white rounded shadow" :id="$getId()" :label="$getLabel()" :label-sr-only="$isLabelHidden()" :helper-text="$getHelperText()" :hint="$getHint()" :hint-icon="$getHintIcon()" :required="$isRequired()" :state-path="$getStatePath()">

    @php
        $containers = $getChildComponentContainers();
        $isCollapsible = $isCollapsible();
        $isCloneable = $isCloneable();
        $isItemCreationDisabled = $isItemCreationDisabled();
        $isItemDeletionDisabled = $isItemDeletionDisabled();
        $isItemMovementDisabled = $isItemMovementDisabled();
        $hasItemLabels = $hasItemLabels();
        $labels = collect(
            collect($containers)
                ->first()
                ?->getComponents(),
        )
            ->map(function ($component) {
                return $component->getLabel();
            })
            ->toArray();
    @endphp
    <div {{ $attributes->merge($getExtraAttributes())->class(['filament-forms-repeater-component space-y-6 rounded-xl', 'bg-gray-50 p-6' => $isInset(), 'dark:bg-gray-500/10' => $isInset() && config('forms.dark_mode')]) }}>
        @if (count($containers))
            <ul>
                <x-filament-support::grid :default="$getGridColumns('default')" :sm="$getGridColumns('sm')" :md="$getGridColumns('md')" :lg="$getGridColumns('lg')" :xl="$getGridColumns('xl')" :two-xl="$getGridColumns('2xl')" wire:sortable
                    wire:end.stop="dispatchFormEvent('repeater::moveItems', '{{ $getStatePath() }}', $event.target.sortable.toArray())" class="gap-1">
                    <ul class="flex gap-6 px-12 justify-items-stretch">
                        @foreach ($labels as $label)
                            <li class="flex-1">{{ $label }}</li>
                        @endforeach
                    </ul>
                    @foreach ($containers as $uuid => $item)
                        <li x-data="{
                            isCollapsed: @js($isCollapsed()),
                        }" x-on:repeater-collapse.window="$event.detail === '{{ $getStatePath() }}' && (isCollapsed = true)" x-on:repeater-expand.window="$event.detail === '{{ $getStatePath() }}' && (isCollapsed = false)"
                            wire:key="{{ $this->id }}.{{ $item->getStatePath() }}.item" wire:sortable.item="{{ $uuid }}"
                            x-on:expand-concealing-component.window="
                                    error = $el.querySelector('[data-validation-error]')

                                    if (! error) {
                                        return
                                    }

                                    isCollapsed = false

                                    if (document.body.querySelector('[data-validation-error]') !== error) {
                                        return
                                    }

                                    setTimeout(() => $el.scrollIntoView({ behavior: 'smooth', block: 'start', inline: 'start' }), 200)
                                "
                            @class([
                                'bg-white rounded-xl relative',
                                'dark:bg-gray-800 dark:border-gray-600' => config('forms.dark_mode'),
                            ])>

                            <div class="flex gap-2" x-show="! isCollapsed">
                                @unless($isItemMovementDisabled)
                                    <button title="{{ __('forms::components.repeater.buttons.move_item.label') }}" x-on:click.stop wire:sortable.handle
                                        wire:keydown.prevent.arrow-up="dispatchFormEvent('repeater::moveItemUp', '{{ $getStatePath() }}', '{{ $uuid }}')"
                                        wire:keydown.prevent.arrow-down="dispatchFormEvent('repeater::moveItemDown', '{{ $getStatePath() }}', '{{ $uuid }}')" type="button" @class([
                                            'flex items-center justify-center flex-none w-10 h-10 text-gray-400 border rounded transition hover:text-gray-500',
                                            'dark:border-gray-700' => config('forms.dark_mode'),
                                        ])>
                                        <span class="sr-only">
                                            {{ __('forms::components.repeater.buttons.move_item.label') }}
                                        </span>

                                        <x-heroicon-s-switch-vertical class="w-4 h-4" />
                                    </button>
                                @endunless
                                <div class="flex-1">
                                    {{ $item }}
                                </div>
                                @unless($isItemDeletionDisabled)
                                    <button title="{{ __('forms::components.repeater.buttons.delete_item.label') }}" wire:click.stop="dispatchFormEvent('repeater::deleteItem', '{{ $getStatePath() }}', '{{ $uuid }}')" type="button"
                                        @class([
                                            'flex items-center justify-center flex-none w-10 h-10 text-danger-600 transition hover:text-danger-500',
                                            'dark:text-danger-500 dark:hover:text-danger-400' => config(
                                                'forms.dark_mode'
                                            ),
                                        ])>
                                        <span class="sr-only">
                                            {{ __('forms::components.repeater.buttons.delete_item.label') }}
                                        </span>

                                        <x-heroicon-s-trash class="w-4 h-4" />
                                    </button>
                                @endunless
                            </div>
                        </li>
                    @endforeach
                </x-filament-support::grid>
            </ul>
        @endif

        @if (!$isItemCreationDisabled)
            <div class="relative flex justify-end">
                <x-forms::button :wire:click="'dispatchFormEvent(\'repeater::createItem\', \'' . $getStatePath() . '\')'" size="sm" type="button">
                    {{ $getCreateItemButtonLabel() }}
                </x-forms::button>
            </div>
        @endif
    </div>

</x-forms::field-wrapper>
