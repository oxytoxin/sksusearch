<?php

namespace App\Forms\Components;

use Closure;
use Filament\Forms\Components\Concerns\HasExtraInputAttributes;
use Filament\Forms\Components\Concerns\HasPlaceholder;
use Filament\Forms\Components\Field;
use Filament\Support\Concerns\HasExtraAlpineAttributes;
use Illuminate\Contracts\Support\Arrayable;

class ArrayField extends Field
{
    use HasExtraInputAttributes;
    use HasPlaceholder;
    use HasExtraAlpineAttributes;
    protected string $view = 'forms.components.array-field';


    protected string | Closure | null $separator = null;

    protected array | Arrayable | Closure | null $suggestions = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->default([]);

        $this->afterStateHydrated(static function (ArrayField $component, $state): void {
            if (is_array($state)) {
                return;
            }

            if (!($separator = $component->getSeparator())) {
                $component->state([]);

                return;
            }

            $state = explode($separator, $state ?? '');

            if (count($state) === 1 && blank($state[0])) {
                $state = [];
            }

            $component->state($state);
        });

        $this->dehydrateStateUsing(static function (ArrayField $component, $state) {
            if ($separator = $component->getSeparator()) {
                return implode($separator, $state);
            }

            return $state;
        });

        $this->placeholder(__('forms::components.tags_input.placeholder'));
    }

    public function separator(string | Closure | null $separator = ','): static
    {
        $this->separator = $separator;

        return $this;
    }

    public function suggestions(array | Arrayable | Closure $suggestions): static
    {
        $this->suggestions = $suggestions;

        return $this;
    }

    public function getSeparator(): ?string
    {
        return $this->evaluate($this->separator);
    }

    public function getSuggestions(): array
    {
        $suggestions = $this->evaluate($this->suggestions ?? []);

        if ($suggestions instanceof Arrayable) {
            $suggestions = $suggestions->toArray();
        }

        return $suggestions;
    }
}
