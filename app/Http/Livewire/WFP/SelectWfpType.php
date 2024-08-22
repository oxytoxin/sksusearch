<?php

namespace App\Http\Livewire\WFP;

use App\Models\Office;
use App\Models\WpfType;
use Livewire\Component;
use App\Models\CostCenter;
use App\Models\FundAllocation;
use App\Models\FundClusterWFP;
use Illuminate\Support\Facades\Auth;

class SelectWfpType extends Component
{
    public $types;
    public $user_wfp_id;
    public $wfp;
    public $cost_center_id;
    public $office_id;
    public $total_amount;

    public function mount()
    {

        $this->user_wfp_id = Auth::user()->employee_information->office->cost_centers->first()->fundAllocations->first()->wpf_type_id;
        $this->wfp = WpfType::find($this->user_wfp_id);
        $this->cost_center_id = Auth::user()->employee_information->office->cost_centers->first()->id;
        $this->office_id = Auth::user()->employee_information->office->id;
        $this->types = FundClusterWFP::whereHas('costCenters', function($query) {
            $query->where('office_id', $this->office_id)->whereHas('fundAllocations', function($query) {
                $query->where('wpf_type_id',  $this->user_wfp_id);
            });
        })->get();

    }


    public function render()
    {
        return view('livewire.w-f-p.select-wfp-type');
    }
}
