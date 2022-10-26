<?php

namespace App\Http\Livewire\Archiver;

use App\Models\LegacyDocument;
use Livewire\Component;

class ViewLgcScannedDocuments extends Component
{
    public $dv;
    public $scanned_documents;
    public function mount(LegacyDocument $disbursement_voucher)
    {
        $this->dv = $disbursement_voucher;
    }
    public function render()
    {
        return view('livewire.archiver.view-lgc-scanned-documents');
    }
}
