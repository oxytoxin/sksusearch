<?php

namespace App\Http\Livewire\WFP;

use App\Models\CategoryGroup;
use App\Models\FundAllocation;
use App\Models\CostCenter;
use App\Models\WpfType;
use Livewire\Component;
use WireUi\Traits\Actions;
use Filament\Notifications\Notification;

class EditAllocateFunds extends Component
{
    use Actions;
    public $record;
    public $category_groups;
    public $wfp_type;
    public $selectedType;
    public $fundInitialAmount;
    public $fund_description;
    public $amounts = [];

    public function mount($record, $wfpType)
    {
        $this->record = CostCenter::find($record);
        $this->category_groups = CategoryGroup::where('is_active', 1)->get();
        $this->wfp_type = WpfType::all();
        $this->selectedType = $this->record->fundAllocations->where('wpf_type_id', $wfpType)->first()->wpf_type_id;
        $this->fundInitialAmount = $this->record->fundAllocations->where('wpf_type_id', $this->selectedType)->first()->initial_amount;
        $this->fund_description = $this->record->fundAllocations->first()->description;
       // $this->amounts = array_fill_keys($this->category_groups->pluck('id')->toArray(), 0);

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



    public function confirmAllocation()
    {
        $this->dialog()->confirm([
            'title'       => 'Are you Sure?',
            'description' => 'Do you really want to update this information?',
            'acceptLabel' => 'Yes, update it',
            'method'      => 'submitAllocation',
        ]);
    }

    public function submitAllocation()
    {
       if($this->selectedType === "" || $this->selectedType === null)
       {
        Notification::make()->title('Please Select a WFP Period')->danger()->send();
       }else{
                //save the data
                foreach($this->amounts as $categoryGroupId => $amount)
                {
                    $fundAllocation = FundAllocation::where('cost_center_id', $this->record->id)
                        ->where('wpf_type_id', $this->selectedType)
                        ->where('category_group_id', $categoryGroupId)
                        ->first();

                    if ($fundAllocation) {
                        $fundAllocation->update([
                            'initial_amount' => $amount,
                        ]);
                    } else {
                        FundAllocation::create([
                            'cost_center_id' => $this->record->id,
                            'wpf_type_id' => $this->selectedType,
                            'fund_cluster_w_f_p_s_id' => $this->record->fundClusterWFP->id,
                            'category_group_id' => $categoryGroupId,
                            'initial_amount' => $amount,
                        ]);
                    }
                }

                Notification::make()->title('Successfully Updated')->success()->send();
                return redirect()->route('wfp.fund-allocation', ['filter' => $this->record->fundClusterWFP->id]);
       }

    }

    public function confirmAllocation161()
    {
        $this->dialog()->confirm([
            'title'       => 'Are you Sure?',
            'description' => 'Do you really want to update this information?',
            'acceptLabel' => 'Yes, save it',
            'method'      => 'submitAllocation161',
        ]);
    }

    public function submitAllocation161()
    {
        if($this->selectedType === "" || $this->selectedType === null)
        {
         Notification::make()->title('Please Select a WFP Period')->danger()->send();
        }else{
            $this->validate([
                'fundInitialAmount' => 'required|numeric|min:100',
                'fund_description' => 'required'
            ],
            [
                'fundInitialAmount.required' => 'The amount field is required',
                'fundInitialAmount.numeric' => 'The amount field must be a number',
                'fundInitialAmount.min' => 'The amount field must be at least 100',
                'fund_description.required' => 'The description field is required'

            ]);

            $fundAllocation = FundAllocation::where('cost_center_id', $this->record->id)
                ->where('wpf_type_id', $this->selectedType)
                ->first();

            if ($fundAllocation) {
                $fundAllocation->update([
                    'initial_amount' => $this->fundInitialAmount,
                    'description' => $this->fund_description
                ]);
            }

            Notification::make()->title('Successfully Updated')->success()->send();
            return redirect()->route('wfp.fund-allocation', ['filter' => $this->record->fundClusterWFP->id]);
        }

    }


    public function render()
    {
        return view('livewire.w-f-p.edit-allocate-funds', [
            'category_groups' => $this->category_groups
        ]);
    }
}
