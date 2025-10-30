<?php

namespace App\Http\Livewire\WFP;

use Livewire\Component;
use App\Models\CategoryGroup;
use App\Models\FundAllocation;
use App\Models\CostCenter;
use App\Models\WpfType;

class ViewAllocatedFund extends Component
{
    public $record;
    public $category_groups;
    public $wfp_type;
    public $selectedType;
    public $fundInitialAmount;
    public $fund_description;
    public $amounts = [];

    public $is_supplemental = false;

    public $supplementalQuarterId;

   protected $queryString = ['is_supplemental','supplementalQuarterId'];

    public function mount($record, $wfpType)
    {
        $this->record = CostCenter::find($record)->load(['fundAllocations'=>function($query) {
            $query->when(!is_null($this->supplementalQuarterId), function ($query) {
                $query->where('supplemental_quarter_id', $this->supplementalQuarterId);
            })
            ->when(is_null($this->supplementalQuarterId), function ($query) {
                $query->where('is_supplemental', 0);
            });
        }]);
        $this->category_groups = CategoryGroup::where('is_active', 1)->get();
        $this->wfp_type = WpfType::all();
        $this->selectedType = $this->record->fundAllocations->where('wpf_type_id', $wfpType)->first()->wpf_type_id;
        $this->fundInitialAmount = $this->record->fundAllocations->where('wpf_type_id', $this->selectedType)->first()->initial_amount;
        $this->fund_description = $this->record->fundAllocations->where('wpf_type_id', $this->selectedType)->first()->description;

        foreach ($this->record->fundAllocations->where('wpf_type_id', $wfpType) as $allocation) {
            $this->amounts[$allocation->category_group_id] = $allocation->initial_amount;
        }
    }

    public function calculateSubTotal($categoryGroupId)
    {
        // Return the amount associated with the given category group ID
        // if($this->amounts[$categoryGroupId] < 0)
        // {
        //     $this->amounts[$categoryGroupId] = 0;
        // }else
        // {
        //     $this->amounts[$categoryGroupId] = $this->amounts[$categoryGroupId];
        // }
        return $this->amounts[$categoryGroupId] ?? 0;
    }

    public function calculateTotal()
    {
        // Calculate the total of all amounts
        return array_sum($this->amounts);
    }

    public function render()
    {
        return view('livewire.w-f-p.view-allocated-fund');
    }
}
