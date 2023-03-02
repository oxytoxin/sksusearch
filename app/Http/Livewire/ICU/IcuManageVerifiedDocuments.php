<?php

namespace App\Http\Livewire\ICU;

use App\Models\DisbursementVoucher;
use Livewire\Component;

class IcuManageVerifiedDocuments extends Component
{
    public DisbursementVoucher $disbursement_voucher;

    public function render()
    {
        return view('livewire.icu.icu-manage-verified-documents');
    }
}
