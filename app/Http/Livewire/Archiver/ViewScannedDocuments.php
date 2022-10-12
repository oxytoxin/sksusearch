<?php

namespace App\Http\Livewire\Archiver;

use App\Models\DisbursementVoucher;
use Livewire\Component;

class ViewScannedDocuments extends Component
{
    public $dv;
    public $scanned_documents;
    public function mount(DisbursementVoucher $disbursement_voucher)
    {
        $this->dv = $disbursement_voucher;
    }
    public function render()
    {
        return view('livewire.archiver.view-scanned-documents');
    }
}
