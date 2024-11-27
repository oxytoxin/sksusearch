<?php

namespace App\Http\Livewire\WFP;

use Livewire\Component;
use App\Models\Wfp;

class DeactivatedPricelists extends Component
{
    public $record;
    
    public function mount()
    {
        $this->record =  Wfp::with([
            'costCenter',
            'user',
            'wfpDetails'
        ])->whereHas('wfpDetails.supply', function ($query) {
            $query->where('is_active', 0);
        })->get();
        // $this->record = Wfp::whereHas('wfpDetails', function($query) {
        //     $query->whereHas('supply', function ($query) {
        //         $query->where('is_active', 0);
        //     });
        // })->get();
    }

    public function render()
    {
        return view('livewire.w-f-p.deactivated-pricelists');
    }
}
