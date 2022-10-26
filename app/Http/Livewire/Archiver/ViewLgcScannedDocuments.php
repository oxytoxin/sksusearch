<?php

namespace App\Http\Livewire\Archiver;

use App\Models\LegacyDocument;
use Livewire\Component;

class ViewLgcScannedDocuments extends Component
{
    public $dv;
    public $scanned_documents;
    public function mount(LegacyDocument $legacy_document)
    {
        $this->dv = $legacy_document;
    }
    public function render()
    {
        return view('livewire.archiver.view-lgc-scanned-documents');
    }
}
