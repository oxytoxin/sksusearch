<?php

namespace App\Http\Livewire\WFP;

use App\Models\CategoryGroup;
use App\Models\FundAllocation;
use App\Models\CostCenter;
use App\Models\WpfType;
use Livewire\Component;
use WireUi\Traits\Actions;
use Filament\Notifications\Notification;

class AllocateFunds extends Component
{
    use Actions;
    public $record;
    public $category_groups;
    public $wfp_type;
    public $selectedType;
    public $fundInitialAmount;
    public $fund_description;
    public $amounts = [];

    public function mount($record)
    {
        $this->record = CostCenter::find($record);
        $this->category_groups = CategoryGroup::all();
        $this->wfp_type = WpfType::all();
        $this->selectedType = "";
        $this->amounts = array_fill_keys($this->category_groups->pluck('id')->toArray(), 0);

    }



    public function calculateSubTotal($categoryGroupId)
    {
        // Return the amount associated with the given category group ID
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
            'description' => 'Do you really want to save this information?',
            'acceptLabel' => 'Yes, save it',
            'method'      => 'submitAllocation',
        ]);
    }

    public function submitAllocation()
    {
       if($this->selectedType === "")
       {
        Notification::make()->title('Please Select a WFP Type')->danger()->send();
       }

       //save the data
        foreach($this->amounts as $categoryGroupId => $amount)
        {
            FundAllocation::create([
                'cost_center_id' => $this->record->id,
                'wpf_type_id' => $this->selectedType,
                'fund_cluster_w_f_p_s_id' => $this->record->fundClusterWFP->id,
                'category_group_id' => $categoryGroupId,
                'initial_amount' => $amount,
            ]);
        }

        Notification::make()->title('Successfully Saved')->success()->send();
        return redirect()->route('wfp.fund-allocation');

    //    $this->validate([
    //        'amounts.*' => 'required|numeric|min:100'
    //    ],
    //    [
    //        'amounts.*.required' => 'The amount field is required',
    //        'amounts.*.numeric' => 'The amount field must be a number',
    //        'amounts.*.min' => 'The amount field must be at least 100'
    //    ]);

    }

    public function confirmAllocation161()
    {
        $this->dialog()->confirm([
            'title'       => 'Are you Sure?',
            'description' => 'Do you really want to save this information?',
            'acceptLabel' => 'Yes, save it',
            'method'      => 'submitAllocation161',
        ]);
    }

    public function submitAllocation161()
    {
        FundAllocation::create([
            'cost_center_id' => $this->record->id,
            'wpf_type_id' => $this->selectedType,
            'fund_cluster_w_f_p_s_id' => $this->record->fundClusterWFP->id,
            'initial_amount' => $this->fundInitialAmount,
            'description' => $this->fund_description
        ]);

        Notification::make()->title('Successfully Saved')->success()->send();
        return redirect()->route('wfp.fund-allocation');
    }

    public function render()
    {

        return view('livewire.w-f-p.allocate-funds', [
            'category_groups' => $this->category_groups
        ]);
    }
}
