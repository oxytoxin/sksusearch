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
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;

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
    public $remarks_modal_title;
    //step 1
    public $fund_description;
    public $source_fund;
    public $confirm_fund_source;
    public $specify_fund_source;
    public $is_misc = false;
    //step 2
    public $supplies_is_remarks;
    public $supplies_remarks;
    public $supplies_remarks_details;
    public $supplies_particulars;
    public $supplies_particular_id;
    public $supplies_specs;
    public $supplies_code;
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
     public $mooe_is_remarks;
     public $mooe_remarks;
     public $mooe_remarks_details;
     public $mooe_particulars;
     public $mooe_particular_id;
     public $mooe_specs;
     public $mooe_code;
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
     public $training_is_remarks;
     public $training_remarks;
     public $training_remarks_details;
     public $training_particulars;
     public $training_particular_id;
     public $training_specs;
     public $training_code;
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
    public $machine_is_remarks;
    public $machine_remarks;
    public $machine_remarks_details;
    public $machine_particulars;
    public $machine_particular_id;
    public $machine_specs;
    public $machine_code;
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
     public $building_is_remarks;
     public $building_remarks;
     public $building_remarks_details;
     public $building_particulars;
     public $building_particular_id;
     public $building_specs;
     public $building_code;
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
     public $remarksModal = false;
     public $suppliesDetailModal = false;
     public $mooeDetailModal = false;
     public $trainingDetailModal = false;
     public $machineDetailModal = false;
     public $buildingDetailModal = false;


    public function mount($record, $wfpType)
    {
        $this->record = CostCenter::where('id', $record)->whereHas('fundAllocations', function ($query) use ($wfpType) {
            $query->where('wpf_type_id', $wfpType);
        })->first();
        $this->costCenter = $this->record->where('office_id', auth()->user()->employee_information->office_id)->first();
        // dd($this->record->where('office_id', auth()->user()->employee_information->office_id)->get());
        $this->wfp_type = $this->record->fundAllocations->where('wpf_type_id', $wfpType)->first()->wpfType;
        $this->wfp_fund = $this->record->fundAllocations->where('wpf_type_id', $wfpType)->first()->fundClusterWFP;
        $this->fund_description = $this->wfp_fund->fund_source;
        $this->form->fill();
        $this->global_index = 1;
        $this->fund_allocations = $this->record->fundAllocations->where('wpf_type_id', $wfpType);

        if($this->wfp_fund->id === 1 || $this->wfp_fund->id === 2)
        {
            $this->current_balance = $this->record->fundAllocations->where('wpf_type_id', $wfpType)->map(function($allocation) {
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

        //source of fund
        // if($this->wfp_fund->id > 3)
        // {
        //     $this->source_fund = 'TUITION FEE - RESEARCH FUND';
        // }
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('supplies_particular')
            ->label('')
            ->searchable()
            ->placeholder('Search for a particular')
            ->preload()
            // ->options(function () {
            //     switch($this->global_index)
            //     {
            //         case 2:
            //             return Supply::whereHas('categoryItems', function ($query) {
            //                 $query->where('budget_category_id', 1);
            //             })->limit(50)->pluck('particulars', 'id');
            //             break;
            //         case 3:
            //             return Supply::whereHas('categoryItems', function ($query) {
            //                 $query->where('budget_category_id', 2);
            //             })->limit(50)->pluck('particulars', 'id');
            //             break;
            //         case 4:
            //             return Supply::whereHas('categoryItems', function ($query) {
            //                 $query->where('budget_category_id', 3);
            //             })->limit(50)->pluck('particulars', 'id');
            //             break;
            //         case 5:
            //             return Supply::whereHas('categoryItems', function ($query) {
            //                 $query->where('budget_category_id', 4);
            //             })->limit(50)->pluck('particulars', 'id');
            //             break;
            //         case 6:
            //             return Supply::whereHas('categoryItems', function ($query) {
            //                 $query->where('budget_category_id', 5);
            //             })->limit(50)->pluck('particulars', 'id');
            //             break;
            //     }

            // })
            ->getSearchResultsUsing(function (string $search){
                switch($this->global_index)
                {
                    case 2:
                        return Supply::whereHas('categoryItems', function ($query) {
                            $query->where('budget_category_id', 1);
                        })->where('particulars', 'like', "%{$search}%")
                          ->orWhere('specifications', 'like', "%{$search}%")
                          ->limit(50)->pluck('particulars', 'id');
                        break;
                    case 3:
                        return Supply::whereHas('categoryItems', function ($query) {
                            $query->where('budget_category_id', 2);
                        })->where('particulars', 'like', "%{$search}%")
                        ->orWhere('specifications', 'like', "%{$search}%")
                        ->limit(50)->pluck('particulars', 'id');
                        break;
                    case 4:
                        return Supply::whereHas('categoryItems', function ($query) {
                            $query->where('budget_category_id', 3);
                        })->where('particulars', 'like', "%{$search}%")
                        ->orWhere('specifications', 'like', "%{$search}%")
                        ->limit(50)->pluck('particulars', 'id');
                        break;
                    case 5:
                        return Supply::whereHas('categoryItems', function ($query) {
                            $query->where('budget_category_id', 4);
                        })->where('particulars', 'like', "%{$search}%")
                        ->orWhere('specifications', 'like', "%{$search}%")
                        ->limit(50)->pluck('particulars', 'id');
                        break;
                    case 6:
                        return Supply::whereHas('categoryItems', function ($query) {
                            $query->where('budget_category_id', 5);
                        })->where('particulars', 'like', "%{$search}%")
                        ->orWhere('specifications', 'like', "%{$search}%")
                        ->limit(50)->pluck('particulars', 'id');
                        break;
                    default:
                        return Supply::where('particulars', 'like', "%{$search}%")
                        ->orWhere('specifications', 'like', "%{$search}%")
                        ->limit(50)->pluck('particulars', 'id');
                        break;
                }
            })
            ->reactive()
            ->afterStateUpdated(function () {
                switch($this->global_index)
                {
                    case 2:
                        if($this->data['supplies_particular'] != null)
                        {
                            $this->supplies_particular_id = $this->data['supplies_particular'];
                            $this->supplies_category_attr = Supply::find($this->data['supplies_particular']);
                            $this->supplies_specs = $this->supplies_category_attr->specifications;
                            $this->supplies_code = $this->supplies_category_attr->supply_code;
                            $this->supplies_uacs = $this->supplies_category_attr->categoryItems->uacs_code;
                            $this->supplies_title_group = $this->supplies_category_attr->categoryGroups->name;
                            $this->supplies_account_title = $this->supplies_category_attr->categoryItems->name;
                            $this->supplies_ppmp = $this->supplies_category_attr->is_ppmp;
                            $this->supplies_cost_per_unit = $this->supplies_category_attr->unit_cost;
                            $this->supplies_quantity = array_fill(0, 12, 0);
                            $this->calculateSuppliesTotalQuantity();

                        }else{
                            $this->supplies_particular_id = null;
                            $this->supplies_category_attr = null;
                            $this->supplies_specs = null;
                            $this->supplies_code = null;
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
                        break;
                    case 3:
                        if($this->data['supplies_particular'] != null)
                        {
                            $this->mooe_particular_id = $this->data['supplies_particular'];
                            $this->mooe_category_attr = Supply::find($this->data['supplies_particular']);
                            $this->mooe_specs = $this->mooe_category_attr->specifications;
                            $this->mooe_code = $this->mooe_category_attr->supply_code;
                            $this->mooe_uacs = $this->mooe_category_attr->categoryItems->uacs_code;
                            $this->mooe_title_group = $this->mooe_category_attr->categoryGroups->name;
                            $this->mooe_account_title = $this->mooe_category_attr->categoryItems->name;
                            $this->mooe_ppmp = $this->mooe_category_attr->is_ppmp;
                            $this->mooe_cost_per_unit = $this->mooe_category_attr->unit_cost;
                            $this->mooe_quantity = array_fill(0, 12, 0);
                            $this->calculateMooeTotalQuantity();

                        }else{
                            $this->mooe_category_attr = null;
                            $this->mooe_specs = null;
                            $this->mooe_code = null;
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
                        break;
                    case 4:
                        if($this->data['supplies_particular'] != null)
                        {
                            $this->training_particular_id = $this->data['supplies_particular'];
                            $this->training_category_attr = Supply::find($this->data['supplies_particular']);
                            $this->training_specs = $this->training_category_attr->specifications;
                            $this->training_code = $this->training_category_attr->supply_code;
                            $this->training_uacs = $this->training_category_attr->categoryItems->uacs_code;
                            $this->training_title_group = $this->training_category_attr->categoryGroups->name;
                            $this->training_account_title = $this->training_category_attr->categoryItems->name;
                            $this->training_ppmp = $this->training_category_attr->is_ppmp;
                            $this->training_cost_per_unit = $this->training_category_attr->unit_cost;
                            $this->training_quantity = array_fill(0, 12, 0);
                            $this->calculateTrainingTotalQuantity();

                        }else{
                            $this->training_category_attr = null;
                            $this->training_specs = null;
                            $this->training_code = null;
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
                        break;
                    case 5:
                        if($this->data['supplies_particular'] != null)
                        {
                            $this->machine_particular_id = $this->data['supplies_particular'];
                            $this->machine_category_attr = Supply::find($this->data['supplies_particular']);
                            $this->machine_specs = $this->machine_category_attr->specifications;
                            $this->machine_code = $this->machine_category_attr->supply_code;
                            $this->machine_uacs = $this->machine_category_attr->categoryItems->uacs_code;
                            $this->machine_title_group = $this->machine_category_attr->categoryGroups->name;
                            $this->machine_account_title = $this->machine_category_attr->categoryItems->name;
                            $this->machine_ppmp = $this->machine_category_attr->is_ppmp;
                            $this->machine_cost_per_unit = $this->machine_category_attr->unit_cost;
                            $this->machine_quantity = array_fill(0, 12, 0);
                            $this->calculateMachineTotalQuantity();

                        }else{
                            $this->machine_category_attr = null;
                            $this->machine_specs = null;
                            $this->machine_code = null;
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
                        break;
                    case 6:
                        if($this->data['supplies_particular'] != null)
                        {
                            $this->building_particular_id = $this->data['supplies_particular'];
                            $this->building_category_attr = Supply::find($this->data['supplies_particular']);
                            $this->building_specs = $this->building_category_attr->specifications;
                            $this->building_code = $this->building_category_attr->supply_code;
                            $this->building_uacs = $this->building_category_attr->categoryItems->uacs_code;
                            $this->building_title_group = $this->building_category_attr->categoryGroups->name;
                            $this->building_account_title = $this->building_category_attr->categoryItems->name;
                            $this->building_ppmp = $this->building_category_attr->is_ppmp;
                            $this->building_cost_per_unit = $this->building_category_attr->unit_cost;
                            $this->building_quantity = array_fill(0, 12, 0);
                            $this->calculateBuildingTotalQuantity();

                        }else{
                            $this->building_category_attr = null;
                            $this->building_specs = null;
                            $this->building_code = null;
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
                        break;
                }
            })
        ];
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
            $this->supplies_specs = $this->supplies_category_attr->specifications;
            $this->supplies_code = $this->supplies_category_attr->supply_code;
            $this->supplies_uacs = $this->supplies_category_attr->categoryItems->uacs_code;
            $this->supplies_title_group = $this->supplies_category_attr->categoryGroups->name;
            $this->supplies_account_title = $this->supplies_category_attr->categoryItems->name;
            $this->supplies_ppmp = $this->supplies_category_attr->is_ppmp;
            $this->supplies_cost_per_unit = $this->supplies_category_attr->unit_cost;
            $this->supplies_quantity = array_fill(0, 12, 0);
            $this->calculateSuppliesTotalQuantity();

        }else{
            $this->supplies_category_attr = null;
            $this->supplies_specs = null;
            $this->supplies_code = null;
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
        $cost_per_unit = $this->supplies_cost_per_unit == null  ? 0 : $this->supplies_cost_per_unit;
        $this->supplies_total_quantity = array_sum($this->supplies_quantity);
        $this->supplies_estimated_budget = number_format($this->supplies_total_quantity * $cost_per_unit, 2);
    }

    public function updatedSuppliesIsRemarks()
    {
        if($this->supplies_is_remarks === false)
        {
            $this->supplies_remarks = null;
        }
    }

    public function addSupplies()
    {
        //validate all step 2
        $this->validate([
            'supplies_particular_id' => 'required',
            'supplies_uom' => 'required',
            'supplies_cost_per_unit' => 'required|gt:0',
            'supplies_total_quantity' => 'gt:0',
        ],
        [
            'supplies_particular_id.required' => 'Particulars is required',
            'supplies_uom.required' => 'UOM is required',
            'supplies_cost_per_unit.required' => 'Cost per unit is required',
            'supplies_cost_per_unit.gt' => 'Cost per unit must be greater than 0',
            'supplies_total_quantity.gt' => 'Total quantity must be greater than 0',
        ]);
        $intEstimatedBudget = (int)str_replace(',', '', $this->supplies_estimated_budget);

        if($this->supplies != null)
        {
            foreach($this->supplies as $key => $supply)
            {
                if($supply['particular_id'] == $this->supplies_particular_id && $supply['uom'] == $this->supplies_uom)
                {
                    $this->supplies[$key]['quantity'] = $this->supplies[$key]['quantity'] += $this->supplies_quantity;
                    $this->supplies[$key]['total_quantity'] = $this->supplies[$key]['total_quantity'] += $this->supplies_total_quantity;
                    $this->supplies[$key]['estimated_budget'] = $this->supplies[$key]['estimated_budget'] += $intEstimatedBudget;
                    $this->supplies[$key]['quantity'] = array_map(function($a, $b) {
                        return $a + $b;
                    }, $this->supplies[$key]['quantity'], $this->supplies_quantity);
                    break;
                }else{
                    $this->supplies[] = [
                        'budget_category_id' => 1,
                        'budget_category' => 'Supplies & Semi-Expendables',
                        'particular_id' => $this->supplies_particular_id,
                        'particular' => $this->supplies_category_attr->particulars,
                        'supply_code' => $this->supplies_category_attr->supply_code,
                        'specifications' => $this->supplies_category_attr->specifications,
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
                        'remarks' => $this->supplies_remarks,
                    ];
                }
            }
        }else{
            $this->supplies[] = [
                'budget_category_id' => 1,
                'budget_category' => 'Supplies & Semi-Expendables',
                'particular_id' => $this->supplies_particular_id,
                'particular' => $this->supplies_category_attr->particulars,
                'supply_code' => $this->supplies_category_attr->supply_code,
                'specifications' => $this->supplies_category_attr->specifications,
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
                'remarks' => $this->supplies_remarks,
            ];
        }

        if($this->wfp_fund->id === 3 || $this->wfp_fund->id === 4 || $this->wfp_fund->id === 5 || $this->wfp_fund->id === 6 || $this->wfp_fund->id === 7)
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
        $this->data['supplies_particular'] = null;
        $this->supplies_particular_id = null;
        $this->supplies_specs = null;
        $this->supplies_code = null;
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
        $this->supplies_is_remarks = false;
        $this->supplies_remarks = null;
    }


    public function updatedMooeParticularId()
    {
        if($this->mooe_particular_id != null)
        {
            $this->mooe_category_attr = Supply::find($this->mooe_particular_id);
            $this->mooe_specs = $this->mooe_category_attr->specifications;
            $this->mooe_code = $this->mooe_category_attr->supply_code;
            $this->mooe_uacs = $this->mooe_category_attr->categoryItems->uacs_code;
            $this->mooe_title_group = $this->mooe_category_attr->categoryGroups->name;
            $this->mooe_account_title = $this->mooe_category_attr->categoryItems->name;
            $this->mooe_ppmp = $this->mooe_category_attr->is_ppmp;
            $this->mooe_cost_per_unit = $this->mooe_category_attr->unit_cost;
            $this->mooe_quantity = array_fill(0, 12, 0);
            $this->calculateMooeTotalQuantity();

        }else{
            $this->mooe_category_attr = null;
            $this->mooe_specs = null;
            $this->mooe_code = null;
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
        $cost_per_unit = $this->mooe_cost_per_unit == null  ? 0 : $this->mooe_cost_per_unit;
        $this->mooe_total_quantity = array_sum($this->mooe_quantity);
        $this->mooe_estimated_budget = number_format($this->mooe_total_quantity * $cost_per_unit, 2);
    }

    public function updatedMooeIsRemarks()
    {
        if($this->mooe_is_remarks === false)
        {
            $this->mooe_remarks = null;
        }
    }

    public function addMooe()
    {
        //validate all step 2
        $this->validate([
            'mooe_particular_id' => 'required',
            'mooe_uom' => 'required',
            'mooe_cost_per_unit' => 'required|gt:0',
            'mooe_total_quantity' => 'gt:0',
        ],
        [
            'mooe_particular_id.required' => 'Particulars is required',
            'mooe_uom.required' => 'UOM is required',
            'mooe_cost_per_unit.required' => 'Cost per unit is required',
            'mooe_cost_per_unit.gt' => 'Cost per unit must be greater than 0',
            'mooe_total_quantity.gt' => 'Total quantity must be greater than 0',
        ]);
        $intEstimatedBudget = (int)str_replace(',', '', $this->mooe_estimated_budget);
        if($this->mooe != null)
        {
            foreach($this->mooe as $key => $mooe)
            {
                if($mooe['particular_id'] == $this->mooe_particular_id && $mooe['uom'] == $this->mooe_uom)
                {
                    $this->mooe[$key]['quantity'] = $this->mooe[$key]['quantity'] += $this->mooe_quantity;
                    $this->mooe[$key]['total_quantity'] = $this->mooe[$key]['total_quantity'] += $this->mooe_total_quantity;
                    $this->mooe[$key]['estimated_budget'] = $this->mooe[$key]['estimated_budget'] += $intEstimatedBudget;
                    $this->mooe[$key]['quantity'] = array_map(function($a, $b) {
                        return $a + $b;
                    }, $this->mooe[$key]['quantity'], $this->mooe_quantity);
                    break;
                }else{
                    $this->mooe[] = [
                        'budget_category_id' => 2,
                        'budget_category' => 'MOOE',
                        'particular_id' => $this->mooe_particular_id,
                        'particular' => $this->mooe_category_attr->particulars,
                        'supply_code' => $this->mooe_category_attr->supply_code,
                        'specifications' => $this->mooe_category_attr->specifications,
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
                        'remarks' => $this->mooe_remarks,
                    ];
                }
            }
        }else{
            $this->mooe[] = [
                'budget_category_id' => 2,
                'budget_category' => 'MOOE',
                'particular_id' => $this->mooe_particular_id,
                'particular' => $this->mooe_category_attr->particulars,
                'supply_code' => $this->mooe_category_attr->supply_code,
                'specifications' => $this->mooe_category_attr->specifications,
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
                'remarks' => $this->mooe_remarks,
            ];
        }

        if($this->wfp_fund->id === 3 || $this->wfp_fund->id === 4 || $this->wfp_fund->id === 5 || $this->wfp_fund->id === 6 || $this->wfp_fund->id === 7)
        {
            $categoryGroupId = $this->mooe_category_attr->categoryGroups->id;
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
                    'category_group' => $this->mooe_category_attr->categoryGroups->name,
                    'initial_amount' => 0,
                    'current_total' => $intEstimatedBudget,
                    'balance' => $intEstimatedBudget,
                ];
            }

        }else{
            //add current_total to current balance from estimated budget
            foreach ($this->current_balance as $key => $balance) {
                if($balance['category_group_id'] == $this->mooe_category_attr->categoryGroups->id)
                {
                    $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                    $this->current_balance[$key]['balance'] -= $intEstimatedBudget;
                }
            }
        }

        //add current_total to current balance from estimated budget
        // foreach ($this->current_balance as $key => $balance) {
        //     if($balance['category_group_id'] == $this->mooe_category_attr->categoryGroups->id)
        //     {
        //         $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
        //         $this->current_balance[$key]['balance'] -= $intEstimatedBudget;
        //     }
        // }

        $this->clearMooe();
    }

    public function showMooeDetails()
    {
        $this->mooeDetailModal = true;
    }

    public function clearMooe()
    {
        $this->data['supplies_particular'] = null;
        $this->mooe_particular_id = null;
        $this->mooe_specs = null;
        $this->mooe_code = null;
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
        $this->mooe_is_remarks = false;
    }

    public function updatedTrainingParticularId()
    {
        if($this->training_particular_id != null)
        {
            $this->training_category_attr = Supply::find($this->training_particular_id);
            $this->training_specs = $this->training_category_attr->specifications;
            $this->training_code = $this->training_category_attr->supply_code;
            $this->training_uacs = $this->training_category_attr->categoryItems->uacs_code;
            $this->training_title_group = $this->training_category_attr->categoryGroups->name;
            $this->training_account_title = $this->training_category_attr->categoryItems->name;
            $this->training_ppmp = $this->training_category_attr->is_ppmp;
            $this->training_cost_per_unit = $this->training_category_attr->unit_cost;
            $this->training_quantity = array_fill(0, 12, 0);
            $this->calculateTrainingTotalQuantity();

        }else{
            $this->training_category_attr = null;
            $this->training_specs = null;
            $this->training_code = null;
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
        $cost_per_unit = $this->training_cost_per_unit == null  ? 0 : $this->training_cost_per_unit;
        $this->training_total_quantity = array_sum($this->training_quantity);
        $this->training_estimated_budget = number_format($this->training_total_quantity * $cost_per_unit, 2);
    }

    public function updatedTrainingIsRemarks()
    {
        if($this->training_is_remarks === false)
        {
            $this->training_remarks = null;
        }
    }

    public function addTraining()
    {
        //validate all step 2
        $this->validate([
            'training_particular_id' => 'required',
            'training_uom' => 'required',
            'training_cost_per_unit' => 'required|gt:0',
            'training_total_quantity' => 'gt:0',
        ],
        [
            'training_particular_id.required' => 'Particulars is required',
            'training_uom.required' => 'UOM is required',
            'training_cost_per_unit.required' => 'Cost per unit is required',
            'training_cost_per_unit.gt' => 'Cost per unit must be greater than 0',
            'training_total_quantity.gt' => 'Total quantity must be greater than 0',
        ]);
        $intEstimatedBudget = (int)str_replace(',', '', $this->training_estimated_budget);
        if($this->trainings != null)
        {
            foreach($this->trainings as $key => $training)
            {
                if($training['particular_id'] == $this->training_particular_id && $training['uom'] == $this->training_uom)
                {
                    $this->trainings[$key]['quantity'] = $this->trainings[$key]['quantity'] += $this->training_quantity;
                    $this->trainings[$key]['total_quantity'] = $this->trainings[$key]['total_quantity'] += $this->training_total_quantity;
                    $this->trainings[$key]['estimated_budget'] = $this->trainings[$key]['estimated_budget'] += $intEstimatedBudget;
                    $this->trainings[$key]['quantity'] = array_map(function($a, $b) {
                        return $a + $b;
                    }, $this->trainings[$key]['quantity'], $this->training_quantity);
                    break;
                }else{
                    $this->trainings[] = [
                        'budget_category_id' => 3,
                        'budget_category' => 'Trainings',
                        'particular_id' => $this->training_particular_id,
                        'particular' => $this->training_category_attr->particulars,
                        'supply_code' => $this->training_category_attr->supply_code,
                        'specifications' => $this->training_category_attr->specifications,
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
                        'remarks' => $this->training_remarks,
                    ];
                }
            }
        }else{
            $this->trainings[] = [
                'budget_category_id' => 3,
                'budget_category' => 'Trainings',
                'particular_id' => $this->training_particular_id,
                'particular' => $this->training_category_attr->particulars,
                'supply_code' => $this->training_category_attr->supply_code,
                'specifications' => $this->training_category_attr->specifications,
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
                'remarks' => $this->training_remarks,
            ];
        }

        if($this->wfp_fund->id === 3 || $this->wfp_fund->id === 4 || $this->wfp_fund->id === 5 || $this->wfp_fund->id === 6 || $this->wfp_fund->id === 7)
        {
            $categoryGroupId = $this->training_category_attr->categoryGroups->id;
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
                    'category_group' => $this->training_category_attr->categoryGroups->name,
                    'initial_amount' => 0,
                    'current_total' => $intEstimatedBudget,
                    'balance' => $intEstimatedBudget,
                ];
            }

        }else{
            //add current_total to current balance from estimated budget
            foreach ($this->current_balance as $key => $balance) {
                if($balance['category_group_id'] == $this->training_category_attr->categoryGroups->id)
                {
                    $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                    $this->current_balance[$key]['balance'] -= $intEstimatedBudget;
                }
            }
        }

        //add current_total to current balance from estimated budget
        // foreach ($this->current_balance as $key => $balance) {
        //     if($balance['category_group_id'] == $this->training_category_attr->categoryGroups->id)
        //     {
        //         $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
        //         $this->current_balance[$key]['balance'] -= $intEstimatedBudget;
        //     }
        // }

        $this->clearTrainings();
    }

    public function showTrainingDetails()
    {
        $this->trainingDetailModal = true;
    }

    public function clearTrainings()
    {
        $this->data['supplies_particular'] = null;
        $this->training_particular_id = null;
        $this->training_specs = null;
        $this->training_code = null;
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
        $this->training_is_remarks = false;
    }

    public function updatedMachineParticularId()
    {
        if($this->machine_particular_id != null)
        {
            $this->machine_category_attr = Supply::find($this->machine_particular_id);
            $this->machine_specs = $this->machine_category_attr->specifications;
            $this->machine_code = $this->machine_category_attr->supply_code;
            $this->machine_uacs = $this->machine_category_attr->categoryItems->uacs_code;
            $this->machine_title_group = $this->machine_category_attr->categoryGroups->name;
            $this->machine_account_title = $this->machine_category_attr->categoryItems->name;
            $this->machine_ppmp = $this->machine_category_attr->is_ppmp;
            $this->machine_cost_per_unit = $this->machine_category_attr->unit_cost;
            $this->machine_quantity = array_fill(0, 12, 0);
            $this->calculateMachineTotalQuantity();

        }else{
            $this->machine_category_attr = null;
            $this->machine_specs = null;
            $this->machine_code = null;
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
        $cost_per_unit = $this->machine_cost_per_unit == null  ? 0 : $this->machine_cost_per_unit;
        $this->machine_total_quantity = array_sum($this->machine_quantity);
        $this->machine_estimated_budget = number_format($this->machine_total_quantity * $cost_per_unit, 2);
    }

    public function updatedMachineIsRemarks()
    {
        if($this->machine_is_remarks === false)
        {
            $this->machine_remarks = null;
        }
    }

    public function addMachine()
    {
        //validate all step 2
        $this->validate([
            'machine_particular_id' => 'required',
            'machine_uom' => 'required',
            'machine_cost_per_unit' => 'required|gt:0',
            'machine_total_quantity' => 'gt:0',
        ],
        [
            'machine_particular_id.required' => 'Particulars is required',
            'machine_uom.required' => 'UOM is required',
            'machine_cost_per_unit.required' => 'Cost per unit is required',
            'machine_cost_per_unit.gt' => 'Cost per unit must be greater than 0',
            'machine_total_quantity.gt' => 'Total quantity must be greater than 0',
        ]);
        $intEstimatedBudget = (int)str_replace(',', '', $this->machine_estimated_budget);

        if($this->machines != null)
        {
            foreach($this->machines as $key => $machine)
            {
                if($machine['particular_id'] == $this->machine_particular_id && $machine['uom'] == $this->machine_uom)
                {
                    $this->machines[$key]['quantity'] = $this->machines[$key]['quantity'] += $this->machine_quantity;
                    $this->machines[$key]['total_quantity'] = $this->machines[$key]['total_quantity'] += $this->machine_total_quantity;
                    $this->machines[$key]['estimated_budget'] = $this->machines[$key]['estimated_budget'] += $intEstimatedBudget;
                    $this->machines[$key]['quantity'] = array_map(function($a, $b) {
                        return $a + $b;
                    }, $this->machines[$key]['quantity'], $this->machine_quantity);
                    break;
                }else{
                    $this->machines[] = [
                        'budget_category_id' => 4,
                        'budget_category' => 'Machine & Equipment / Furniture & Fixtures / Bio / Vehicles',
                        'particular_id' => $this->machine_particular_id,
                        'particular' => $this->machine_category_attr->particulars,
                        'supply_code' => $this->machine_category_attr->supply_code,
                        'specifications' => $this->machine_category_attr->specifications,
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
                        'remarks' => $this->machine_remarks,
                    ];
                }
            }
        }else{
            $this->machines[] = [
                'budget_category_id' => 4,
                'budget_category' => 'Machine & Equipment / Furniture & Fixtures / Bio / Vehicles',
                'particular_id' => $this->machine_particular_id,
                'particular' => $this->machine_category_attr->particulars,
                'supply_code' => $this->machine_category_attr->supply_code,
                'specifications' => $this->machine_category_attr->specifications,
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
                'remarks' => $this->machine_remarks,
            ];
        }


        if($this->wfp_fund->id === 3 || $this->wfp_fund->id === 4 || $this->wfp_fund->id === 5 || $this->wfp_fund->id === 6 || $this->wfp_fund->id === 7)
        {
            $categoryGroupId = $this->machine_category_attr->categoryGroups->id;
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
                    'category_group' => $this->machine_category_attr->categoryGroups->name,
                    'initial_amount' => 0,
                    'current_total' => $intEstimatedBudget,
                    'balance' => $intEstimatedBudget,
                ];
            }

        }else{
            //add current_total to current balance from estimated budget
            foreach ($this->current_balance as $key => $balance) {
                if($balance['category_group_id'] == $this->machine_category_attr->categoryGroups->id)
                {
                    $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                    $this->current_balance[$key]['balance'] -= $intEstimatedBudget;
                }
            }
        }

        //add current_total to current balance from estimated budget
        // foreach ($this->current_balance as $key => $balance) {
        //     if($balance['category_group_id'] == $this->machine_category_attr->categoryGroups->id)
        //     {
        //         $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
        //         $this->current_balance[$key]['balance'] -= $intEstimatedBudget;
        //     }
        // }


        $this->clearMachine();
    }

    public function showMachineDetails()
    {
        $this->machineDetailModal = true;
    }

    public function clearMachine()
    {
        $this->data['supplies_particular'] = null;
        $this->machine_particular_id = null;
        $this->machine_specs = null;
        $this->machine_code = null;
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
        $this->machine_is_remarks = false;
    }

    public function updatedBuildingParticularId()
    {
        if($this->building_particular_id != null)
        {
            $this->building_category_attr = Supply::find($this->building_particular_id);
            $this->building_specs = $this->building_category_attr->specifications;
            $this->building_code = $this->building_category_attr->supply_code;
            $this->building_uacs = $this->building_category_attr->categoryItems->uacs_code;
            $this->building_title_group = $this->building_category_attr->categoryGroups->name;
            $this->building_account_title = $this->building_category_attr->categoryItems->name;
            $this->building_ppmp = $this->building_category_attr->is_ppmp;
            $this->building_cost_per_unit = $this->building_category_attr->unit_cost;
            $this->building_quantity = array_fill(0, 12, 0);
            $this->calculateBuildingTotalQuantity();

        }else{
            $this->building_category_attr = null;
            $this->building_specs = null;
            $this->building_code = null;
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
        $cost_per_unit = $this->building_cost_per_unit == null  ? 0 : $this->building_cost_per_unit;
        $this->building_total_quantity = array_sum($this->building_quantity);
        $this->building_estimated_budget = number_format($this->building_total_quantity * $cost_per_unit, 2);
    }

    public function updatedBuildingIsRemarks()
    {
        if($this->building_is_remarks === false)
        {
            $this->building_remarks = null;
        }
    }

    public function addBuilding()
    {
        //validate all step 2
        $this->validate([
            'building_particular_id' => 'required',
            'building_uom' => 'required',
            'building_cost_per_unit' => 'required|gt:0',
            'building_total_quantity' => 'gt:0',
        ],
        [
            'building_particular_id.required' => 'Particulars is required',
            'building_uom.required' => 'UOM is required',
            'building_cost_per_unit.required' => 'Cost per unit is required',
            'building_cost_per_unit.gt' => 'Cost per unit must be greater than 0',
            'building_total_quantity.gt' => 'Total quantity must be greater than 0',
        ]);
        $intEstimatedBudget = (int)str_replace(',', '', $this->building_estimated_budget);

        if($this->buildings != null)
        {
            foreach($this->buildings as $key => $building)
            {
                if($building['particular_id'] == $this->building_particular_id && $building['uom'] == $this->building_uom)
                {
                    $this->buildings[$key]['quantity'] = $this->buildings[$key]['quantity'] += $this->building_quantity;
                    $this->buildings[$key]['total_quantity'] = $this->buildings[$key]['total_quantity'] += $this->building_total_quantity;
                    $this->buildings[$key]['estimated_budget'] = $this->buildings[$key]['estimated_budget'] += $intEstimatedBudget;
                    $this->buildings[$key]['quantity'] = array_map(function($a, $b) {
                        return $a + $b;
                    }, $this->buildings[$key]['quantity'], $this->building_quantity);
                    break;
                }else{
                    $this->buildings[] = [
                        'budget_category_id' => 5,
                        'budget_category' => 'Building & Infrastructure',
                        'particular_id' => $this->building_particular_id,
                        'particular' => $this->building_category_attr->particulars,
                        'supply_code' => $this->building_category_attr->supply_code,
                        'specifications' => $this->building_category_attr->specifications,
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
                        'remarks' => $this->building_remarks,
                    ];
                }
            }
        }else{
            $this->buildings[] = [
                'budget_category_id' => 5,
                'budget_category' => 'Building & Infrastructure',
                'particular_id' => $this->building_particular_id,
                'particular' => $this->building_category_attr->particulars,
                'supply_code' => $this->building_category_attr->supply_code,
                'specifications' => $this->building_category_attr->specifications,
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
                'remarks' => $this->building_remarks,
            ];
        }

        if($this->wfp_fund->id === 3 || $this->wfp_fund->id === 4 || $this->wfp_fund->id === 5 || $this->wfp_fund->id === 6 || $this->wfp_fund->id === 7)
        {
            $categoryGroupId = $this->building_category_attr->categoryGroups->id;
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
                    'category_group' => $this->building_category_attr->categoryGroups->name,
                    'initial_amount' => 0,
                    'current_total' => $intEstimatedBudget,
                    'balance' => $intEstimatedBudget,
                ];
            }

        }else{
            //add current_total to current balance from estimated budget
            foreach ($this->current_balance as $key => $balance) {
                if($balance['category_group_id'] == $this->building_category_attr->categoryGroups->id)
                {
                    $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                    $this->current_balance[$key]['balance'] -= $intEstimatedBudget;
                }
            }
        }

        //add current_total to current balance from estimated budget
        // foreach ($this->current_balance as $key => $balance) {
        //     if($balance['category_group_id'] == $this->building_category_attr->categoryGroups->id)
        //     {
        //         $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
        //         $this->current_balance[$key]['balance'] -= $intEstimatedBudget;
        //     }
        // }


        $this->clearBuilding();
    }


    public function showBuildingDetails()
    {
        $this->buildingDetailModal = true;
    }

    public function clearBuilding()
    {
        $this->data['supplies_particular'] = null;
        $this->building_particular_id = null;
        $this->building_specs = null;
        $this->building_code = null;
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
        $this->building_is_remarks = false;
    }

    public function decreaseStep()
    {
        $this->global_index--;
        $this->data['supplies_particular'] = null;
    }

    public function increaseStep()
    {
        $this->global_index++;
        $this->data['supplies_particular'] = null;
    }


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
            $sumAllocated = $this->record->fundAllocations->sum('initial_amount');
            $sumTotal = array_sum(array_column($this->current_balance, 'current_total'));
            $sumBalance = $sumAllocated - $sumTotal;
            $wfp = Wfp::create([
                'cost_center_id' => $this->record->id,
                'wpf_type_id' => $this->wfp_type->id,
                'fund_cluster_w_f_p_s_id' => $this->wfp_fund->id,
                'user_id' => auth()->user()->id,
                'fund_description' => $this->fund_description,
                'source_fund' => $this->source_fund,
                'confirm_fund_source' => $this->confirm_fund_source ?? null,
                'specify_fund_source' =>$this->specify_fund_source,
                'balance' => $sumBalance,
                'program_allocated' => $sumTotal,
                'total_allocated_fund' => $sumAllocated,
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
                    'remarks' => $item['remarks'],
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
                    'remarks' => $item['remarks'],
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
                    'remarks' => $item['remarks'],
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
                    'remarks' => $item['remarks'],
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
                    'remarks' => $item['remarks'],
                ]);
            }

            DB::commit();

            $this->dialog()->success(
                $title = 'Operation Successful',
                $description = 'WFP has been successfully created',
            );

            return redirect()->route('wfp.wfp-history');
        }else{
            $this->dialog()->error(
                $title = 'Operation Failed',
                $description = 'Your grand total has exceeded the budget allocation',
            );
            $this->suppliesDetailModal = false;
        }
    }

    public function viewRemarks($index, $type)
    {
        $this->remarksModal = true;

        switch($type)
        {
            case 1:
                $this->supplies_remarks_details = $this->supplies[$index]['remarks'];
                $this->remarks_modal_title = 'Supplies & Semi-Expendables';
                break;
            case 2:
                $this->mooe_remarks_details = $this->mooe[$index]['remarks'];
                $this->remarks_modal_title = 'MOOE';
                break;
            case 3:
                $this->training_remarks_details = $this->trainings[$index]['remarks'];
                $this->remarks_modal_title = 'Trainings';
                break;
            case 4:
                $this->machine_remarks_details = $this->machines[$index]['remarks'];
                $this->remarks_modal_title = 'Machine & Equipment / Furniture & Fixtures / Bio / Vehicles';
                break;
            case 5:
                $this->building_remarks_details = $this->buildings[$index]['remarks'];
                $this->remarks_modal_title = 'Building & Infrastructure';
                break;
            default:
                $this->remarks_modal_title = 'Remarks';
        }

    }

    public function deleteSupply($index)
    {
        if (isset($this->supplies[$index])) {
            $budget = $this->supplies[$index]['estimated_budget'];
            $title_group = $this->supplies[$index]['title_group'];

            foreach ($this->current_balance as $key => $item) {
                if ($item['category_group_id'] === $title_group) {
                    if (isset($this->current_balance[$key]['current_total']) && is_numeric($this->current_balance[$key]['current_total'])) {
                        $this->current_balance[$key]['current_total'] -= $budget;
                        $this->current_balance[$key]['balance'] += $budget;
                    }
                    break;
                }
            }
            // Remove the supply at the given index
            unset($this->supplies[$index]);
            // Reset the array indices to avoid undefined index issues
            $this->supplies = array_values($this->supplies);
        }

    }

    public function deleteMooe($index)
    {
        if (isset($this->mooe[$index])) {
            $budget = $this->mooe[$index]['estimated_budget'];
            $title_group = $this->mooe[$index]['title_group'];

            foreach ($this->current_balance as $key => $item) {
                if ($item['category_group_id'] === $title_group) {
                    if (isset($this->current_balance[$key]['current_total']) && is_numeric($this->current_balance[$key]['current_total'])) {
                        $this->current_balance[$key]['current_total'] -= $budget;
                        $this->current_balance[$key]['balance'] += $budget;
                    }
                    break;
                }
            }
            // Remove the supply at the given index
            unset($this->mooe[$index]);
            // Reset the array indices to avoid undefined index issues
            $this->mooe = array_values($this->mooe);
        }
    }

    public function deleteTraining($index)
    {
        if (isset($this->trainings[$index])) {
            $budget = $this->trainings[$index]['estimated_budget'];
            $title_group = $this->trainings[$index]['title_group'];

            foreach ($this->current_balance as $key => $item) {
                if ($item['category_group_id'] === $title_group) {
                    if (isset($this->current_balance[$key]['current_total']) && is_numeric($this->current_balance[$key]['current_total'])) {
                        $this->current_balance[$key]['current_total'] -= $budget;
                        $this->current_balance[$key]['balance'] += $budget;
                    }
                    break;
                }
            }
            // Remove the supply at the given index
            unset($this->trainings[$index]);
            // Reset the array indices to avoid undefined index issues
            $this->trainings = array_values($this->trainings);
        }
    }

    public function deleteMachine($index)
    {
        if (isset($this->machines[$index])) {
            $budget = $this->machines[$index]['estimated_budget'];
            $title_group = $this->machines[$index]['title_group'];

            foreach ($this->current_balance as $key => $item) {
                if ($item['category_group_id'] === $title_group) {
                    if (isset($this->current_balance[$key]['current_total']) && is_numeric($this->current_balance[$key]['current_total'])) {
                        $this->current_balance[$key]['current_total'] -= $budget;
                        $this->current_balance[$key]['balance'] += $budget;
                    }
                    break;
                }
            }
            // Remove the supply at the given index
            unset($this->machines[$index]);
            // Reset the array indices to avoid undefined index issues
            $this->machines = array_values($this->machines);
        }
    }

    public function deleteBuilding($index)
    {
        if (isset($this->buildings[$index])) {
            $budget = $this->buildings[$index]['estimated_budget'];
            $title_group = $this->buildings[$index]['title_group'];

            foreach ($this->current_balance as $key => $item) {
                if ($item['category_group_id'] === $title_group) {
                    if (isset($this->current_balance[$key]['current_total']) && is_numeric($this->current_balance[$key]['current_total'])) {
                        $this->current_balance[$key]['current_total'] -= $budget;
                        $this->current_balance[$key]['balance'] += $budget;
                    }
                    break;
                }
            }
            // Remove the supply at the given index
            unset($this->buildings[$index]);
            // Reset the array indices to avoid undefined index issues
            $this->buildings = array_values($this->buildings);
        }
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
