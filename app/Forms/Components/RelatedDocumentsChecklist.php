<?php

namespace App\Forms\Components;

use Closure;
use Filament\Forms\Components\Concerns\CanBeValidated;
use Filament\Forms\Components\Field;

class RelatedDocumentsChecklist extends Field
{
    use CanBeValidated;

    protected string $view = 'forms.components.related-documents-checklist';

    protected array | Closure $documents = [];

    public function documents(array | Closure $documents): static
    {
        $this->documents = $documents;

        return $this;
    }

    public function getDocuments(): array
    {
        return $this->evaluate($this->documents);
    }
}
