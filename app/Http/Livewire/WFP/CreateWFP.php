<?php

namespace App\Http\Livewire\WFP;

use App\Models\Wfp;
use Filament\Forms;
use App\Models\Supply;
use Livewire\Component;
use App\Models\CostCenter;
use WireUi\Traits\Actions;
use App\Models\CategoryGroup;
use App\Models\CategoryItems;
use App\Models\FundClusterWFP;
use App\Models\WfpDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Actions\Action;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;

class CreateWFP extends Component implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;
    use Actions;

    public $record;
    public $wfp_type;
    public $wfp_fund;
    public $data;
    public $costCenter;

    public $total_quantity;
    public $supply_cost;

    public $total_quantity2;
    public $supply_cost2;

    public $total_quantity3;
    public $supply_cost3;

    public $total_quantity4;
    public $supply_cost4;

    public $total_quantity5;
    public $supply_cost5;

    public $global_index;
    //table arrays
    public $supplies = [];
    public $mooe = [];
    public $trainings = [];
    public $machines = [];
    public $buildings = [];
    public $grouped_arrays = [];
    public $asyncSearchUser;

    public $fund_allocations;
    public $current_balance;
    //step 1
    public $fund_description;
    public $source_fund;
    public $confirm_fund_source;
    public $specify_fund_source;
    public $is_misc = false;
    //step 2
    public $supplies_particulars;
    public $supplies_particular_id;
    public $supplies_category_attr;
    public $supplies_uacs;
    public $supplies_title_group;
    public $supplies_account_title;
    public $supplies_quantity = [];
    public $supplies_total_quantity;
    public $supplies_ppmp;
    public $supplies_uom;
    public $supplies_cost_per_unit;
    public $supplies_estimated_budget;
    //step 3
     public $mooe_particulars;
     public $mooe_particular_id;
     public $mooe_category_attr;
     public $mooe_uacs;
     public $mooe_title_group;
     public $mooe_account_title;
     public $mooe_quantity = [];
     public $mooe_total_quantity;
     public $mooe_ppmp;
     public $mooe_uom;
     public $mooe_cost_per_unit;
     public $mooe_estimated_budget;
     //step 4
     public $training_particulars;
     public $training_particular_id;
     public $training_category_attr;
     public $training_uacs;
     public $training_title_group;
     public $training_account_title;
     public $training_quantity = [];
     public $training_total_quantity;
     public $training_ppmp;
     public $training_uom;
     public $training_cost_per_unit;
     public $training_estimated_budget;
    //step 5
    public $machine_particulars;
    public $machine_particular_id;
    public $machine_category_attr;
    public $machine_uacs;
    public $machine_title_group;
    public $machine_account_title;
    public $machine_quantity = [];
    public $machine_total_quantity;
    public $machine_ppmp;
    public $machine_uom;
    public $machine_cost_per_unit;
    public $machine_estimated_budget;
    //step 6
     public $building_particulars;
     public $building_particular_id;
     public $building_category_attr;
     public $building_uacs;
     public $building_title_group;
     public $building_account_title;
     public $building_quantity = [];
     public $building_total_quantity;
     public $building_ppmp;
     public $building_uom;
     public $building_cost_per_unit;
     public $building_estimated_budget;

     //modals
     public $suppliesDetailModal = false;
     public $mooeDetailModal = false;
     public $trainingDetailModal = false;
     public $machineDetailModal = false;
     public $buildingDetailModal = false;






    public function mount($record)
    {
        $this->record = CostCenter::where('id', $record)->first();
        $this->costCenter = $this->record->where('office_id', auth()->user()->employee_information->office_id)->first();
        // dd($this->record->where('office_id', auth()->user()->employee_information->office_id)->get());
        $this->wfp_type = $this->record->fundAllocations->first()->wpfType;
        $this->wfp_fund = $this->record->fundAllocations->first()->fundClusterWFP;
        $this->form->fill();
        $this->global_index = 1;
        $this->fund_allocations = $this->record->fundAllocations;

        if($this->wfp_fund->id === 1 || $this->wfp_fund->id === 2)
        {
            $this->current_balance = $this->record->fundAllocations->map(function($allocation) {
                return [
                'category_group_id' => $allocation->category_group_id,
                'category_group' => $allocation->categoryGroup?->name,
                'initial_amount' => $allocation->initial_amount,
                'current_total' => 0,
                'balance' => $allocation->initial_amount,
                ];
            })->toArray();
        }else{
            $this->current_balance = [];
        }


        $this->supplies_particulars = Supply::whereHas('categoryItems', function ($query) {
            $query->where('budget_category_id', 1);
        })->get();
        $this->supplies_total_quantity = 0;
        $this->supplies_quantity = array_fill(0, 12, 0);

        $this->mooe_particulars = Supply::whereHas('categoryItems', function ($query) {
            $query->where('budget_category_id', 2);
        })->get();
        $this->mooe_total_quantity = 0;
        $this->mooe_quantity = array_fill(0, 12, 0);

        $this->training_particulars = Supply::whereHas('categoryItems', function ($query) {
            $query->where('budget_category_id', 3);
        })->get();
        $this->training_total_quantity = 0;
        $this->training_quantity = array_fill(0, 12, 0);

        $this->machine_particulars = Supply::whereHas('categoryItems', function ($query) {
            $query->where('budget_category_id', 4);
        })->get();
        $this->machine_total_quantity = 0;
        $this->machine_quantity = array_fill(0, 12, 0);

        $this->building_particulars = Supply::whereHas('categoryItems', function ($query) {
            $query->where('budget_category_id', 5);
        })->get();
        $this->building_total_quantity = 0;
        $this->building_quantity = array_fill(0, 12, 0);


    }

    public function updatedSourceFund()
    {
        if($this->source_fund == 6)
        {
            $this->confirm_fund_source = null;
        }
    }

    public function updatedSuppliesParticularId()
    {
        if($this->supplies_particular_id != null)
        {
            $this->supplies_category_attr = Supply::find($this->supplies_particular_id);
            $this->supplies_uacs = $this->supplies_category_attr->categoryItems->uacs_code;
            $this->supplies_title_group = $this->supplies_category_attr->categoryGroups->name;
            $this->supplies_account_title = $this->supplies_category_attr->categoryItems->name;
            $this->supplies_ppmp = $this->supplies_category_attr->is_ppmp;
            $this->supplies_cost_per_unit = $this->supplies_category_attr->unit_cost;
            $this->supplies_quantity = array_fill(0, 12, 0);
            $this->calculateSuppliesTotalQuantity();

        }else{
            $this->supplies_category_attr = null;
            $this->supplies_uacs = null;
            $this->supplies_title_group = null;
            $this->supplies_account_title = null;
            $this->supplies_ppmp = false;
            $this->supplies_cost_per_unit = 0;
            $this->supplies_total_quantity = 0;
            $this->supplies_estimated_budget = 0;
            $this->supplies_uom = null;
            $this->supplies_quantity = array_fill(0, 12, 0);
        }

    }

    public function updatedSuppliesQuantity()
    {
        $this->calculateSuppliesTotalQuantity();
    }

    public function updatedSuppliesCostPerUnit()
    {
        $this->calculateSuppliesTotalQuantity();
    }

    public function calculateSuppliesTotalQuantity()
    {
        $this->supplies_total_quantity = array_sum($this->supplies_quantity);
        $this->supplies_estimated_budget = number_format($this->supplies_total_quantity * $this->supplies_cost_per_unit, 2);
    }

    public function addSupplies()
    {
        //validate all step 2
        $this->validate([
            'supplies_particular_id' => 'required',
            'supplies_uom' => 'required',
        ],
        [
            'supplies_particular_id.required' => 'Particulars is required',
            'supplies_uom.required' => 'UOM is required',
        ]);
        $intEstimatedBudget = (int)str_replace(',', '', $this->supplies_estimated_budget);
        //add to supplies array
        $this->supplies[] = [
            'budget_category_id' => 1,
            'budget_category' => 'Supplies & Semi-Expendables',
            'particular_id' => $this->supplies_particular_id,
            'particular' => $this->supplies_category_attr->particulars,
            'uacs' => $this->supplies_uacs,
            'title_group' => $this->supplies_category_attr->categoryGroups->id,
            'account_title_id' => $this->supplies_category_attr->categoryItems->id,
            'account_title' => $this->supplies_category_attr->categoryItems->name,
            'ppmp' => $this->supplies_ppmp,
            'cost_per_unit' => $this->supplies_cost_per_unit,
            'quantity' => $this->supplies_quantity,
            'total_quantity' => $this->supplies_total_quantity,
            'uom' => $this->supplies_uom,
            'estimated_budget' => $intEstimatedBudget,
        ];

        if($this->wfp_fund->id === 3 || $this->wfp_fund->id === 4 || $this->wfp_fund->id === 5 || $this->wfp_fund->id === 6)
        {
            $categoryGroupId = $this->supplies_category_attr->categoryGroups->id;
            $found = false;

            foreach ($this->current_balance as $key => $balance) {
                if ($balance['category_group_id'] == $categoryGroupId) {
                    $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $this->current_balance[] = [
                    'category_group_id' => $categoryGroupId,
                    'category_group' => $this->supplies_category_attr->categoryGroups->name,
                    'initial_amount' => 0,
                    'current_total' => $intEstimatedBudget,
                    'balance' => $intEstimatedBudget,
                ];
            }

        }else{
            //add current_total to current balance from estimated budget
            foreach ($this->current_balance as $key => $balance) {
                if($balance['category_group_id'] == $this->supplies_category_attr->categoryGroups->id)
                {
                    $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                    $this->current_balance[$key]['balance'] -= $intEstimatedBudget;
                }
            }
        }

        $this->clearSupplies();
    }

    public function showSuppliesDetails()
    {
        $this->suppliesDetailModal = true;
    }

    public function clearSupplies()
    {
        $this->supplies_particular_id = null;
        $this->supplies_category_attr = null;
        $this->supplies_uacs = null;
        $this->supplies_title_group = null;
        $this->supplies_account_title = null;
        $this->supplies_ppmp = false;
        $this->supplies_cost_per_unit = 0;
        $this->supplies_total_quantity = 0;
        $this->supplies_estimated_budget = 0;
        $this->supplies_uom = null;
        $this->supplies_quantity = array_fill(0, 12, 0);
    }


    public function updatedMooeParticularId()
    {
        if($this->mooe_particular_id != null)
        {
            $this->mooe_category_attr = Supply::find($this->mooe_particular_id);
            $this->mooe_uacs = $this->mooe_category_attr->categoryItems->uacs_code;
            $this->mooe_title_group = $this->mooe_category_attr->categoryGroups->name;
            $this->mooe_account_title = $this->mooe_category_attr->categoryItems->name;
            $this->mooe_ppmp = $this->mooe_category_attr->is_ppmp;
            $this->mooe_cost_per_unit = $this->mooe_category_attr->unit_cost;
            $this->mooe_quantity = array_fill(0, 12, 0);
            $this->calculateMooeTotalQuantity();

        }else{
            $this->mooe_category_attr = null;
            $this->mooe_uacs = null;
            $this->mooe_title_group = null;
            $this->mooe_account_title = null;
            $this->mooe_ppmp = false;
            $this->mooe_cost_per_unit = 0;
            $this->mooe_total_quantity = 0;
            $this->mooe_estimated_budget = 0;
            $this->mooe_uom = null;
            $this->mooe_quantity = array_fill(0, 12, 0);
        }

    }

    public function updatedMooeQuantity()
    {
        $this->calculateMooeTotalQuantity();
    }

    public function updatedMooeCostPerUnit()
    {
        $this->calculateMooeTotalQuantity();
    }

    public function calculateMooeTotalQuantity()
    {
        $this->mooe_total_quantity = array_sum($this->mooe_quantity);
        $this->mooe_estimated_budget = number_format($this->mooe_total_quantity * $this->mooe_cost_per_unit, 2);
    }

    public function addMooe()
    {
        //validate all step 2
        $this->validate([
            'mooe_particular_id' => 'required',
            'mooe_uom' => 'required',
        ],
        [
            'mooe_particular_id.required' => 'Particulars is required',
            'mooe_uom.required' => 'UOM is required',
        ]);
        $intEstimatedBudget = (int)str_replace(',', '', $this->mooe_estimated_budget);
        //add to supplies array
        $this->mooe[] = [
            'budget_category_id' => 2,
            'budget_category' => 'MOOE',
            'particular_id' => $this->mooe_particular_id,
            'particular' => $this->mooe_category_attr->particulars,
            'uacs' => $this->mooe_uacs,
            'title_group' => $this->mooe_category_attr->categoryGroups->id,
            'account_title_id' => $this->mooe_category_attr->categoryItems->id,
            'account_title' => $this->mooe_category_attr->categoryItems->name,
            'ppmp' => $this->mooe_ppmp,
            'cost_per_unit' => $this->mooe_cost_per_unit,
            'quantity' => $this->mooe_quantity,
            'total_quantity' => $this->mooe_total_quantity,
            'uom' => $this->mooe_uom,
            'estimated_budget' => $intEstimatedBudget,
        ];

        //add current_total to current balance from estimated budget
        foreach ($this->current_balance as $key => $balance) {
            if($balance['category_group_id'] == $this->mooe_category_attr->categoryGroups->id)
            {
                $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                $this->current_balance[$key]['balance'] -= $intEstimatedBudget;
            }
        }

        $this->clearMooe();
    }

    public function showMooeDetails()
    {
        $this->mooeDetailModal = true;
    }

    public function clearMooe()
    {
        $this->mooe_particular_id = null;
        $this->mooe_category_attr = null;
        $this->mooe_uacs = null;
        $this->mooe_title_group = null;
        $this->mooe_account_title = null;
        $this->mooe_ppmp = false;
        $this->mooe_cost_per_unit = 0;
        $this->mooe_total_quantity = 0;
        $this->mooe_estimated_budget = 0;
        $this->mooe_uom = null;
        $this->mooe_quantity = array_fill(0, 12, 0);
    }

    public function updatedTrainingParticularId()
    {
        if($this->training_particular_id != null)
        {
            $this->training_category_attr = Supply::find($this->training_particular_id);
            $this->training_uacs = $this->training_category_attr->categoryItems->uacs_code;
            $this->training_title_group = $this->training_category_attr->categoryGroups->name;
            $this->training_account_title = $this->training_category_attr->categoryItems->name;
            $this->training_ppmp = $this->training_category_attr->is_ppmp;
            $this->training_cost_per_unit = $this->training_category_attr->unit_cost;
            $this->training_quantity = array_fill(0, 12, 0);
            $this->calculateTrainingTotalQuantity();

        }else{
            $this->training_category_attr = null;
            $this->training_uacs = null;
            $this->training_title_group = null;
            $this->training_account_title = null;
            $this->training_ppmp = false;
            $this->training_cost_per_unit = 0;
            $this->training_total_quantity = 0;
            $this->training_estimated_budget = 0;
            $this->training_uom = null;
            $this->training_quantity = array_fill(0, 12, 0);
        }

    }

    public function updatedTrainingQuantity()
    {
        $this->calculateTrainingTotalQuantity();
    }

    public function updatedTrainingCostPerUnit()
    {
        $this->calculateTrainingTotalQuantity();
    }

    public function calculateTrainingTotalQuantity()
    {
        $this->training_total_quantity = array_sum($this->training_quantity);
        $this->training_estimated_budget = number_format($this->training_total_quantity * $this->training_cost_per_unit, 2);
    }

    public function addTraining()
    {
        //validate all step 2
        $this->validate([
            'training_particular_id' => 'required',
            'training_uom' => 'required',
        ],
        [
            'training_particular_id.required' => 'Particulars is required',
            'training_uom.required' => 'UOM is required',
        ]);
        $intEstimatedBudget = (int)str_replace(',', '', $this->training_estimated_budget);
        //add to supplies array
        $this->trainings[] = [
            'budget_category_id' => 3,
            'budget_category' => 'Trainings',
            'particular_id' => $this->training_particular_id,
            'particular' => $this->training_category_attr->particulars,
            'uacs' => $this->training_uacs,
            'title_group' => $this->training_category_attr->categoryGroups->id,
            'account_title_id' => $this->training_category_attr->categoryItems->id,
            'account_title' => $this->training_category_attr->categoryItems->name,
            'ppmp' => $this->training_ppmp,
            'cost_per_unit' => $this->training_cost_per_unit,
            'quantity' => $this->training_quantity,
            'total_quantity' => $this->training_total_quantity,
            'uom' => $this->training_uom,
            'estimated_budget' => $intEstimatedBudget,
        ];
        //add current_total to current balance from estimated budget
        foreach ($this->current_balance as $key => $balance) {
            if($balance['category_group_id'] == $this->training_category_attr->categoryGroups->id)
            {
                $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                $this->current_balance[$key]['balance'] -= $intEstimatedBudget;
            }
        }

        $this->clearTrainings();
    }

    public function showTrainingDetails()
    {
        $this->trainingDetailModal = true;
    }

    public function clearTrainings()
    {
        $this->training_particular_id = null;
        $this->training_category_attr = null;
        $this->training_uacs = null;
        $this->training_title_group = null;
        $this->training_account_title = null;
        $this->training_ppmp = false;
        $this->training_cost_per_unit = 0;
        $this->training_total_quantity = 0;
        $this->training_estimated_budget = 0;
        $this->training_uom = null;
        $this->training_quantity = array_fill(0, 12, 0);
    }

    public function updatedMachineParticularId()
    {
        if($this->machine_particular_id != null)
        {
            $this->machine_category_attr = Supply::find($this->machine_particular_id);
            $this->machine_uacs = $this->machine_category_attr->categoryItems->uacs_code;
            $this->machine_title_group = $this->machine_category_attr->categoryGroups->name;
            $this->machine_account_title = $this->machine_category_attr->categoryItems->name;
            $this->machine_ppmp = $this->machine_category_attr->is_ppmp;
            $this->machine_cost_per_unit = $this->machine_category_attr->unit_cost;
            $this->machine_quantity = array_fill(0, 12, 0);
            $this->calculateMachineTotalQuantity();

        }else{
            $this->machine_category_attr = null;
            $this->machine_uacs = null;
            $this->machine_title_group = null;
            $this->machine_account_title = null;
            $this->machine_ppmp = false;
            $this->machine_cost_per_unit = 0;
            $this->machine_total_quantity = 0;
            $this->machine_estimated_budget = 0;
            $this->machine_uom = null;
            $this->machine_quantity = array_fill(0, 12, 0);
        }

    }

    public function updatedMachineQuantity()
    {
        $this->calculateMachineTotalQuantity();
    }

    public function updatedMachineCostPerUnit()
    {
        $this->calculateMachineTotalQuantity();
    }

    public function calculateMachineTotalQuantity()
    {
        $this->machine_total_quantity = array_sum($this->machine_quantity);
        $this->machine_estimated_budget = number_format($this->machine_total_quantity * $this->machine_cost_per_unit, 2);
    }

    public function addMachine()
    {
        //validate all step 2
        $this->validate([
            'machine_particular_id' => 'required',
            'machine_uom' => 'required',
        ],
        [
            'machine_particular_id.required' => 'Particulars is required',
            'machine_uom.required' => 'UOM is required',
        ]);
        $intEstimatedBudget = (int)str_replace(',', '', $this->machine_estimated_budget);
        //add to supplies array

        $this->machines[] = [
            'budget_category_id' => 4,
            'budget_category' => 'Machine & Equipment / Furniture & Fixtures / Bio / Vehicles',
            'particular_id' => $this->machine_particular_id,
            'particular' => $this->machine_category_attr->particulars,
            'uacs' => $this->machine_uacs,
            'title_group' => $this->machine_category_attr->categoryGroups->id,
            'account_title_id' => $this->machine_category_attr->categoryItems->id,
            'account_title' => $this->machine_category_attr->categoryItems->name,
            'ppmp' => $this->machine_ppmp,
            'cost_per_unit' => $this->machine_cost_per_unit,
            'quantity' => $this->machine_quantity,
            'total_quantity' => $this->machine_total_quantity,
            'uom' => $this->machine_uom,
            'estimated_budget' => $intEstimatedBudget,
        ];
        //add current_total to current balance from estimated budget
        foreach ($this->current_balance as $key => $balance) {
            if($balance['category_group_id'] == $this->machine_category_attr->categoryGroups->id)
            {
                $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                $this->current_balance[$key]['balance'] -= $intEstimatedBudget;
            }
        }


        $this->clearMachine();
    }

    public function showMachineDetails()
    {
        $this->machineDetailModal = true;
    }

    public function clearMachine()
    {
        $this->machine_particular_id = null;
        $this->machine_category_attr = null;
        $this->machine_uacs = null;
        $this->machine_title_group = null;
        $this->machine_account_title = null;
        $this->machine_ppmp = false;
        $this->machine_cost_per_unit = 0;
        $this->machine_total_quantity = 0;
        $this->machine_estimated_budget = 0;
        $this->machine_uom = null;
        $this->machine_quantity = array_fill(0, 12, 0);
    }

    public function updatedBuildingParticularId()
    {
        if($this->building_particular_id != null)
        {
            $this->building_category_attr = Supply::find($this->building_particular_id);
            $this->building_uacs = $this->building_category_attr->categoryItems->uacs_code;
            $this->building_title_group = $this->building_category_attr->categoryGroups->name;
            $this->building_account_title = $this->building_category_attr->categoryItems->name;
            $this->building_ppmp = $this->building_category_attr->is_ppmp;
            $this->building_cost_per_unit = $this->building_category_attr->unit_cost;
            $this->building_quantity = array_fill(0, 12, 0);
            $this->calculateBuildingTotalQuantity();

        }else{
            $this->building_category_attr = null;
            $this->building_uacs = null;
            $this->building_title_group = null;
            $this->building_account_title = null;
            $this->building_ppmp = false;
            $this->building_cost_per_unit = 0;
            $this->building_total_quantity = 0;
            $this->building_estimated_budget = 0;
            $this->building_uom = null;
            $this->building_quantity = array_fill(0, 12, 0);
        }

    }

    public function updatedBuildingQuantity()
    {
        $this->calculateBuildingTotalQuantity();
    }

    public function updatedBuildingCostPerUnit()
    {
        $this->calculateBuildingTotalQuantity();
    }

    public function calculateBuildingTotalQuantity()
    {
        $this->building_total_quantity = array_sum($this->building_quantity);
        $this->building_estimated_budget = number_format($this->building_total_quantity * $this->building_cost_per_unit, 2);
    }

    public function addBuilding()
    {
        //validate all step 2
        $this->validate([
            'building_particular_id' => 'required',
            'building_uom' => 'required',
        ],
        [
            'building_particular_id.required' => 'Particulars is required',
            'building_uom.required' => 'UOM is required',
        ]);
        $intEstimatedBudget = (int)str_replace(',', '', $this->building_estimated_budget);
        //add to supplies array
        $this->buildings[] = [
            'budget_category_id' => 5,
            'budget_category' => 'Building & Infrastructure',
            'particular_id' => $this->building_particular_id,
            'particular' => $this->building_category_attr->particulars,
            'uacs' => $this->building_uacs,
            'title_group' => $this->building_category_attr->categoryGroups->id,
            'account_title_id' => $this->building_category_attr->categoryItems->id,
            'account_title' => $this->building_category_attr->categoryItems->name,
            'ppmp' => $this->building_ppmp,
            'cost_per_unit' => $this->building_cost_per_unit,
            'quantity' => $this->building_quantity,
            'total_quantity' => $this->building_total_quantity,
            'uom' => $this->building_uom,
            'estimated_budget' => $intEstimatedBudget,
        ];
        //add current_total to current balance from estimated budget
        foreach ($this->current_balance as $key => $balance) {
            if($balance['category_group_id'] == $this->building_category_attr->categoryGroups->id)
            {
                $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                $this->current_balance[$key]['balance'] -= $intEstimatedBudget;
            }
        }


        $this->clearBuilding();
    }


    public function showBuildingDetails()
    {
        $this->buildingDetailModal = true;
    }

    public function clearBuilding()
    {
        $this->building_particular_id = null;
        $this->building_category_attr = null;
        $this->building_uacs = null;
        $this->building_title_group = null;
        $this->building_account_title = null;
        $this->building_ppmp = false;
        $this->building_cost_per_unit = 0;
        $this->building_total_quantity = 0;
        $this->building_estimated_budget = 0;
        $this->building_uom = null;
        $this->building_quantity = array_fill(0, 12, 0);
    }



    public function decreaseStep()
    {
        $this->global_index--;
    }

    public function increaseStep()
    {
        $this->global_index++;
    }

    // protected function getFormSchema(): array
    // {
    //     return [
    //         Wizard::make([
    //             Wizard\Step::make('Initial Information')
    //             ->schema([
    //             Forms\Components\TextInput::make('fund_description')->required(),
    //             Forms\Components\Select::make('source_fund')
    //             ->label('Source of Fund')
    //             ->reactive()
    //             ->afterStateUpdated(fn ($state, $set) => $state != 'MISCELLANEOUS/FIDUCIARY FEE' ? $set('specify_fund_source', '') : '')
    //             ->options([
    //                 'TUITION FEE - RESEARCH FUND' => 'TUITION FEE - RESEARCH FUND',
    //                 'TUITION FEE - EXTENSION FUND' => 'TUITION FEE - EXTENSION FUND',
    //                 'TUITION FEE - STUDENT DEVELOPMENT' => 'TUITION FEE - STUDENT DEVELOPMENT',
    //                 'TUITION FEE - FACILITIES DEVELOPMENT' => 'TUITION FEE - FACILITIES DEVELOPMENT',
    //                 'TUITION FEE - CURRICULUM DEVELOPMENT' => 'TUITION FEE - CURRICULUM DEVELOPMENT',
    //                 'MISCELLANEOUS/FIDUCIARY FEE' => 'MISCELLANEOUS/FIDUCIARY FEE ',
    //             ])->required(),
    //             Forms\Components\TextInput::make('specify_fund_source')
    //             ->label('if miscellaneous/fiduciary fee, please specify')
    //             ->required()
    //             ->visible(fn ($get) => $get('source_fund') === 'MISCELLANEOUS/FIDUCIARY FEE'),
    //             Forms\Components\Grid::make(1)
    //             ->schema([
    //                 Forms\Components\TextInput::make('specific_fund_source')->required(),
    //             ]),
    //             ]),
    //             Wizard\Step::make('Supplies & Semi-Expendables')
    //                 ->schema([
    //                     Forms\Components\Grid::make(4)
    //                 ->schema([
    //                     Forms\Components\Select::make('particulars')
    //                     ->reactive()
    //                     ->options(fn ($get) => Supply::whereHas('categoryItems', function ($query) use ($get) {
    //                         $query->where('budget_category_id', 1);
    //                     })->pluck('particulars', 'id'))
    //                     ->reactive()
    //                     ->afterStateUpdated(function ($state, $set, $get){
    //                                 $uacs_code = Supply::find($state)->categoryItems->uacs_code;
    //                                 $category_group = Supply::find($state)->categoryGroups->id;
    //                                 $account_title = Supply::find($state)->categoryItems->id;
    //                                 $ppmp = Supply::find($state)->is_ppmp;
    //                                 $this->supply_cost = Supply::find($state)->unit_cost;

    //                                 $set('uacs_code', $uacs_code);
    //                                 $set('title_group', $category_group);
    //                                 $set('account_title', $account_title);
    //                                 $set('is_ppmp', $ppmp ? true : false);
    //                                 $set('cost_per_unit', $ppmp ? number_format($this->supply_cost, 2) : 0);
    //                     })
    //                     ->searchable()
    //                     ->required(),
    //                     Forms\Components\TextInput::make('uacs_code')->disabled()->required(),
    //                     Forms\Components\Select::make('title_group')
    //                     ->disabled()
    //                     ->options(fn ($get) => CategoryGroup::all()->pluck('name', 'id'))
    //                     ->required(),
    //                     Forms\Components\Select::make('account_title')
    //                     ->disabled()
    //                     ->options(fn ($get) => CategoryItems::all()->pluck('name', 'id'))
    //                     ->required()
    //                 ]),
    //                 Forms\Components\Grid::make(1)
    //                     ->schema([
    //                         TableRepeater::make('quantity_year')
    //                             ->label('')
    //                             ->schema([
    //                                 Forms\Components\TextInput::make('jan')->numeric()->default(0)
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year'] as $entry) {
    //                                                                 $this->total_quantity = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty', $this->total_quantity);
    //                                                             $set('../../estimated_budget', number_format($this->total_quantity * $this->supply_cost, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('feb')->numeric()->default(0)
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year'] as $entry) {
    //                                                                 $this->total_quantity = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty', $this->total_quantity);
    //                                                             $set('../../estimated_budget', number_format($this->total_quantity * $this->supply_cost, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('mar')->numeric()->default(0)
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year'] as $entry) {
    //                                                                 $this->total_quantity = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty', $this->total_quantity);
    //                                                             $set('../../estimated_budget', number_format($this->total_quantity * $this->supply_cost, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('apr')->numeric()->default(0)
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year'] as $entry) {
    //                                                                 $this->total_quantity = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty', $this->total_quantity);
    //                                                             $set('../../estimated_budget', number_format($this->total_quantity * $this->supply_cost, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('may')->numeric()->default(0)
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year'] as $entry) {
    //                                                                 $this->total_quantity = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty', $this->total_quantity);
    //                                                             $set('../../estimated_budget', number_format($this->total_quantity * $this->supply_cost, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('jun')->numeric()->default(0)
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year'] as $entry) {
    //                                                                 $this->total_quantity = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty', $this->total_quantity);
    //                                                             $set('../../estimated_budget', number_format($this->total_quantity * $this->supply_cost, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('jul')->numeric()->default(0)
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year'] as $entry) {
    //                                                                 $this->total_quantity = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty', $this->total_quantity);
    //                                                             $set('../../estimated_budget', number_format($this->total_quantity * $this->supply_cost, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('aug')->numeric()->default(0)
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year'] as $entry) {
    //                                                                 $this->total_quantity = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty', $this->total_quantity);
    //                                                             $set('../../estimated_budget', number_format($this->total_quantity * $this->supply_cost, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('sep')->numeric()->default(0)
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year'] as $entry) {
    //                                                                 $this->total_quantity = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty', $this->total_quantity);
    //                                                             $set('../../estimated_budget', number_format($this->total_quantity * $this->supply_cost, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('oct')->numeric()->default(0)
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year'] as $entry) {
    //                                                                 $this->total_quantity = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty', $this->total_quantity);
    //                                                             $set('../../estimated_budget', number_format($this->total_quantity * $this->supply_cost, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('nov')->numeric()->default(0)
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year'] as $entry) {
    //                                                                 $this->total_quantity = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty', $this->total_quantity);
    //                                                             $set('../../estimated_budget', number_format($this->total_quantity * $this->supply_cost, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('dec')->numeric()->default(0)
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year'] as $entry) {
    //                                                                 $this->total_quantity = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty', $this->total_quantity);
    //                                                             $set('../../estimated_budget', number_format($this->total_quantity * $this->supply_cost, 2));
    //                                                     }),
    //                                                 ])->hideLabels()
    //                                                 ->disableItemCreation()
    //                                                 ->disableItemDeletion()
    //                                                 ->disableItemMovement()
    //                                                 ->columnSpan('full'),
    //                     ]),
    //                     Forms\Components\Grid::make(1)
    //                     ->schema([
    //                         Toggle::make('is_ppmp')
    //                         ->label('PPMP')
    //                         ->default(true)
    //                         ->disabled()
    //                         ->reactive(),
    //                     ]),
    //                     Forms\Components\Grid::make(4)
    //                     ->schema([
    //                         Forms\Components\TextInput::make('total_qty')
    //                         ->label('Quantity')
    //                         ->required()
    //                         ->disabled()
    //                         ->default(0),
    //                         Forms\Components\Select::make('uom')
    //                         ->label('UOM')
    //                         ->required()
    //                         // ->options(fn ($get) => CategoryItems::all()->pluck('name', 'id'))
    //                         ->options([
    //                             'pcs' => 'pcs',
    //                             'box' => 'box',
    //                             'pax' => 'pax',
    //                             'lot' => 'lot',
    //                             'van' => 'van',
    //                         ]),
    //                         Forms\Components\TextInput::make('cost_per_unit')
    //                         ->label('Cost per Unit')
    //                         ->reactive()
    //                         ->required()
    //                         ->disabled(fn ($get) => $get('is_ppmp') === true)
    //                         ->afterStateUpdated(function ($state, $set){
    //                             $quantity = $this->data['total_qty'];
    //                             $total = $quantity * $state;
    //                             $set('estimated_budget', number_format($total, 2));
    //                         })
    //                         ->default(0),
    //                         Forms\Components\TextInput::make('estimated_budget')
    //                         ->label('Estimated Budget')
    //                         ->required()
    //                         ->disabled()
    //                         ->default(0),

    //                     ])
    //                 ]),
    //             Wizard\Step::make('MOOE')
    //                 ->schema([
    //                     Forms\Components\Grid::make(4)
    //                 ->schema([
    //                     Forms\Components\Select::make('particulars2')
    //                     ->label('Particulars')
    //                     ->reactive()
    //                     ->options(fn ($get) => Supply::whereHas('categoryItems', function ($query) use ($get) {
    //                         $query->where('budget_category_id', 2);
    //                     })->pluck('particulars', 'id'))
    //                     ->reactive()
    //                     ->afterStateUpdated(function ($state, $set, $get){
    //                                 $uacs_code = Supply::find($state)->categoryItems->uacs_code;
    //                                 $category_group = Supply::find($state)->categoryGroups->id;
    //                                 $account_title = Supply::find($state)->categoryItems->id;
    //                                 $ppmp = Supply::find($state)->is_ppmp;
    //                                 $this->supply_cost2 = Supply::find($state)->unit_cost;

    //                                 $set('uacs_code2', $uacs_code);
    //                                 $set('title_group2', $category_group);
    //                                 $set('account_title2', $account_title);
    //                                 $set('is_ppmp2', $ppmp ? true : false);
    //                                 $set('cost_per_unit2', $ppmp ? number_format($this->supply_cost2, 2) : 0);
    //                     })
    //                     ->searchable()
    //                     ->required(),
    //                     Forms\Components\TextInput::make('uacs_code2')
    //                     ->label('UACS CODE')->disabled()->required(),
    //                     Forms\Components\Select::make('title_group2')
    //                     ->label('Title Group')
    //                     ->disabled()
    //                     ->options(fn ($get) => CategoryGroup::all()->pluck('name', 'id'))
    //                     ->required(),
    //                     Forms\Components\Select::make('account_title2')
    //                     ->label('Account Title')
    //                     ->disabled()
    //                     ->options(fn ($get) => CategoryItems::all()->pluck('name', 'id'))
    //                     ->required()
    //                 ]),
    //                 Forms\Components\Grid::make(1)
    //                     ->schema([
    //                         TableRepeater::make('quantity_year2')
    //                             ->label('')
    //                             ->schema([
    //                                 Forms\Components\TextInput::make('jan2')->numeric()->default(0)
    //                                                         ->label('Jan')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year2'] as $entry) {
    //                                                                 $this->total_quantity2 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty2', $this->total_quantity2);
    //                                                             $set('../../estimated_budget2', number_format($this->total_quantity2 * $this->supply_cost2, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('feb2')->numeric()->default(0)
    //                                                         ->label('Feb')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year2'] as $entry) {
    //                                                                 $this->total_quantity2 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty2', $this->total_quantity2);
    //                                                             $set('../../estimated_budget2', number_format($this->total_quantity2 * $this->supply_cost2, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('mar2')->numeric()->default(0)
    //                                                         ->label('Mar')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year2'] as $entry) {
    //                                                                 $this->total_quantity2 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty2', $this->total_quantity2);
    //                                                             $set('../../estimated_budget2', number_format($this->total_quantity2 * $this->supply_cost2, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('apr2')->numeric()->default(0)
    //                                                         ->label('Apr')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year2'] as $entry) {
    //                                                                 $this->total_quantity2 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty2', $this->total_quantity2);
    //                                                             $set('../../estimated_budget2', number_format($this->total_quantity2 * $this->supply_cost2, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('may2')->numeric()->default(0)
    //                                                         ->label('May')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year2'] as $entry) {
    //                                                                 $this->total_quantity2 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty2', $this->total_quantity2);
    //                                                             $set('../../estimated_budget2', number_format($this->total_quantity2 * $this->supply_cost2, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('jun2')->numeric()->default(0)
    //                                                         ->label('Jun')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year2'] as $entry) {
    //                                                                 $this->total_quantity2 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty2', $this->total_quantity2);
    //                                                             $set('../../estimated_budget2', number_format($this->total_quantity2 * $this->supply_cost2, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('jul2')->numeric()->default(0)
    //                                                         ->label('Jul')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year2'] as $entry) {
    //                                                                 $this->total_quantity2 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty2', $this->total_quantity2);
    //                                                             $set('../../estimated_budget2', number_format($this->total_quantity2 * $this->supply_cost2, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('aug2')->numeric()->default(0)
    //                                                         ->label('Aug')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year2'] as $entry) {
    //                                                                 $this->total_quantity2 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty2', $this->total_quantity2);
    //                                                             $set('../../estimated_budget2', number_format($this->total_quantity2 * $this->supply_cost2, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('sep2')->numeric()->default(0)
    //                                                         ->label('Sep')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year2'] as $entry) {
    //                                                                 $this->total_quantity2 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty2', $this->total_quantity2);
    //                                                             $set('../../estimated_budget2', number_format($this->total_quantity2 * $this->supply_cost2, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('oct2')->numeric()->default(0)
    //                                                         ->label('Oct')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year2'] as $entry) {
    //                                                                 $this->total_quantity2 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty2', $this->total_quantity2);
    //                                                             $set('../../estimated_budget2', number_format($this->total_quantity2 * $this->supply_cost2, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('nov2')->numeric()->default(0)
    //                                                         ->label('Nov')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year2'] as $entry) {
    //                                                                 $this->total_quantity2 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty2', $this->total_quantity2);
    //                                                             $set('../../estimated_budget2', number_format($this->total_quantity2 * $this->supply_cost2, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('dec2')->numeric()->default(0)
    //                                                         ->label('Dec')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year2'] as $entry) {
    //                                                                 $this->total_quantity2 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty2', $this->total_quantity2);
    //                                                             $set('../../estimated_budget2', number_format($this->total_quantity2 * $this->supply_cost2, 2));
    //                                                     }),
    //                                                 ])->hideLabels()
    //                                                 ->disableItemCreation()
    //                                                 ->disableItemDeletion()
    //                                                 ->disableItemMovement()
    //                                                 ->columnSpan('full'),
    //                     ]),
    //                     Forms\Components\Grid::make(1)
    //                     ->schema([
    //                         Toggle::make('is_ppmp2')
    //                         ->label('PPMP')
    //                         ->default(true)
    //                         ->disabled()
    //                         ->reactive(),
    //                     ]),
    //                     Forms\Components\Grid::make(4)
    //                     ->schema([
    //                         Forms\Components\TextInput::make('total_qty2')
    //                         ->label('Quantity')
    //                         ->required()
    //                         ->disabled()
    //                         ->default(0),
    //                         Forms\Components\Select::make('uom2')
    //                         ->label('UOM')
    //                         ->required()
    //                         // ->options(fn ($get) => CategoryItems::all()->pluck('name', 'id'))
    //                         ->options([
    //                             'pcs' => 'pcs',
    //                             'box' => 'box',
    //                             'pax' => 'pax',
    //                             'lot' => 'lot',
    //                             'van' => 'van',
    //                         ]),
    //                         Forms\Components\TextInput::make('cost_per_unit2')
    //                         ->label('Cost per Unit')
    //                         ->required()
    //                         ->disabled(fn ($get) => $get('is_ppmp2') === true)
    //                         ->afterStateUpdated(function ($state, $set){
    //                             $quantity = $this->data['total_qty2'];
    //                             $total = $quantity * $state;
    //                             $set('estimated_budget2', number_format($total, 2));
    //                         })
    //                         ->default(0),
    //                         Forms\Components\TextInput::make('estimated_budget2')
    //                         ->label('Estimated Budget')
    //                         ->required()
    //                         ->disabled()
    //                         ->default(0),

    //                     ])
    //                 ]),
    //             Wizard\Step::make('Trainings')
    //                 ->schema([
    //                     Forms\Components\Grid::make(4)
    //                 ->schema([
    //                     Forms\Components\Select::make('particulars3')
    //                     ->label('Particulars')
    //                     ->reactive()
    //                     ->options(fn ($get) => Supply::whereHas('categoryItems', function ($query) use ($get) {
    //                         $query->where('budget_category_id', 3);
    //                     })->pluck('particulars', 'id'))
    //                     ->reactive()
    //                     ->afterStateUpdated(function ($state, $set, $get){
    //                                 $uacs_code = Supply::find($state)->categoryItems->uacs_code;
    //                                 $category_group = Supply::find($state)->categoryGroups->id;
    //                                 $account_title = Supply::find($state)->categoryItems->id;
    //                                 $ppmp = Supply::find($state)->is_ppmp;
    //                                 $this->supply_cost3 = Supply::find($state)->unit_cost;

    //                                 $set('uacs_code3', $uacs_code);
    //                                 $set('title_group3', $category_group);
    //                                 $set('account_title3', $account_title);
    //                                 $set('is_ppmp3', $ppmp ? true : false);

    //                                 $set('cost_per_unit3', $ppmp ? number_format($this->supply_cost3, 2) : 0);
    //                     })
    //                     ->searchable()
    //                     ->required(),
    //                     Forms\Components\TextInput::make('uacs_code3')
    //                     ->label('UACS CODE')
    //                     ->disabled()->required(),
    //                     Forms\Components\Select::make('title_group3')
    //                     ->label('Title Group')
    //                     ->disabled()
    //                     ->options(fn ($get) => CategoryGroup::all()->pluck('name', 'id'))
    //                     ->required(),
    //                     Forms\Components\Select::make('account_title3')
    //                     ->label('Account Title')
    //                     ->disabled()
    //                     ->options(fn ($get) => CategoryItems::all()->pluck('name', 'id'))
    //                     ->required()
    //                 ]),
    //                 Forms\Components\Grid::make(1)
    //                     ->schema([
    //                         TableRepeater::make('quantity_year3')
    //                             ->label('')
    //                             ->schema([
    //                                 Forms\Components\TextInput::make('jan3')->numeric()->default(0)
    //                                                         ->label('Jan')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year3'] as $entry) {
    //                                                                 $this->total_quantity3 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty3', $this->total_quantity3);
    //                                                             $set('../../estimated_budget3', number_format($this->total_quantity3 * $this->supply_cost3, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('feb3')->numeric()->default(0)
    //                                                         ->label('Feb')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year3'] as $entry) {
    //                                                                 $this->total_quantity3 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty3', $this->total_quantity3);
    //                                                             $set('../../estimated_budget3', number_format($this->total_quantity3 * $this->supply_cost3, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('mar3')->numeric()->default(0)
    //                                                         ->label('Mar')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year3'] as $entry) {
    //                                                                 $this->total_quantity3 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty3', $this->total_quantity3);
    //                                                             $set('../../estimated_budget3', number_format($this->total_quantity3 * $this->supply_cost3, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('apr3')->numeric()->default(0)
    //                                                         ->label('Apr')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year3'] as $entry) {
    //                                                                 $this->total_quantity3 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty3', $this->total_quantity3);
    //                                                             $set('../../estimated_budget3', number_format($this->total_quantity3 * $this->supply_cost3, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('may3')->numeric()->default(0)
    //                                                         ->label('May')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year3'] as $entry) {
    //                                                                 $this->total_quantity3 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty3', $this->total_quantity3);
    //                                                             $set('../../estimated_budget3', number_format($this->total_quantity3 * $this->supply_cost3, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('jun3')->numeric()->default(0)
    //                                                         ->label('Jun')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year3'] as $entry) {
    //                                                                 $this->total_quantity3 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty3', $this->total_quantity3);
    //                                                             $set('../../estimated_budget3', number_format($this->total_quantity3 * $this->supply_cost3, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('jul3')->numeric()->default(0)
    //                                                         ->label('Jul')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year3'] as $entry) {
    //                                                                 $this->total_quantity3 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty3', $this->total_quantity3);
    //                                                             $set('../../estimated_budget3', number_format($this->total_quantity3 * $this->supply_cost3, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('aug3')->numeric()->default(0)
    //                                                         ->label('Aug')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year3'] as $entry) {
    //                                                                 $this->total_quantity3 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty3', $this->total_quantity3);
    //                                                             $set('../../estimated_budget3', number_format($this->total_quantity3 * $this->supply_cost3, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('sep3')->numeric()->default(0)
    //                                                         ->label('Sep')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year3'] as $entry) {
    //                                                                 $this->total_quantity3 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty3', $this->total_quantity3);
    //                                                             $set('../../estimated_budget3', number_format($this->total_quantity3 * $this->supply_cost3, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('oct3')->numeric()->default(0)
    //                                                         ->label('Oct')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year3'] as $entry) {
    //                                                                 $this->total_quantity3 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty3', $this->total_quantity3);
    //                                                             $set('../../estimated_budget3', number_format($this->total_quantity3 * $this->supply_cost3, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('nov3')->numeric()->default(0)
    //                                                         ->label('Nov')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year3'] as $entry) {
    //                                                                 $this->total_quantity3 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty3', $this->total_quantity3);
    //                                                             $set('../../estimated_budget3', number_format($this->total_quantity3 * $this->supply_cost3, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('dec3')->numeric()->default(0)
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year3'] as $entry) {
    //                                                                 $this->total_quantity3 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty3', $this->total_quantity3);
    //                                                             $set('../../estimated_budget3', number_format($this->total_quantity3 * $this->supply_cost3, 2));
    //                                                     }),
    //                                                 ])->hideLabels()
    //                                                 ->disableItemCreation()
    //                                                 ->disableItemDeletion()
    //                                                 ->disableItemMovement()
    //                                                 ->columnSpan('full'),
    //                     ]),
    //                     Forms\Components\Grid::make(1)
    //                     ->schema([
    //                         Toggle::make('is_ppmp3')
    //                         ->label('PPMP')
    //                         ->default(true)
    //                         ->disabled()
    //                         ->reactive(),
    //                     ]),
    //                     Forms\Components\Grid::make(4)
    //                     ->schema([
    //                         Forms\Components\TextInput::make('total_qty3')
    //                         ->label('Quantity')
    //                         ->required()
    //                         ->disabled()
    //                         ->default(0),
    //                         Forms\Components\Select::make('uom3')
    //                         ->label('UOM')
    //                         ->required()
    //                         // ->options(fn ($get) => CategoryItems::all()->pluck('name', 'id'))
    //                         ->options([
    //                             'pcs' => 'pcs',
    //                             'box' => 'box',
    //                             'pax' => 'pax',
    //                             'lot' => 'lot',
    //                             'van' => 'van',
    //                         ]),
    //                         Forms\Components\TextInput::make('cost_per_unit3')
    //                         ->label('Cost per Unit')
    //                         ->required()
    //                         ->reactive()
    //                         ->afterStateUpdated(function ($state, $set){
    //                         $quantity = $this->data['total_qty3'];
    //                         $total = $quantity * $state;
    //                         $set('estimated_budget3', number_format($total, 2));
    //                         })
    //                         ->disabled(fn ($get) => $get('is_ppmp3') === true)
    //                         ->default(0),
    //                         Forms\Components\TextInput::make('estimated_budget3')
    //                         ->label('Estimated Budget')
    //                         ->required()
    //                         ->disabled()
    //                         ->default(0),

    //                     ])
    //                 ]),
    //             Wizard\Step::make('Machine & Equipment / Furniture & Fixtures / Bio / Vehicles')
    //                 ->schema([
    //                     Forms\Components\Grid::make(4)
    //                 ->schema([
    //                     Forms\Components\Select::make('particulars4')
    //                     ->label('Particulars')
    //                     ->reactive()
    //                     ->afterStateUpdated(function ($state, $set){
    //                         $cost = Supply::find($state)->unit_cost;
    //                         $set('cost_per_unit4', $cost);
    //                     })
    //                     ->options(fn ($get) => Supply::whereHas('categoryItems', function ($query) use ($get) {
    //                         $query->where('budget_category_id', 4);
    //                     })->pluck('particulars', 'id'))
    //                     ->reactive()
    //                     ->afterStateUpdated(function ($state, $set, $get){
    //                                 $uacs_code = Supply::find($state)->categoryItems->uacs_code;
    //                                 $category_group = Supply::find($state)->categoryGroups->id;
    //                                 $account_title = Supply::find($state)->categoryItems->id;
    //                                 $ppmp = Supply::find($state)->is_ppmp;
    //                                 $this->supply_cost4 = Supply::find($state)->unit_cost;

    //                                 $set('uacs_code4', $uacs_code);
    //                                 $set('title_group4', $category_group);
    //                                 $set('account_title4', $account_title);
    //                                 $set('is_ppmp4', $ppmp ? true : false);
    //                                 $set('cost_per_unit4', $ppmp ? number_format($this->supply_cost4, 2) : 0);
    //                     })
    //                     ->searchable()
    //                     ->required(),
    //                     Forms\Components\TextInput::make('uacs_code4')
    //                     ->label('UACS CODE')->disabled()->required(),
    //                     Forms\Components\Select::make('title_group4')
    //                     ->label('Title Group')
    //                     ->disabled()
    //                     ->options(fn ($get) => CategoryGroup::all()->pluck('name', 'id'))
    //                     ->required(),
    //                     Forms\Components\Select::make('account_title4')
    //                     ->label('Account Title')
    //                     ->disabled()
    //                     ->options(fn ($get) => CategoryItems::all()->pluck('name', 'id'))
    //                     ->required()
    //                 ]),
    //                 Forms\Components\Grid::make(1)
    //                     ->schema([
    //                         TableRepeater::make('quantity_year4')
    //                             ->label('')
    //                             ->schema([
    //                                 Forms\Components\TextInput::make('jan4')->numeric()->default(0)
    //                                                         ->label('Jan')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year4'] as $entry) {
    //                                                                 $this->total_quantity4 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty4', $this->total_quantity4);
    //                                                             $set('../../estimated_budget4', number_format($this->total_quantity4 * $this->supply_cost4, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('feb4')->numeric()->default(0)
    //                                                         ->label('Feb')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year4'] as $entry) {
    //                                                                 $this->total_quantity4 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty4', $this->total_quantity4);
    //                                                             $set('../../estimated_budget4', number_format($this->total_quantity4 * $this->supply_cost4, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('mar4')->numeric()->default(0)
    //                                                         ->label('Mar')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year4'] as $entry) {
    //                                                                 $this->total_quantity4 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty4', $this->total_quantity4);
    //                                                             $set('../../estimated_budget4', number_format($this->total_quantity4 * $this->supply_cost4, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('apr4')->numeric()->default(0)
    //                                                         ->label('Apr')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year4'] as $entry) {
    //                                                                 $this->total_quantity4 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty4', $this->total_quantity4);
    //                                                             $set('../../estimated_budget4', number_format($this->total_quantity4 * $this->supply_cost4, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('may4')->numeric()->default(0)
    //                                                         ->label('May')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year4'] as $entry) {
    //                                                                 $this->total_quantity4 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty4', $this->total_quantity4);
    //                                                             $set('../../estimated_budget4', number_format($this->total_quantity4 * $this->supply_cost4, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('jun4')->numeric()->default(0)
    //                                                         ->label('Jun')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year4'] as $entry) {
    //                                                                 $this->total_quantity4 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty4', $this->total_quantity4);
    //                                                             $set('../../estimated_budget4', number_format($this->total_quantity4 * $this->supply_cost4, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('jul4')->numeric()->default(0)
    //                                                         ->label('Jul')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year4'] as $entry) {
    //                                                                 $this->total_quantity4 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty4', $this->total_quantity4);
    //                                                             $set('../../estimated_budget4', number_format($this->total_quantity4 * $this->supply_cost4, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('aug4')->numeric()->default(0)
    //                                                         ->label('Aug')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year4'] as $entry) {
    //                                                                 $this->total_quantity4 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty4', $this->total_quantity4);
    //                                                             $set('../../estimated_budget4', number_format($this->total_quantity4 * $this->supply_cost4, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('sep4')->numeric()->default(0)
    //                                                         ->label('Sep')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year4'] as $entry) {
    //                                                                 $this->total_quantity4 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty4', $this->total_quantity4);
    //                                                             $set('../../estimated_budget4', number_format($this->total_quantity4 * $this->supply_cost4, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('oct4')->numeric()->default(0)
    //                                                         ->label('Oct')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year4'] as $entry) {
    //                                                                 $this->total_quantity4 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty4', $this->total_quantity4);
    //                                                             $set('../../estimated_budget4', number_format($this->total_quantity4 * $this->supply_cost4, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('nov4')->numeric()->default(0)
    //                                                         ->label('Nov')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year4'] as $entry) {
    //                                                                 $this->total_quantity4 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty4', $this->total_quantity4);
    //                                                             $set('../../estimated_budget4', number_format($this->total_quantity4 * $this->supply_cost4, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('dec4')->numeric()->default(0)
    //                                                         ->label('Dec')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year4'] as $entry) {
    //                                                                 $this->total_quantity4 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty4', $this->total_quantity4);
    //                                                             $set('../../estimated_budget4', number_format($this->total_quantity4 * $this->supply_cost4, 2));
    //                                                     }),
    //                                                 ])->hideLabels()
    //                                                 ->disableItemCreation()
    //                                                 ->disableItemDeletion()
    //                                                 ->disableItemMovement()
    //                                                 ->columnSpan('full'),
    //                     ]),
    //                     Forms\Components\Grid::make(1)
    //                     ->schema([
    //                         Toggle::make('is_ppmp4')
    //                         ->label('PPMP')
    //                         ->default(true)
    //                         ->disabled()
    //                         ->reactive(),
    //                     ]),
    //                     Forms\Components\Grid::make(4)
    //                     ->schema([
    //                         Forms\Components\TextInput::make('total_qty4')
    //                         ->label('Quantity')
    //                         ->required()
    //                         ->disabled()
    //                         ->default(0),
    //                         Forms\Components\Select::make('uom4')
    //                         ->label('UOM')
    //                         ->required()
    //                         // ->options(fn ($get) => CategoryItems::all()->pluck('name', 'id'))
    //                         ->options([
    //                             'pcs' => 'pcs',
    //                             'box' => 'box',
    //                             'pax' => 'pax',
    //                             'lot' => 'lot',
    //                             'van' => 'van',
    //                         ]),
    //                         Forms\Components\TextInput::make('cost_per_unit4')
    //                         ->label('Cost per Unit')
    //                         ->required()
    //                         ->afterStateUpdated(function ($state, $set){
    //                             $quantity = $this->data['total_qty4'];
    //                             $total = $quantity * $state;
    //                             $set('estimated_budget4', number_format($total, 2));
    //                         })
    //                         ->disabled(fn ($get) => $get('is_ppmp4') === true)
    //                         ->default(0),
    //                         Forms\Components\TextInput::make('estimated_budget4')
    //                         ->label('Estimated Budget')
    //                         ->required()
    //                         ->disabled()
    //                         ->default(0),

    //                     ])
    //                 ]),
    //             Wizard\Step::make('Building & Infrastructure')
    //                 ->schema([
    //                     Forms\Components\Grid::make(4)
    //                 ->schema([
    //                     Forms\Components\Select::make('particulars5')
    //                     ->label('Particulars')
    //                     ->reactive()
    //                     ->afterStateUpdated(function ($state, $set){
    //                         $cost = Supply::find($state)->unit_cost;
    //                         $set('cost_per_unit5', $cost);
    //                     })
    //                     ->options(fn ($get) => Supply::whereHas('categoryItems', function ($query) use ($get) {
    //                         $query->where('budget_category_id', 5);
    //                     })->pluck('particulars', 'id'))
    //                     ->reactive()
    //                     ->afterStateUpdated(function ($state, $set, $get){
    //                                 $uacs_code = Supply::find($state)->categoryItems->uacs_code;
    //                                 $category_group = Supply::find($state)->categoryGroups->id;
    //                                 $account_title = Supply::find($state)->categoryItems->id;
    //                                 $ppmp = Supply::find($state)->is_ppmp;
    //                                 $this->supply_cost5 = Supply::find($state)->unit_cost;

    //                                 $set('uacs_code5', $uacs_code);
    //                                 $set('title_group5', $category_group);
    //                                 $set('account_title5', $account_title);
    //                                 $set('is_ppmp5', $ppmp ? true : false);
    //                                 $set('cost_per_unit5', $ppmp ? number_format($this->supply_cost5, 2) : 0);
    //                     })
    //                     ->searchable()
    //                     ->required(),
    //                     Forms\Components\TextInput::make('uacs_code5')
    //                     ->label('UACS CODE')
    //                     ->disabled()->required(),
    //                     Forms\Components\Select::make('title_group5')
    //                     ->label('Title Group')
    //                     ->disabled()
    //                     ->options(fn ($get) => CategoryGroup::all()->pluck('name', 'id'))
    //                     ->required(),
    //                     Forms\Components\Select::make('account_title5')
    //                     ->label('Account Title')
    //                     ->disabled()
    //                     ->options(fn ($get) => CategoryItems::all()->pluck('name', 'id'))
    //                     ->required()
    //                 ]),
    //                 Forms\Components\Grid::make(1)
    //                     ->schema([
    //                         TableRepeater::make('quantity_year5')
    //                             ->label('')
    //                             ->schema([
    //                                 Forms\Components\TextInput::make('jan5')->numeric()->default(0)
    //                                                         ->label('Jan')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year5'] as $entry) {
    //                                                                 $this->total_quantity5 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty5', $this->total_quantity5);
    //                                                             $set('../../estimated_budget5', number_format($this->total_quantity5 * $this->supply_cost5, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('feb5')->numeric()->default(0)
    //                                                         ->label('Feb')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year5'] as $entry) {
    //                                                                 $this->total_quantity5 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty5', $this->total_quantity5);
    //                                                             $set('../../estimated_budget5', number_format($this->total_quantity5 * $this->supply_cost5, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('mar5')->numeric()->default(0)
    //                                                         ->label('Mar')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year5'] as $entry) {
    //                                                                 $this->total_quantity5 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty5', $this->total_quantity5);
    //                                                             $set('../../estimated_budget5', number_format($this->total_quantity5 * $this->supply_cost5, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('apr5')->numeric()->default(0)
    //                                                         ->label('Apr')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year5'] as $entry) {
    //                                                                 $this->total_quantity5 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty5', $this->total_quantity5);
    //                                                             $set('../../estimated_budget5', number_format($this->total_quantity5 * $this->supply_cost5, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('may5')->numeric()->default(0)
    //                                                         ->label('May')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year5'] as $entry) {
    //                                                                 $this->total_quantity5 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty5', $this->total_quantity5);
    //                                                             $set('../../estimated_budget5', number_format($this->total_quantity5 * $this->supply_cost5, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('jun5')->numeric()->default(0)
    //                                                         ->label('Jun')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year5'] as $entry) {
    //                                                                 $this->total_quantity5 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty5', $this->total_quantity5);
    //                                                             $set('../../estimated_budget5', number_format($this->total_quantity5 * $this->supply_cost5, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('jul5')->numeric()->default(0)
    //                                                         ->label('Jul')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year5'] as $entry) {
    //                                                                 $this->total_quantity5 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty5', $this->total_quantity5);
    //                                                             $set('../../estimated_budget5', number_format($this->total_quantity5 * $this->supply_cost5, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('aug5')->numeric()->default(0)
    //                                                         ->label('Aug')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year5'] as $entry) {
    //                                                                 $this->total_quantity5 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty5', $this->total_quantity5);
    //                                                             $set('../../estimated_budget5', number_format($this->total_quantity5 * $this->supply_cost5, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('sep5')->numeric()->default(0)
    //                                                         ->label('Sep')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year5'] as $entry) {
    //                                                                 $this->total_quantity5 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty5', $this->total_quantity5);
    //                                                             $set('../../estimated_budget5', number_format($this->total_quantity5 * $this->supply_cost5, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('oct5')->numeric()->default(0)
    //                                                         ->label('Oct')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year5'] as $entry) {
    //                                                                 $this->total_quantity5 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty5', $this->total_quantity5);
    //                                                             $set('../../estimated_budget5', number_format($this->total_quantity5 * $this->supply_cost5, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('nov5')->numeric()->default(0)
    //                                                         ->label('Nov')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year5'] as $entry) {
    //                                                                 $this->total_quantity5 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty5', $this->total_quantity5);
    //                                                             $set('../../estimated_budget5', number_format($this->total_quantity5 * $this->supply_cost5, 2));
    //                                                     }),
    //                                                         Forms\Components\TextInput::make('dec5')->numeric()->default(0)
    //                                                         ->label('Dec')
    //                                                         ->reactive()
    //                                                          ->afterStateUpdated(function ($state, $set){
    //                                                             foreach ($this->data['quantity_year5'] as $entry) {
    //                                                                 $this->total_quantity5 = array_sum(array_slice($entry, 0, 12));
    //                                                             }
    //                                                             $set('../../total_qty5', $this->total_quantity5);
    //                                                             $set('../../estimated_budget5', number_format($this->total_quantity5 * $this->supply_cost5, 2));
    //                                                     }),
    //                                                 ])->hideLabels()
    //                                                 ->disableItemCreation()
    //                                                 ->disableItemDeletion()
    //                                                 ->disableItemMovement()
    //                                                 ->columnSpan('full'),
    //                     ]),
    //                     Forms\Components\Grid::make(1)
    //                     ->schema([
    //                         Toggle::make('is_ppmp5')
    //                         ->label('PPMP')
    //                         ->default(true)
    //                         ->disabled()
    //                         ->reactive(),
    //                     ]),
    //                     Forms\Components\Grid::make(4)
    //                     ->schema([
    //                         Forms\Components\TextInput::make('total_qty5')
    //                         ->label('Quantity')
    //                         ->required()
    //                         ->disabled()
    //                         ->default(0),
    //                         Forms\Components\Select::make('uom5')
    //                         ->label('UOM')
    //                         ->required()
    //                         // ->options(fn ($get) => CategoryItems::all()->pluck('name', 'id'))
    //                         ->options([
    //                             'pcs' => 'pcs',
    //                             'box' => 'box',
    //                             'pax' => 'pax',
    //                             'lot' => 'lot',
    //                             'van' => 'van',
    //                         ]),
    //                         Forms\Components\TextInput::make('cost_per_unit5')
    //                         ->label('Cost per Unit')
    //                         ->required()
    //                         ->disabled(fn ($get) => $get('is_ppmp5') === true)
    //                         ->afterStateUpdated(function ($state, $set){
    //                             $quantity = $this->data['total_qty5'];
    //                             $total = $quantity * $state;
    //                             $set('estimated_budget5', number_format($total, 2));
    //                         })
    //                         ->default(0),
    //                         Forms\Components\TextInput::make('estimated_budget5')
    //                         ->label('Estimated Budget')
    //                         ->required()
    //                         ->disabled()
    //                         ->default(0),

    //                     ])
    //                 ]),
    //         ])
    //         ->nextAction(function($action){
    //             return $this->hellow('s');
    //         })
    //         ->skippable()
    //         ->submitAction(new HtmlString(view('components.forms.save-button')->render()))
    //     ];
    // }

    public function submit()
    {
        $is_not_valid = false;
        if($this->fund_description === null || $this->specify_fund_source === null)
        {
            $this->dialog()->error(
                $title = 'Operation Failed',
                $description = 'Please fill up initial information',
            );
            $this->suppliesDetailModal = false;
            $this->global_index = 1;

        }

        if($this->wfp_fund->id === 1 || $this->wfp_fund->id === 2)
        {
            foreach ($this->current_balance as $balance) {
                if ($balance['initial_amount'] < $balance['current_total']) {
                    $is_not_valid = true;
                    break;
                }
            }
        }else{
            if(array_sum(array_column($this->current_balance, 'current_total')) > $this->record->fundAllocations->sum('initial_amount'))
            {
                $is_not_valid = true;
            }
        }

        //save data to database
        DB::beginTransaction();
        if(!$is_not_valid)
        {
            $wfp = Wfp::create([
                'cost_center_id' => $this->record->id,
                'wpf_type_id' => $this->wfp_type->id,
                'fund_cluster_w_f_p_s_id' => $this->wfp_fund->id,
                'user_id' => auth()->user()->id,
                'fund_description' => $this->fund_description,
                'source_fund' => $this->source_fund,
                'confirm_fund_source' => $this->confirm_fund_source ?? null,
                'specify_fund_source' =>$this->specify_fund_source,
            ]);

            foreach ($this->supplies as $item)
            {
                WfpDetail::create([
                    'wfp_id' => $wfp->id,
                    'budget_category_id' => $item['budget_category_id'],
                    'supply_id' => $item['particular_id'],
                    'category_group_id' => $item['title_group'],
                    'category_item_id' => $item['account_title_id'],
                    'uacs_code' => $item['uacs'],
                    'is_ppmp' => $item['ppmp'],
                    'quantity_year' => json_encode($item['quantity']),
                    'cost_per_unit' => $item['cost_per_unit'],
                    'total_quantity' => $item['total_quantity'],
                    'uom' => $item['uom'],
                    'estimated_budget' => $item['estimated_budget'],
                ]);
            }

            foreach ($this->mooe as $item)
            {
                WfpDetail::create([
                    'wfp_id' => $wfp->id,
                    'budget_category_id' => $item['budget_category_id'],
                    'supply_id' => $item['particular_id'],
                    'category_group_id' => $item['title_group'],
                    'category_item_id' => $item['account_title_id'],
                    'uacs_code' => $item['uacs'],
                    'is_ppmp' => $item['ppmp'],
                    'quantity_year' => json_encode($item['quantity']),
                    'cost_per_unit' => $item['cost_per_unit'],
                    'total_quantity' => $item['total_quantity'],
                    'uom' => $item['uom'],
                    'estimated_budget' => $item['estimated_budget'],
                ]);
            }

            foreach ($this->trainings as $item)
            {
                WfpDetail::create([
                    'wfp_id' => $wfp->id,
                    'budget_category_id' => $item['budget_category_id'],
                    'supply_id' => $item['particular_id'],
                    'category_group_id' => $item['title_group'],
                    'category_item_id' => $item['account_title_id'],
                    'uacs_code' => $item['uacs'],
                    'is_ppmp' => $item['ppmp'],
                    'quantity_year' => json_encode($item['quantity']),
                    'cost_per_unit' => $item['cost_per_unit'],
                    'total_quantity' => $item['total_quantity'],
                    'uom' => $item['uom'],
                    'estimated_budget' => $item['estimated_budget'],
                ]);
            }

            foreach ($this->machines as $item)
            {
                WfpDetail::create([
                    'wfp_id' => $wfp->id,
                    'budget_category_id' => $item['budget_category_id'],
                    'supply_id' => $item['particular_id'],
                    'category_group_id' => $item['title_group'],
                    'category_item_id' => $item['account_title_id'],
                    'uacs_code' => $item['uacs'],
                    'is_ppmp' => $item['ppmp'],
                    'quantity_year' => json_encode($item['quantity']),
                    'cost_per_unit' => $item['cost_per_unit'],
                    'total_quantity' => $item['total_quantity'],
                    'uom' => $item['uom'],
                    'estimated_budget' => $item['estimated_budget'],
                ]);
            }

            foreach ($this->buildings as $item)
            {
                WfpDetail::create([
                    'wfp_id' => $wfp->id,
                    'budget_category_id' => $item['budget_category_id'],
                    'supply_id' => $item['particular_id'],
                    'category_group_id' => $item['title_group'],
                    'category_item_id' => $item['account_title_id'],
                    'uacs_code' => $item['uacs'],
                    'is_ppmp' => $item['ppmp'],
                    'quantity_year' => json_encode($item['quantity']),
                    'cost_per_unit' => $item['cost_per_unit'],
                    'total_quantity' => $item['total_quantity'],
                    'uom' => $item['uom'],
                    'estimated_budget' => $item['estimated_budget'],
                ]);
            }

            DB::commit();

            $this->dialog()->success(
                $title = 'Operation Successful',
                $description = 'WFP has been successfully created',
            );

            return redirect()->route('wfp.print-wfp', $wfp->id);
        }else{
            $this->dialog()->error(
                $title = 'Operation Failed',
                $description = 'Your grand total has exceeded the budget allocation',
            );
            $this->suppliesDetailModal = false;
        }
    }

    public function deleteSupply($index)
    {
        $budget = $this->supplies[$index]['estimated_budget'];
        $this->current_balance[$this->supplies[$index]['budget_category_id'] - 1]['current_total'] -= $budget;
        unset($this->supplies[$index]);

    }

    public function deleteMooe($index)
    {
        $budget = $this->mooe[$index]['estimated_budget'];
        $this->current_balance[$this->mooe[$index]['budget_category_id'] - 1]['current_total'] -= $budget;
        unset($this->mooe[$index]);
    }

    public function deleteTraining($index)
    {
        $budget = $this->trainings[$index]['estimated_budget'];
        $this->current_balance[$this->trainings[$index]['budget_category_id'] - 1]['current_total'] -= $budget;
        unset($this->trainings[$index]);
    }

    public function deleteMachine($index)
    {
        $budget = $this->machines[$index]['estimated_budget'];
        $this->current_balance[$this->machines[$index]['budget_category_id'] - 1]['current_total'] -= $budget;
        unset($this->machines[$index]);
    }

    public function deleteBuilding($index)
    {
        $budget = $this->buildings[$index]['estimated_budget'];
        $this->current_balance[$this->buildings[$index]['budget_category_id'] - 1]['current_total'] -= $budget;
        unset($this->buildings[$index]);
    }


    protected function getFormStatePath(): string
    {
        return 'data';
    }


    public function render(): View
    {
        $this->costCenter =  CostCenter::where('id', $this->record->id)->first();
        return view('livewire.w-f-p.create-w-f-p', [
            'costCenter' => $this->costCenter,
        ]);
    }
}
