<?php

namespace App\Http\Livewire\Shared;

use App\Models\TravelCompletedCertificate;
use Livewire\Component;

class TravelCompletedCertificatePrint extends Component
{
    public TravelCompletedCertificate $ctc;

    public function render()
    {
        return view('livewire.shared.travel-completed-certificate-print');
    }
}
