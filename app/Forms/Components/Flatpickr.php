<?php

namespace App\Forms\Components;

use Closure;
use Filament\Forms\Components\Concerns\CanBeValidated;
use Filament\Forms\Components\Concerns\HasPlaceholder;
use Filament\Forms\Components\Field;

class Flatpickr extends Field
{
    use CanBeValidated;
    use HasPlaceholder;

    protected bool | Closure $disableTime = false;

    protected bool | Closure $disableDate = false;

    protected string $view = 'forms.components.flatpickr';

    public function disableTime(bool | Closure $condition = true): static
    {
        $this->disableTime = $condition;

        return $this;
    }

    public function shouldDisableTime(): bool
    {
        return (bool) $this->evaluate($this->disableTime);
    }

    public function disableDate(bool | Closure $condition = true): static
    {
        $this->disableDate = $condition;

        return $this;
    }

    public function shouldDisableDate(): bool
    {
        return (bool) $this->evaluate($this->disableDate);
    }
}
