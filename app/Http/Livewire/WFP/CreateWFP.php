<?php

namespace App\Http\Livewire\WFP;

use App\Models\Wfp;
use Filament\Forms;
use App\Models\Supply;
use App\Models\WpfType;
use Livewire\Component;
use App\Models\FundDraft;
use App\Models\WfpDetail;
use App\Models\CostCenter;
use WireUi\Traits\Actions;
use App\Models\CategoryGroup;
use App\Models\CategoryItems;
use App\Models\FundDraftItem;
use App\Models\BudgetCategory;
use App\Models\FundDraftAmount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use Filament\Notifications\Notification;
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
    public $ps = [];
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
    //step 7
    public $ps_is_remarks;
    public $ps_remarks;
    public $ps_remarks_details;
    public $ps_particulars;
    public $ps_particular_id;
    public $ps_specs;
    public $ps_code;
    public $ps_category_attr;
    public $ps_uacs;
    public $ps_title_group;
    public $ps_account_title;
    public $ps_quantity = [];
    public $ps_total_quantity;
    public $ps_ppmp;
    public $ps_uom;
    public $ps_cost_per_unit;
    public $ps_estimated_budget;

    //modals
    public $remarksModal = false;
    public $suppliesDetailModal = false;
    public $mooeDetailModal = false;
    public $trainingDetailModal = false;
    public $machineDetailModal = false;
    public $buildingDetailModal = false;
    public $psDetailModal = false;
    public $wfp_param;

    public $supplies_particular;
    public $is_supplemental;
    public $supplemental_id;
    public $wfp_balance;

    public $programmed = [];
    public $programmed_supplemental = [];
    public $programmed_non_supplemental = 0;

    public $draft_amounts = [];

    public $supplementalQuarterId = null;
    protected $queryString = ['supplementalQuarterId'];

    public $budgetCategoryTabIds = [
        2 => 1,
        3 => 2,
        4 => 3,
        5 => 4,
        6 => 5,
        7 => 6
    ];

    public $categoryIds = [];
    public function mount($record, $wfpType, $isEdit, $isSupplemental)
    {
        $this->is_supplemental = $isSupplemental;
        $costCenter_id = Wfp::where('cost_center_id', $record)->first()?->cost_center_id;
        $this->wfp_param = $wfpType;
        if ($isEdit == 1) {
            $this->record = CostCenter::with([
                'fundAllocations' => function ($query) use ($wfpType) {
                    $query->where('wpf_type_id', $wfpType)->with('categoryGroup');
                }
            ])
                ->where('id', $costCenter_id)->whereHas('fundAllocations', function ($query) use ($wfpType) {
                    $query->where('wpf_type_id', $wfpType)
                        ->when(!is_null($this->supplementalQuarterId), function ($query) {
                            if ($this->supplementalQuarterId == 1) {
                                $query->where(
                                    'supplemental_quarter_id',
                                    $this->supplementalQuarterId
                                )->orWhere('supplemental_quarter_id', null);
                            } else {
                                $query->where(
                                    'supplemental_quarter_id',
                                    '<=',
                                    $this->supplementalQuarterId
                                )->orWhere('supplemental_quarter_id', null);
                            }
                        });
                })->first();
        } else {
            $this->record = CostCenter::with([
                'wfp' => function ($query) use ($wfpType) {
                    $query->where('wpf_type_id', $wfpType)->with('wfpDetails');
                },
                'fundAllocations' => function ($query) use ($wfpType) {
                    $query->where('wpf_type_id', $wfpType)->when(
                        !is_null($this->supplementalQuarterId),
                        function ($query) {
                            $query->where('is_supplemental', 0)->orWhere(function ($query) {
                                $query->where(
                                    'supplemental_quarter_id',
                                    '!=',
                                    null
                                )->where('supplemental_quarter_id', '<=', $this->supplementalQuarterId);
                            });
                        }
                    )->with('categoryGroup');
                }
            ])->where('id', $record)->whereHas('fundAllocations', function ($query) use ($wfpType) {
                $query->where('wpf_type_id', $wfpType);
            })->first();
        }

        abort_unless($this->record, 404, 'Record not found.');

        if ($isSupplemental) {
            $this->wfp_type = WpfType::findOrFail($wfpType);
            $this->wfp_fund = $this->record->fundAllocations->where(
                'wpf_type_id',
                $wfpType
            )->where('supplemental_quarter_id', $this->supplementalQuarterId)->first()->fundClusterWFP;
            $this->fund_allocations = $this->record->fundAllocations->where(
                'wpf_type_id',
                $wfpType
            )->where('supplemental_quarter_id', $this->supplementalQuarterId);
        } else {
            $this->wfp_type = $this->record->fundAllocations->where('wpf_type_id', $wfpType)->first()->wpfType;
            $this->wfp_fund = $this->record->fundAllocations->where(
                'wpf_type_id',
                $wfpType
            )->first()->fundClusterWFP;
            $this->fund_allocations = $this->record->fundAllocations->where('wpf_type_id', $wfpType);
        }

        $this->costCenter = $this->record->where(
            'office_id',
            auth()->user()->employee_information->office_id
        )->first();

        $this->fund_description = $this->wfp_fund->fund_source;
        $this->form->fill();
        $this->global_index = 1;

        if (in_array($this->wfp_fund->id, [1, 3, 9])) {
            if ($isSupplemental) {
                if ($this->record->fundAllocations->where('wpf_type_id', $wfpType)->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->first()->fundDrafts()->first()?->draft_amounts()->exists()) {
                    // HERE DRAFT
                    $draft_amounts = $this->record->fundAllocations->where(
                        'wpf_type_id',
                        $wfpType
                    )->where(
                        'supplemental_quarter_id',
                        $this->supplementalQuarterId
                    )->first()->fundDrafts->first()->draft_items()->get();

                    $this->categoryIds = $draft_amounts->pluck('budget_category_id')->toArray();

                    if ($draft_amounts) {
                        foreach ($draft_amounts as $draft_amount) {
                            if (!isset($this->draft_amounts[$draft_amount->title_group])) {
                                $this->draft_amounts[$draft_amount->title_group] = 0;
                            }
                            $this->draft_amounts[$draft_amount->title_group] += $draft_amount->estimated_budget;
                        }
                    }
                    $workFinancialPlans = $this->record->wfp->filter(function ($wfp) {
                        return $wfp->is_supplemental === 0 || ($wfp->supplemental_quarter_id < $this->supplementalQuarterId && $wfp->supplemental_quarter_id !== null);
                    });

                    if ($workFinancialPlans) {
                        $all_prev_programmed = $workFinancialPlans->filter(function ($allocation) {
                            return $allocation->is_supplemental === 0 || ($allocation->supplemental_quarter_id < $this->supplementalQuarterId && $allocation->supplemental_quarter_id !== null);
                        });
                        foreach ($all_prev_programmed as $wfp) {
                            foreach ($wfp->wfpDetails as $allocation) {
                                if (!isset($this->programmed[$allocation->category_group_id])) {
                                    $this->programmed[$allocation->category_group_id] = 0;
                                }
                                $this->programmed[$allocation->category_group_id] += ($allocation->total_quantity * $allocation->cost_per_unit);
                            }
                        }
                        $all_current_programmed = $workFinancialPlans->filter(function ($allocation) {
                            return $allocation->supplemental_quarter_id === $this->supplementalQuarterId;
                        });
                        foreach ($all_current_programmed as $wfp) {
                            foreach ($wfp->wfpDetails as $allocation) {
                                if (!isset($this->programmed_supplemental[$allocation->category_group_id])) {
                                    $this->programmed_supplemental[$allocation->category_group_id] = 0;
                                }
                                $this->programmed_supplemental[$allocation->category_group_id] += ($allocation->total_quantity * $allocation->cost_per_unit);
                            }
                        }
                    }
                    $costCenterFundAllocations = $this->record->fundAllocations;
                    $allocation_non_supplemental = [];

                    $prev_allocation = $costCenterFundAllocations->filter(function ($allocation) {
                        return $allocation->is_supplemental === 0 || $allocation->supplemental_quarter_id < (int) $this->supplementalQuarterId;
                    });
                    foreach ($prev_allocation as $allocation) {
                        $allocation_non_supplemental[$allocation->category_group_id] = $allocation->initial_amount;
                    }

                    $all_current_allocation = $costCenterFundAllocations->filter(function ($allocation) {
                        return $allocation->supplemental_quarter_id === $this->supplementalQuarterId;
                    });

                    //
                    $this->current_balance = $costCenterFundAllocations
                        ->filter(function ($allocation) {
                            return $allocation->initial_amount > 0 && $allocation->categoryGroup?->is_active == 1;
                        })
                        ->map(function ($allocation) use (
                            $allocation_non_supplemental,
                            $costCenterFundAllocations,
                            $all_current_allocation,
                            $prev_allocation,
                            $all_prev_programmed
                        ) {
                            $current_and_prev_allocation = $allocation_non_supplemental[$allocation->category_group_id] ?? 0;

                            $total_programmed_draft = isset($this->draft_amounts[$allocation->category_group_id]) ? $this->draft_amounts[$allocation->category_group_id] : 0;

                            if ($allocation->supplemental_quarter_id == $this->supplementalQuarterId) {
                                $current_and_prev_allocation += $allocation->initial_amount;
                            }

                            if ($total_programmed_draft === 0) {
                                $total_programmed = isset($this->programmed[$allocation->category_group_id]) ? $this->programmed[$allocation->category_group_id] : 0;
                            } else {
                                $total_programmed = 0;
                            }
                            if ($allocation->supplemental_quarter_id !== $this->supplementalQuarterId && !empty($all_current_allocation->where(
                                'category_group_id',
                                $allocation->category_group_id
                            )->first())) {
                                return null;
                            }
                            if ($allocation->supplemental_quarter_id === $this->supplementalQuarterId) {
                                return [
                                    'category_group_id' => $allocation->category_group_id,
                                    'category_group' => $allocation->categoryGroup?->name,
                                    'initial_amount' => $current_and_prev_allocation - ($this->programmed[$allocation->category_group_id] ?? 0),
                                    'current_total' => ($this->draft_amounts[$allocation->category_group_id] ?? 0),
                                    'balance' => $current_and_prev_allocation + $allocation->initial_amount - ($this->programmed[$allocation->category_group_id] ?? 0),
                                    'sort_id' => $allocation->categoryGroup?->sort_id, // Adding sort_id for sorting
                                ];
                            } else {
                                return [
                                    'category_group_id' => $allocation->category_group_id,
                                    'category_group' => $allocation->categoryGroup?->name,
                                    'initial_amount' => $current_and_prev_allocation - $total_programmed,
                                    'current_total' => (($this->draft_amounts[$allocation->category_group_id] ?? 0) > 0 ? ($this->draft_amounts[$allocation->category_group_id] ?? 0) - ($allocation_non_supplemental[$allocation->category_group_id] ?? 0) : 0),
                                    'balance' => ($this->draft_amounts[$allocation->category_group_id] ?? 0),
                                    'sort_id' => $allocation->categoryGroup?->sort_id, // Adding sort_id for sorting
                                ];
                            }
                        })
                        ->filter(function ($item) {
                            return !is_null($item);
                        })
                        ->sortBy('sort_id') // Sort by sort_id
                        ->values()
                        ->toArray();

                    $this->programmed_non_supplemental = array_sum(array_diff_key(
                        $allocation_non_supplemental,
                        array_flip(array_column($this->current_balance, 'category_group_id'))
                    ));
                } else {
                    // HERE NON-DRAFT

                    $workFinancialPlans = $this->record->wfp->filter(function ($wfp) {
                        return $wfp->is_supplemental === 0 || ($wfp->supplemental_quarter_id < $this->supplementalQuarterId && $wfp->supplemental_quarter_id !== null);
                    });

                    if ($workFinancialPlans) {
                        $all_prev_programmed = $workFinancialPlans->filter(function ($allocation) {
                            return $allocation->is_supplemental === 0 || ($allocation->supplemental_quarter_id < (int) $this->supplementalQuarterId && $allocation->supplemental_quarter_id !== null);
                        });

                        foreach ($all_prev_programmed as $wfp) {
                            foreach ($wfp->wfpDetails as $allocation) {
                                if (!isset($this->programmed[$allocation->category_group_id])) {
                                    $this->programmed[$allocation->category_group_id] = 0;
                                }
                                $this->programmed[$allocation->category_group_id] += ($allocation->total_quantity * $allocation->cost_per_unit);
                            }
                        }

                        $all_current_programmed = $workFinancialPlans->filter(function ($allocation) {
                            return $allocation->supplemental_quarter_id === $this->supplementalQuarterId;
                        });
                        foreach ($all_current_programmed as $wfp) {
                            foreach ($wfp->wfpDetails as $allocation) {
                                if (!isset($this->programmed_supplemental[$allocation->category_group_id])) {
                                    $this->programmed_supplemental[$allocation->category_group_id] = 0;
                                }
                                $this->programmed_supplemental[$allocation->category_group_id] += ($allocation->total_quantity * $allocation->cost_per_unit);
                            }
                        }
                    }

                    $costCenterFundAllocations = $this->record->fundAllocations;
                    $allocation_non_supplemental = [];

                    $prev_allocation = $costCenterFundAllocations->filter(function ($allocation) {
                        return $allocation->is_supplemental === 0 || ($allocation->supplemental_quarter_id < (int) $this->supplementalQuarterId && $allocation->supplemental_quarter_id !== null);
                    });

                    foreach ($prev_allocation as $allocation) {
                        $allocation_non_supplemental[$allocation->category_group_id] = $allocation->initial_amount;
                    }

                    $all_current_allocation = $costCenterFundAllocations->filter(function ($allocation) {
                        return $allocation->supplemental_quarter_id === $this->supplementalQuarterId;
                    });

                    $this->current_balance = $costCenterFundAllocations
                        ->filter(function ($allocation) {
                            return $allocation->initial_amount > 0 && $allocation->categoryGroup?->is_active == 1;
                        })
                        ->map(function ($allocation) use ($allocation_non_supplemental, $all_current_allocation) {
                            $current_and_prev_allocation = $allocation_non_supplemental[$allocation->category_group_id] ?? 0;
                            if ($allocation->supplemental_quarter_id === $this->supplementalQuarterId) {
                                $current_and_prev_allocation += $allocation->initial_amount;
                            }

                            if ($allocation->supplemental_quarter_id !== $this->supplementalQuarterId && !empty($all_current_allocation->where(
                                'category_group_id',
                                $allocation->category_group_id
                            )->first())) {
                                return null;
                            }
                            return [
                                'category_group_id' => $allocation->category_group_id,
                                'category_group' => $allocation->categoryGroup?->name,
                                'initial_amount' => ($current_and_prev_allocation - ($this->programmed[$allocation->category_group_id] ?? 0)),
                                'current_total' => 0,
                                'balance' => $current_and_prev_allocation + $allocation->initial_amount - ($this->programmed[$allocation->category_group_id] ?? 0),
                                'sort_id' => $allocation->categoryGroup?->sort_id,
                                'supplemental_quarter_id' => $allocation->supplemental_quarter_id,
                                'programmed' => ($this->programmed[$allocation->category_group_id] ?? 0),
                                'allocated' => $current_and_prev_allocation
                            ];
                        })
                        ->filter(function ($item) {
                            return !is_null($item);
                        })
                        ->sortBy('sort_id') // Sort by sort_id
                        ->values()
                        ->toArray();
                    // dd($this->current_balance);
                }
            } else {
                $fundDrafts = $this->record->fundAllocations->where(
                    'wpf_type_id',
                    $wfpType
                )->first()->fundDrafts()->first()?->draft_amounts()->get();
                if (!empty($fundDrafts)) {
                     $this->categoryIds = $fundDrafts->pluck('category_group_id')->toArray();
                    $initial_amount = $this->record->fundAllocations->where('wpf_type_id', $wfpType);
                    $draft_amounts = $this->record->fundAllocations->where(
                        'wpf_type_id',
                        $wfpType
                    )->first()->fundDrafts->first()->draft_items;
                    $this->current_balance = $this->record->fundAllocations->where('wpf_type_id', $wfpType)
                        ->filter(function ($allocation) {
                            return $allocation->initial_amount != '0.00';
                        })
                        ->map(function ($allocation) use ($initial_amount, $draft_amounts) {
                            return [
                                'category_group_id' => $allocation->category_group_id,
                                'category_group' => $allocation->categoryGroup->name,
                                'initial_amount' => $initial_amount->where(
                                    'category_group_id',
                                    $allocation->category_group_id
                                )->first()->initial_amount ?? 0,
                                'current_total' => $draft_amounts->where(
                                    'title_group',
                                    $allocation->category_group_id
                                )->sum('estimated_budget') ?? 0,
                                'balance' => $initial_amount->where(
                                    'category_group_id',
                                    $allocation->category_group_id
                                )->first()->initial_amount ?? 0 - $draft_amounts->where(
                                    'title_group',
                                    $allocation->category_group_id
                                )->sum('estimated_budget') ?? 0,
                            ];
                        })
                        ->toArray();
                } else {
                    $this->current_balance = $this->record->fundAllocations
                        ->where('wpf_type_id', $wfpType)
                        ->filter(function ($allocation) {
                            return $allocation->initial_amount > 0 && $allocation->categoryGroup?->is_active == 1;
                        })
                        ->map(function ($allocation) {
                            return [
                                'category_group_id' => $allocation->category_group_id,
                                'category_group' => $allocation->categoryGroup?->name,
                                'initial_amount' => $allocation->initial_amount,
                                'current_total' => 0,
                                'balance' => $allocation->initial_amount,
                                'sort_id' => $allocation->categoryGroup?->sort_id, // Adding sort_id for sorting
                            ];
                        })
                        ->sortBy('sort_id') // Sort by sort_id
                        ->values()
                        ->toArray();
                }
            }
        } else {
            //164
            if ($isSupplemental) {

                if ($this->record->fundAllocations->where('wpf_type_id', $wfpType)->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->first()->fundDrafts()->first()?->draft_amounts()->exists()) {
                    if ($isSupplemental) {
                        $programmed = [];
                        if (count($this->record->wfp) > 0) {
                            $all_programmed = $this->record->wfp->filter(function ($wfp) use ($wfpType) {
                                return $wfp->is_supplemental === 0 || ($wfp->supplemental_quarter_id < $this->supplementalQuarterId && $wfp->supplemental_quarter_id !== null);
                            });

                            foreach ($all_programmed as $wfp) {
                                foreach ($wfp->wfpDetails as $allocation) {
                                    if (!isset($programmed[$allocation->category_group_id])) {
                                        $programmed[$allocation->category_group_id] = 0;
                                    }
                                    $programmed[$allocation->category_group_id] += ($allocation->total_quantity * $allocation->cost_per_unit);
                                }
                            }

                            $all_allocation = $this->record->fundAllocations->filter(function ($f) {
                                return $f->is_supplemental === 0 || ($f->supplemental_quarter_id <= $this->supplementalQuarterId && $f->supplemental_quarter_id !== null);
                            });

                            $this->wfp_balance = $all_allocation->sum('initial_amount') - array_sum($programmed);
                            $fundDraftId = $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $wfpType
                            )->where(
                                'supplemental_quarter_id',
                                $this->supplementalQuarterId
                            )->first()->fundDrafts->first()->id;
                            $fdrafts = FundDraftAmount::where(
                                'fund_draft_id',
                                $fundDraftId
                            )->with(['fundDraft'])->get();
                            $balance_pushes = false;
                            foreach ($fdrafts as $key => $allocation) {
                                $init = 0;
                                if ($balance_pushes === false) {
                                    $init = $this->wfp_balance;
                                    $balance_pushes = true;
                                }
                                $this->current_balance[] = [
                                    'category_group_id' => $allocation->category_group_id,
                                    'category_group' => $allocation->category_group,
                                    'initial_amount' => $init,
                                    'current_total' => $allocation->current_total,
                                    'balance' => $allocation->balance,
                                    'supplemental_quarter_id' => $allocation->fundDraft->fundAllocation->supplemental_quarter_id,
                                    'cost_center_id' => $allocation->fundDraft->fundAllocation->cost_center_id
                                ];
                            }
                        } else {
                            $initial = $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $wfpType
                            )->where(
                                'supplemental_quarter_id',
                                $this->supplementalQuarterId
                            )->first()->initial_amount;
                            $this->wfp_balance = $initial;
                            $this->current_balance = $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $wfpType
                            )->where(
                                'supplemental_quarter_id',
                                $this->supplementalQuarterId
                            )->first()->fundDrafts->first()->draft_amounts->map(function (
                                $allocation
                            ) {
                                return [
                                    'category_group_id' => $allocation->category_group_id,
                                    'category_group' => $allocation->category_group,
                                    'initial_amount' => $allocation->initial_amount,
                                    'current_total' => $allocation->current_total,
                                    'balance' => $allocation->balance,
                                ];
                            })->toArray();
                        }
                    } else {
                        $this->current_balance = $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $wfpType
                        )->where(
                            'is_supplemental',
                            0
                        )->first()->fundDrafts->first()->draft_amounts->map(function ($allocation) {
                            return [
                                'category_group_id' => $allocation->category_group_id,
                                'category_group' => $allocation->category_group,
                                'initial_amount' => $allocation->initial_amount,
                                'current_total' => $allocation->current_total,
                                'balance' => $allocation->balance,
                            ];
                        })->toArray();
                    }
                } else {

                    //balance 164
                    $programmed = [];
                    // if (count($this->record->wfp) > 0) {
                    foreach (
                        $this->record->wfp->filter(function ($wfp) {
                            return $wfp->is_supplemental === 0 || ($wfp->supplemental_quarter_id < $this->supplementalQuarterId && $wfp->supplemental_quarter_id !== null);
                        }) as $wfp
                    ) {
                        foreach ($wfp->wfpDetails as $allocation) {
                            if (!isset($programmed[$allocation->category_group_id])) {
                                $programmed[$allocation->category_group_id] = 0;
                            }
                            $programmed[$allocation->category_group_id] += ($allocation->total_quantity * $allocation->cost_per_unit);
                        }
                    }

                    $prev_allocations = [];

                    foreach (
                        $this->record->fundAllocations->filter(function ($allocation) {
                            return $allocation->is_supplemental === 0 || ($allocation->supplemental_quarter_id < $this->supplementalQuarterId && $allocation->supplemental_quarter_id !== null);
                        }) as $allocation
                    ) {
                        if (isset($prev_allocations[$allocation->category_group_id])) {
                            $prev_allocations[$allocation->category_group_id] += $allocation->initial_amount;
                        } else {
                            $prev_allocations[$allocation->category_group_id] = $allocation->initial_amount;
                        }
                    }

                    $initial = $this->record->fundAllocations->where(
                        'wpf_type_id',
                        $wfpType
                    )->first()->initial_amount;
                    $this->wfp_balance = $initial - array_sum($programmed);

                    $balance = array_sum($prev_allocations) - array_sum($programmed);
                    $this->current_balance = $this->record->fundAllocations->where(
                        'wpf_type_id',
                        $wfpType
                    )->where('supplemental_quarter_id', $this->supplementalQuarterId)->map(function (
                        $allocation
                    ) use ($balance) {
                        return [
                            'category_group_id' => $allocation->category_group_id,
                            'category_group' => "",
                            'initial_amount' => $allocation->initial_amount + $balance,
                            'current_total' => $allocation->current_total,
                            'balance' => $allocation->balance,
                        ];
                    })->toArray();
                    // } else {
                    //     $this->wfp_balance = $this->record->fundAllocations->where('supplemental_quarter_id', $this->supplementalQuarterId)->sum('initial_amount');
                    //     $this->current_balance = [];
                    // }
                }
            } else {

                // xdd
                $fundDrafts = $this->record->fundAllocations->where('wpf_type_id', $wfpType)->where(
                    'is_supplemental',
                    0
                )->first()->fundDrafts()->first()?->draft_amounts()->get();

                // FIX
                if (!is_null($fundDrafts) && count($fundDrafts) > 0) {
                    $draftItems = $this->record->fundAllocations->where('wpf_type_id', $wfpType)->where(
                    'is_supplemental',
                    0
                )->first()->fundDrafts()->first()?->draft_items()->get();

                 $this->categoryIds = $fundDrafts->pluck('category_group_id')->toArray();
                    if ($isSupplemental) {
                        $this->current_balance = $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $wfpType
                        )->where(
                            'supplemental_quarter_id',
                            $this->supplementalQuarterId
                        )->first()->fundDrafts->first()->draft_amounts->map(function (
                            $allocation
                        ) {
                            return [
                                'category_group_id' => $allocation->category_group_id,
                                'category_group' => $allocation->category_group,
                                'initial_amount' => $allocation->initial_amount,
                                'current_total' => $allocation->current_total,
                                'balance' => $allocation->balance,
                            ];
                        })->toArray();

                    } else {
                        if ($this->record->fundAllocations->where('wpf_type_id', $wfpType)->where(
                            'is_supplemental',
                            0
                        )->first() === null) {
                            $initial = $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $wfpType
                            )->where(
                                'supplemental_quarter_id',
                                $this->supplementalQuarterId
                            )->first()->initial_amount;
                        } else {
                            $initial = $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $wfpType
                            )->where('is_supplemental', 0)->first()->initial_amount;
                        }
                        $this->current_balance = $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $wfpType
                        )->where(
                            'is_supplemental',
                            0
                        )->first()->fundDrafts->first()->draft_amounts->map(function ($allocation) use (
                            $initial,
                            $draftItems
                        ) {
                            return [
                                'category_group_id' => $allocation->category_group_id,
                                'category_group' => $allocation->category_group,
                                'initial_amount' => $initial,
                                'current_total' => $draftItems->where('title_group', $allocation->category_group_id)->sum('estimated_budget'),
                                'balance' => $allocation->balance,
                            ];
                        })->toArray();

                    }
                } else {
                    $initial = $this->record->fundAllocations->where(
                        'wpf_type_id',
                        $wfpType
                    )->where('is_supplemental', 0)->first()->initial_amount;
                    $this->current_balance = $this->record->fundAllocations->where(
                        'wpf_type_id',
                        $wfpType
                    )->where('is_supplemental', 0)->map(function ($allocation) use ($initial) {
                        return [
                            'category_group_id' => $allocation->category_group_id,
                            'category_group' => $allocation->category_group,
                            'initial_amount' => $initial,
                            'current_total' => $allocation->current_total,
                            'balance' => $allocation->balance,
                        ];
                    })->toArray();
                }
            }
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

        $this->ps_particulars = Supply::whereHas('categoryItems', function ($query) {
            $query->where('budget_category_id', 6);
        })->get();
        $this->ps_total_quantity = 0;
        $this->ps_quantity = array_fill(0, 12, 0);


        //if has draft
        if ($isSupplemental) {
            if ($this->record->fundAllocations->where(
                'wpf_type_id',
                $this->wfp_param
            )->where(
                'supplemental_quarter_id',
                $this->supplementalQuarterId
            )->first()->fundDrafts()->exists()) {
                //1
                $this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->first()->fundDrafts->first()->draft_items->filter(function (
                    $item
                ) {
                    return in_array($item->title_group, $this->categoryIds) && $item->budget_category_id == 1;
                })->map(function ($item) {
                    $this->supplies[] = [
                        'budget_category_id' => $item->budget_category_id,
                        'budget_category' => 'Supplies & Semi-Expendables',
                        'particular_id' => $item->particular_id,
                        'particular' => $item->particular,
                        'supply_code' => $item->supply_code,
                        'specifications' => $item->specifications,
                        'uacs' => $item->uacs,
                        'title_group' => $item->title_group,
                        'account_title_id' => $item->account_title_id,
                        'account_title' => $item->account_title,
                        'ppmp' => $item->ppmp,
                        'cost_per_unit' => $item->cost_per_unit,
                        'quantity' => json_decode($item->quantity, true),
                        'total_quantity' => $item->total_quantity,
                        'uom' => $item->uom,
                        'estimated_budget' => $item->estimated_budget,
                        'remarks' => $item->remarks,
                    ];
                });
                //2
                $this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->first()->fundDrafts->first()->draft_items->filter(function (
                    $item
                ) {
                    return in_array($item->title_group, $this->categoryIds) && $item->budget_category_id == 2;
                })->map(function ($item) {
                    $this->mooe[] = [
                        'budget_category_id' => $item->budget_category_id,
                        'budget_category' => 'MOOE',
                        'particular_id' => $item->particular_id,
                        'particular' => $item->particular,
                        'supply_code' => $item->supply_code,
                        'specifications' => $item->specifications,
                        'uacs' => $item->uacs,
                        'title_group' => $item->title_group,
                        'account_title_id' => $item->account_title_id,
                        'account_title' => $item->account_title,
                        'ppmp' => $item->ppmp,
                        'cost_per_unit' => $item->cost_per_unit,
                        'quantity' => json_decode($item->quantity, true),
                        'total_quantity' => $item->total_quantity,
                        'uom' => $item->uom,
                        'estimated_budget' => $item->estimated_budget,
                        'remarks' => $item->remarks,
                    ];
                });
                //3
                $this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->first()->fundDrafts->first()->draft_items->filter(function (
                    $item
                ) {
                    return in_array($item->title_group, $this->categoryIds) && $item->budget_category_id == 3;
                })->map(function ($item) {
                    $this->trainings[] = [
                        'budget_category_id' => $item->budget_category_id,
                        'budget_category' => 'Training & Seminar',
                        'particular_id' => $item->particular_id,
                        'particular' => $item->particular,
                        'supply_code' => $item->supply_code,
                        'specifications' => $item->specifications,
                        'uacs' => $item->uacs,
                        'title_group' => $item->title_group,
                        'account_title_id' => $item->account_title_id,
                        'account_title' => $item->account_title,
                        'ppmp' => $item->ppmp,
                        'cost_per_unit' => $item->cost_per_unit,
                        'quantity' => json_decode($item->quantity, true),
                        'total_quantity' => $item->total_quantity,
                        'uom' => $item->uom,
                        'estimated_budget' => $item->estimated_budget,
                        'remarks' => $item->remarks,
                    ];
                });
                //4
                $this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->first()->fundDrafts->first()->draft_items->filter(function (
                    $item
                ) {
                    return in_array($item->title_group, $this->categoryIds) && $item->budget_category_id == 4;
                })->map(function ($item) {
                    $this->machines[] = [
                        'budget_category_id' => $item->budget_category_id,
                        'budget_category' => 'Machine & Equipment',
                        'particular_id' => $item->particular_id,
                        'particular' => $item->particular,
                        'supply_code' => $item->supply_code,
                        'specifications' => $item->specifications,
                        'uacs' => $item->uacs,
                        'title_group' => $item->title_group,
                        'account_title_id' => $item->account_title_id,
                        'account_title' => $item->account_title,
                        'ppmp' => $item->ppmp,
                        'cost_per_unit' => $item->cost_per_unit,
                        'quantity' => json_decode($item->quantity, true),
                        'total_quantity' => $item->total_quantity,
                        'uom' => $item->uom,
                        'estimated_budget' => $item->estimated_budget,
                        'remarks' => $item->remarks,
                    ];
                });
                //5
                $this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->first()->fundDrafts->first()->draft_items->filter(function (
                    $item
                ) {
                    return in_array($item->title_group, $this->categoryIds) && $item->budget_category_id == 5;
                })->map(function ($item) {
                    $this->buildings[] = [
                        'budget_category_id' => $item->budget_category_id,
                        'budget_category' => 'Building & Structure',
                        'particular_id' => $item->particular_id,
                        'particular' => $item->particular,
                        'supply_code' => $item->supply_code,
                        'specifications' => $item->specifications,
                        'uacs' => $item->uacs,
                        'title_group' => $item->title_group,
                        'account_title_id' => $item->account_title_id,
                        'account_title' => $item->account_title,
                        'ppmp' => $item->ppmp,
                        'cost_per_unit' => $item->cost_per_unit,
                        'quantity' => json_decode($item->quantity, true),
                        'total_quantity' => $item->total_quantity,
                        'uom' => $item->uom,
                        'estimated_budget' => $item->estimated_budget,
                        'remarks' => $item->remarks,
                    ];
                });
                //6
                $this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->first()->fundDrafts->first()->draft_items->filter(function (
                    $item
                ) {
                    return in_array($item->title_group, $this->categoryIds) && $item->budget_category_id == 6;
                })->map(function ($item) {
                    $this->ps[] = [
                        'budget_category_id' => $item->budget_category_id,
                        'budget_category' => 'PS',
                        'particular_id' => $item->particular_id,
                        'particular' => $item->particular,
                        'supply_code' => $item->supply_code,
                        'specifications' => $item->specifications,
                        'uacs' => $item->uacs,
                        'title_group' => $item->title_group,
                        'account_title_id' => $item->account_title_id,
                        'account_title' => $item->account_title,
                        'ppmp' => $item->ppmp,
                        'cost_per_unit' => $item->cost_per_unit,
                        'quantity' => json_decode($item->quantity, true),
                        'total_quantity' => $item->total_quantity,
                        'uom' => $item->uom,
                        'estimated_budget' => $item->estimated_budget,
                        'remarks' => $item->remarks,
                    ];
                });
            }
        } else {
            if ($this->record->fundAllocations->where(
                'wpf_type_id',
                $this->wfp_param
            )->first()->fundDrafts()->exists()) {
                //1
                $this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->first()->fundDrafts->first()->draft_items->filter(function ($item) {
                    return in_array($item->title_group, $this->categoryIds) && $item->budget_category_id == 1;
                })->map(function ($item) {
                    $this->supplies[] = [
                        'budget_category_id' => $item->budget_category_id,
                        'budget_category' => 'Supplies & Semi-Expendables',
                        'particular_id' => $item->particular_id,
                        'particular' => $item->particular,
                        'supply_code' => $item->supply_code,
                        'specifications' => $item->specifications,
                        'uacs' => $item->uacs,
                        'title_group' => $item->title_group,
                        'account_title_id' => $item->account_title_id,
                        'account_title' => $item->account_title,
                        'ppmp' => $item->ppmp,
                        'cost_per_unit' => $item->cost_per_unit,
                        'quantity' => json_decode($item->quantity, true),
                        'total_quantity' => $item->total_quantity,
                        'uom' => $item->uom,
                        'estimated_budget' => $item->estimated_budget,
                        'remarks' => $item->remarks,
                    ];
                });
                //2
                $this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->first()->fundDrafts->first()->draft_items->filter(function ($item) {
                    return in_array($item->title_group, $this->categoryIds) && $item->budget_category_id == 2;
                })->map(function ($item) {
                    $this->mooe[] = [
                        'budget_category_id' => $item->budget_category_id,
                        'budget_category' => 'MOOE',
                        'particular_id' => $item->particular_id,
                        'particular' => $item->particular,
                        'supply_code' => $item->supply_code,
                        'specifications' => $item->specifications,
                        'uacs' => $item->uacs,
                        'title_group' => $item->title_group,
                        'account_title_id' => $item->account_title_id,
                        'account_title' => $item->account_title,
                        'ppmp' => $item->ppmp,
                        'cost_per_unit' => $item->cost_per_unit,
                        'quantity' => json_decode($item->quantity, true),
                        'total_quantity' => $item->total_quantity,
                        'uom' => $item->uom,
                        'estimated_budget' => $item->estimated_budget,
                        'remarks' => $item->remarks,
                    ];
                });

                //3
                $this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->first()->fundDrafts->first()->draft_items->filter(function ($item) {
                    return in_array($item->title_group, $this->categoryIds) && $item->budget_category_id == 3;
                })->map(function ($item) {
                    $this->trainings[] = [
                        'budget_category_id' => $item->budget_category_id,
                        'budget_category' => 'Training & Seminar',
                        'particular_id' => $item->particular_id,
                        'particular' => $item->particular,
                        'supply_code' => $item->supply_code,
                        'specifications' => $item->specifications,
                        'uacs' => $item->uacs,
                        'title_group' => $item->title_group,
                        'account_title_id' => $item->account_title_id,
                        'account_title' => $item->account_title,
                        'ppmp' => $item->ppmp,
                        'cost_per_unit' => $item->cost_per_unit,
                        'quantity' => json_decode($item->quantity, true),
                        'total_quantity' => $item->total_quantity,
                        'uom' => $item->uom,
                        'estimated_budget' => $item->estimated_budget,
                        'remarks' => $item->remarks,
                    ];
                });

                //4
                $this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->first()->fundDrafts->first()->draft_items->filter(function ($item) {
                    return in_array($item->title_group, $this->categoryIds) && $item->budget_category_id == 4;
                })->map(function ($item) {
                    $this->machines[] = [
                        'budget_category_id' => $item->budget_category_id,
                        'budget_category' => 'Machine & Equipment',
                        'particular_id' => $item->particular_id,
                        'particular' => $item->particular,
                        'supply_code' => $item->supply_code,
                        'specifications' => $item->specifications,
                        'uacs' => $item->uacs,
                        'title_group' => $item->title_group,
                        'account_title_id' => $item->account_title_id,
                        'account_title' => $item->account_title,
                        'ppmp' => $item->ppmp,
                        'cost_per_unit' => $item->cost_per_unit,
                        'quantity' => json_decode($item->quantity, true),
                        'total_quantity' => $item->total_quantity,
                        'uom' => $item->uom,
                        'estimated_budget' => $item->estimated_budget,
                        'remarks' => $item->remarks,
                    ];
                });
                //5
                $this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->first()->fundDrafts->first()->draft_items->filter(function ($item) {
                    return in_array($item->title_group, $this->categoryIds) && $item->budget_category_id == 5;
                })->map(function ($item) {
                    $this->buildings[] = [
                        'budget_category_id' => $item->budget_category_id,
                        'budget_category' => 'Building & Structure',
                        'particular_id' => $item->particular_id,
                        'particular' => $item->particular,
                        'supply_code' => $item->supply_code,
                        'specifications' => $item->specifications,
                        'uacs' => $item->uacs,
                        'title_group' => $item->title_group,
                        'account_title_id' => $item->account_title_id,
                        'account_title' => $item->account_title,
                        'ppmp' => $item->ppmp,
                        'cost_per_unit' => $item->cost_per_unit,
                        'quantity' => json_decode($item->quantity, true),
                        'total_quantity' => $item->total_quantity,
                        'uom' => $item->uom,
                        'estimated_budget' => $item->estimated_budget,
                        'remarks' => $item->remarks,
                    ];
                });
                //6
                $this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->first()->fundDrafts->first()->draft_items->filter(function ($item) {
                    return in_array($item->title_group, $this->categoryIds) && $item->budget_category_id == 6;
                })->map(function ($item) {
                    $this->ps[] = [
                        'budget_category_id' => $item->budget_category_id,
                        'budget_category' => 'PS',
                        'particular_id' => $item->particular_id,
                        'particular' => $item->particular,
                        'supply_code' => $item->supply_code,
                        'specifications' => $item->specifications,
                        'uacs' => $item->uacs,
                        'title_group' => $item->title_group,
                        'account_title_id' => $item->account_title_id,
                        'account_title' => $item->account_title,
                        'ppmp' => $item->ppmp,
                        'cost_per_unit' => $item->cost_per_unit,
                        'quantity' => json_decode($item->quantity, true),
                        'total_quantity' => $item->total_quantity,
                        'uom' => $item->uom,
                        'estimated_budget' => $item->estimated_budget,
                        'remarks' => $item->remarks,
                    ];
                });
            }
        }

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
                ->getSearchResultsUsing(function (string $search) {
                    return Supply::whereHas('categoryItems', function ($query) {
                        $query->where('budget_category_id', $this->budgetCategoryTabIds[$this->global_index]);
                    })->where('is_active', operator: 1)->where('particulars', 'like', "%{$search}%")
                        ->when(!is_null($search), function ($query) use ($search) {
                            $query->where('specifications', 'like', "%{$search}%")
                            ->orWhere('particulars', 'like', "%{$search}%");
                        })
                        ->limit(50)->pluck('particulars', 'id');
                    // switch($this->global_index)
                    // {
                    //     case 2:
                    //         return Supply::whereHas('categoryItems', function ($query) {
                    //             $query->where('budget_category_id', 1);
                    //         })->where('is_active', 1)->where('particulars', 'like', "%{$search}%")
                    //           ->orWhere('specifications', 'like', "%{$search}%")
                    //           ->limit(50)->pluck('particulars', 'id');
                    //         break;
                    //     case 3:
                    //         return Supply::whereHas('categoryItems', function ($query) {
                    //             $query->where('budget_category_id', 2);
                    //         })->where('is_active', 1)->where('particulars', 'like', "%{$search}%")
                    //         ->orWhere('specifications', 'like', "%{$search}%")
                    //         ->limit(50)->pluck('particulars', 'id');
                    //         break;
                    //     case 4:
                    //         return Supply::whereHas('categoryItems', function ($query) {
                    //             $query->where('budget_category_id', 3);
                    //         })->where('is_active', 1)->where('particulars', 'like', "%{$search}%")
                    //         ->orWhere('specifications', 'like', "%{$search}%")
                    //         ->limit(50)->pluck('particulars', 'id');
                    //         break;
                    //     case 5:
                    //         return Supply::whereHas('categoryItems', function ($query) {
                    //             $query->where('budget_category_id', 4);
                    //         })->where('is_active', 1)->where('particulars', 'like', "%{$search}%")
                    //         ->orWhere('specifications', 'like', "%{$search}%")
                    //         ->limit(50)->pluck('particulars', 'id');
                    //         break;
                    //     case 6:
                    //         return Supply::whereHas('categoryItems', function ($query) {
                    //             $query->where('budget_category_id', 5);
                    //         })->where('is_active', 1)->where('particulars', 'like', "%{$search}%")
                    //         ->orWhere('specifications', 'like', "%{$search}%")
                    //         ->limit(50)->pluck('particulars', 'id');
                    //         break;
                    //     case 7:
                    //         return Supply::whereHas('categoryItems', function ($query) {
                    //             $query->where('budget_category_id', 6);
                    //         })->where('is_active', 1)->where('particulars', 'like', "%{$search}%")
                    //         ->orWhere('specifications', 'like', "%{$search}%")
                    //         ->limit(50)->pluck('particulars', 'id');
                    //         break;
                    //     default:
                    //         return Supply::where('is_active', 1)->where('particulars', 'like', "%{$search}%")
                    //         ->orWhere('specifications', 'like', "%{$search}%")
                    //         ->limit(50)->pluck('particulars', 'id');
                    //         break;
                    // }
                })
                ->reactive()
                ->afterStateUpdated(function () {
                    switch ($this->global_index) {
                        case 2:
                            if ($this->supplies_particular != null) {
                                $this->supplies_particular_id = $this->supplies_particular;
                                $this->supplies_category_attr = Supply::find($this->supplies_particular);
                                $this->supplies_specs = $this->supplies_category_attr->specifications;
                                $this->supplies_code = $this->supplies_category_attr->supply_code;
                                $this->supplies_uacs = $this->supplies_category_attr->categoryItems->uacs_code;
                                $this->supplies_title_group = $this->supplies_category_attr->categoryGroups->name;
                                $this->supplies_account_title = $this->supplies_category_attr->categoryItems->name;
                                $this->supplies_ppmp = $this->supplies_category_attr->is_ppmp;
                                $this->supplies_cost_per_unit = $this->supplies_category_attr->unit_cost;
                                $this->supplies_uom = $this->supplies_category_attr->uom;
                                $this->supplies_quantity = array_fill(0, 12, 0);
                                $this->calculateSuppliesTotalQuantity();
                            } else {
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
                            if ($this->supplies_particular != null) {
                                $this->mooe_particular_id = $this->supplies_particular;
                                $this->mooe_category_attr = Supply::find($this->supplies_particular);
                                $this->mooe_specs = $this->mooe_category_attr->specifications;
                                $this->mooe_code = $this->mooe_category_attr->supply_code;
                                $this->mooe_uacs = $this->mooe_category_attr->categoryItems->uacs_code;
                                $this->mooe_title_group = $this->mooe_category_attr->categoryGroups->name;
                                $this->mooe_account_title = $this->mooe_category_attr->categoryItems->name;
                                $this->mooe_ppmp = $this->mooe_category_attr->is_ppmp;
                                $this->mooe_cost_per_unit = $this->mooe_category_attr->unit_cost;
                                $this->mooe_uom = $this->mooe_category_attr->uom;
                                $this->mooe_quantity = array_fill(0, 12, 0);
                                $this->calculateMooeTotalQuantity();
                            } else {
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
                            if ($this->supplies_particular != null) {
                                $this->training_particular_id = $this->supplies_particular;
                                $this->training_category_attr = Supply::find($this->supplies_particular);
                                $this->training_specs = $this->training_category_attr->specifications;
                                $this->training_code = $this->training_category_attr->supply_code;
                                $this->training_uacs = $this->training_category_attr->categoryItems->uacs_code;
                                $this->training_title_group = $this->training_category_attr->categoryGroups->name;
                                $this->training_account_title = $this->training_category_attr->categoryItems->name;
                                $this->training_ppmp = $this->training_category_attr->is_ppmp;
                                $this->training_cost_per_unit = $this->training_category_attr->unit_cost;
                                $this->training_uom = $this->training_category_attr->uom;
                                $this->training_quantity = array_fill(0, 12, 0);
                                $this->calculateTrainingTotalQuantity();
                            } else {
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
                            if ($this->supplies_particular != null) {
                                $this->machine_particular_id = $this->supplies_particular;
                                $this->machine_category_attr = Supply::find($this->supplies_particular);
                                $this->machine_specs = $this->machine_category_attr->specifications;
                                $this->machine_code = $this->machine_category_attr->supply_code;
                                $this->machine_uacs = $this->machine_category_attr->categoryItems->uacs_code;
                                $this->machine_title_group = $this->machine_category_attr->categoryGroups->name;
                                $this->machine_account_title = $this->machine_category_attr->categoryItems->name;
                                $this->machine_ppmp = $this->machine_category_attr->is_ppmp;
                                $this->machine_cost_per_unit = $this->machine_category_attr->unit_cost;
                                $this->machine_uom = $this->machine_category_attr->uom;
                                $this->machine_quantity = array_fill(0, 12, 0);
                                $this->calculateMachineTotalQuantity();
                            } else {
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
                            if ($this->supplies_particular != null) {
                                $this->building_particular_id = $this->supplies_particular;
                                $this->building_category_attr = Supply::find($this->supplies_particular);
                                $this->building_specs = $this->building_category_attr->specifications;
                                $this->building_code = $this->building_category_attr->supply_code;
                                $this->building_uacs = $this->building_category_attr->categoryItems->uacs_code;
                                $this->building_title_group = $this->building_category_attr->categoryGroups->name;
                                $this->building_account_title = $this->building_category_attr->categoryItems->name;
                                $this->building_ppmp = $this->building_category_attr->is_ppmp;
                                $this->building_cost_per_unit = $this->building_category_attr->unit_cost;
                                $this->building_uom = $this->building_category_attr->uom;
                                $this->building_quantity = array_fill(0, 12, 0);
                                $this->calculateBuildingTotalQuantity();
                            } else {
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
                        case 7:
                            if ($this->supplies_particular != null) {
                                $this->ps_particular_id = $this->supplies_particular;
                                $this->ps_category_attr = Supply::find($this->supplies_particular);
                                $this->ps_specs = $this->ps_category_attr->specifications;
                                $this->ps_code = $this->ps_category_attr->supply_code;
                                $this->ps_uacs = $this->ps_category_attr->categoryItems->uacs_code;
                                $this->ps_title_group = $this->ps_category_attr->categoryGroups->name;
                                $this->ps_account_title = $this->ps_category_attr->categoryItems->name;
                                $this->ps_ppmp = $this->ps_category_attr->is_ppmp;
                                $this->ps_cost_per_unit = $this->ps_category_attr->unit_cost;
                                $this->ps_uom = $this->ps_category_attr->uom;
                                $this->ps_quantity = array_fill(0, 12, 0);
                                $this->calculatePsTotalQuantity();
                            } else {
                                $this->ps_category_attr = null;
                                $this->ps_specs = null;
                                $this->ps_code = null;
                                $this->ps_uacs = null;
                                $this->ps_title_group = null;
                                $this->ps_account_title = null;
                                $this->ps_ppmp = false;
                                $this->ps_cost_per_unit = 0;
                                $this->ps_total_quantity = 0;
                                $this->ps_estimated_budget = 0;
                                $this->ps_uom = null;
                                $this->ps_quantity = array_fill(0, 12, 0);
                            }
                            break;
                    }
                })
        ];
    }

    public function updatedSourceFund()
    {
        if ($this->source_fund == 6) {
            $this->confirm_fund_source = null;
        }
    }

    public function updatedSuppliesParticularId()
    {
        if ($this->supplies_particular_id != null) {
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
        } else {
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
        $supply = $this->supplies_category_attr;
        $budget_category_id = BudgetCategory::where(
            'id',
            $supply->categoryItems()->first()->budget_category_id
        )->first()->id;
        switch ($budget_category_id) {
            case 1:
                $this->calculateSuppliesTotalQuantity();
                break;
            case 2:
                $this->calculateMooeTotalQuantity();
                $cost_per_unit = $this->mooe_cost_per_unit == null ? 0 : $this->mooe_cost_per_unit;
                $this->supplies_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->mooe_quantity ?? [0]));
                $this->supplies_estimated_budget = number_format(
                    $this->supplies_total_quantity * $cost_per_unit,
                    2
                );
                break;
            case 3:
                $this->calculateTrainingTotalQuantity();
                $cost_per_unit = $this->training_cost_per_unit == null ? 0 : $this->training_cost_per_unit;
                $this->supplies_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->training_quantity ?? [0]));
                $this->supplies_estimated_budget = number_format(
                    $this->supplies_total_quantity * $cost_per_unit,
                    2
                );
                break;
            case 4:
                $this->calculateMachineTotalQuantity();
                $cost_per_unit = $this->machine_cost_per_unit == null ? 0 : $this->machine_cost_per_unit;
                $this->supplies_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->machine_quantity ?? [0]));
                $this->supplies_estimated_budget = number_format(
                    $this->supplies_total_quantity * $cost_per_unit,
                    2
                );
                break;
            case 5:
                $this->calculateBuildingTotalQuantity();
                $cost_per_unit = $this->building_cost_per_unit == null ? 0 : $this->building_cost_per_unit;
                $this->supplies_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->building_quantity ?? [0]));
                $this->supplies_estimated_budget = number_format(
                    $this->supplies_total_quantity * $cost_per_unit,
                    2
                );
                break;
            case 6:
                $this->calculatePsTotalQuantity();
                $cost_per_unit = $this->ps_cost_per_unit == null ? 0 : $this->ps_cost_per_unit;
                $this->supplies_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->ps_quantity ?? [0]));
                $this->supplies_estimated_budget = number_format(
                    $this->supplies_total_quantity * $cost_per_unit,
                    2
                );
                break;
        }
    }

    public function updatedSuppliesCostPerUnit()
    {
        $supply = $this->supplies_category_attr;
        $budget_category_id = BudgetCategory::where(
            'id',
            $supply->categoryItems()->first()->budget_category_id
        )->first()->id;
        switch ($budget_category_id) {
            case 1:
                $this->calculateSuppliesTotalQuantity();
                break;
            case 2:
                $this->calculateMooeTotalQuantity();
                $cost_per_unit = $this->mooe_cost_per_unit == null ? 0 : $this->mooe_cost_per_unit;
                $this->supplies_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->mooe_quantity ?? [0]));
                $this->supplies_estimated_budget = number_format(
                    $this->supplies_total_quantity * $cost_per_unit,
                    2
                );
                break;
            case 3:
                $this->calculateTrainingTotalQuantity();
                $cost_per_unit = $this->training_cost_per_unit == null ? 0 : $this->training_cost_per_unit;
                $this->supplies_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->training_quantity ?? [0]));
                $this->supplies_estimated_budget = number_format(
                    $this->supplies_total_quantity * $cost_per_unit,
                    2
                );
                break;
            case 4:
                $this->calculateMachineTotalQuantity();
                $cost_per_unit = $this->machine_cost_per_unit == null ? 0 : $this->machine_cost_per_unit;
                $this->supplies_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->machine_quantity ?? [0]));
                $this->supplies_estimated_budget = number_format(
                    $this->supplies_total_quantity * $cost_per_unit,
                    2
                );
                break;
            case 5:
                $this->calculateBuildingTotalQuantity();
                $cost_per_unit = $this->building_cost_per_unit == null ? 0 : $this->building_cost_per_unit;
                $this->supplies_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->building_quantity ?? [0]));
                $this->supplies_estimated_budget = number_format(
                    $this->supplies_total_quantity * $cost_per_unit,
                    2
                );
                break;
            case 6:
                $this->calculatePsTotalQuantity();
                $cost_per_unit = $this->ps_cost_per_unit == null ? 0 : $this->ps_cost_per_unit;
                $this->supplies_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->ps_quantity ?? [0]));
                $this->supplies_estimated_budget = number_format(
                    $this->supplies_total_quantity * $cost_per_unit,
                    2
                );
                break;
        }
    }

    public function calculateSuppliesTotalQuantity()
    {
        $cost_per_unit = $this->supplies_cost_per_unit == null ? 0 : $this->supplies_cost_per_unit;
        $this->supplies_total_quantity = array_sum(array_map(function ($quantity) {
            return is_numeric($quantity) ? (int) $quantity : 0;
        }, $this->supplies_quantity ?? [0]));
        $this->supplies_estimated_budget = number_format($this->supplies_total_quantity * $cost_per_unit, 2);
    }

    public function updatedSuppliesIsRemarks()
    {
        if ($this->supplies_is_remarks === false) {
            $this->supplies_remarks = null;
        }
    }

    public function addSupplies()
    {
        //validate all step 2

        $this->supplies_particular_id = $this->supplies_particular;
        $this->supplies_category_attr = Supply::find($this->supplies_particular);
        $this->supplies_specs = $this->supplies_category_attr->specifications;
        $this->supplies_code = $this->supplies_category_attr->supply_code;
        $this->supplies_uacs = $this->supplies_category_attr->categoryItems->uacs_code;
        $this->supplies_title_group = $this->supplies_category_attr->categoryGroups->name;
        $this->supplies_account_title = $this->supplies_category_attr->categoryItems->name;
        $this->supplies_ppmp = $this->supplies_category_attr->is_ppmp;
        $this->supplies_cost_per_unit = $this->supplies_category_attr->unit_cost;
        $this->supplies_uom = $this->supplies_category_attr->uom;
        $this->supplies_quantity = array_fill(0, 12, 0);


        try {
            $this->validate(
                [
                    'supplies_particular_id' => 'required',
                    'supplies_uom' => 'required',
                    // 'supplies_cost_per_unit' => 'required|gt:0',
                    'supplies_total_quantity' => 'gt:0',
                ],
                [
                    'supplies_particular_id.required' => 'Particulars is required',
                    'supplies_uom.required' => 'UOM is required',
                    'supplies_cost_per_unit.required' => 'Cost per unit is required',
                    // 'supplies_cost_per_unit.gt' => 'Cost per unit must be greater than 0',
                    'supplies_total_quantity.gt' => 'Total quantity must be greater than 0',
                ]
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            dd($e->errors(), $this->supplies_particular_id);
        }


        if ($this->is_supplemental) {
            $is_valid_category_group = $this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)
                ->where('supplemental_quarter_id', $this->supplementalQuarterId)
                ->pluck('category_group_id')
                ->contains($this->supplies_category_attr->categoryGroups->id);

            if ($is_valid_category_group) {
                $fund_allocation = $this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)
                    ->where('supplemental_quarter_id', $this->supplementalQuarterId)
                    ->where('category_group_id', $this->supplies_category_attr->categoryGroups->id)
                    ->first();

                if ($fund_allocation && $fund_allocation->initial_amount == 0) {
                    $this->dialog()->error(
                        $title = 'Operation Failed',
                        $description = 'You don\'t have fund allocation for this title group.',
                    );
                    return;
                }
            }

            $intEstimatedBudget = (float) str_replace(',', '', $this->supplies_estimated_budget);
            if ($this->record->fundAllocations->where(
                'wpf_type_id',
                $this->wfp_param
            )->where(
                'supplemental_quarter_id',
                $this->supplementalQuarterId
            )->first()->fundDrafts()->exists()) {
                $draft_id = $this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->first()->fundDrafts()->first()->id;
            }
            if ($this->supplies != null) {
                foreach ($this->supplies as $key => $supply) {
                    if ($supply['particular_id'] == $this->supplies_particular_id && $supply['uom'] == $this->supplies_uom && $supply['remarks'] == $this->supplies_remarks) {
                        $this->supplies[$key]['quantity'] = $this->supplies[$key]['quantity'] += $this->supplies_quantity;
                        $this->supplies[$key]['total_quantity'] = $this->supplies[$key]['total_quantity'] += $this->supplies_total_quantity;
                        $this->supplies[$key]['estimated_budget'] = $this->supplies[$key]['estimated_budget'] += $intEstimatedBudget;
                        $this->supplies[$key]['quantity'] = array_map(function ($a, $b) {
                            return $a + $b;
                        }, $this->supplies[$key]['quantity'], $this->supplies_quantity);

                        $draft_items = $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->where(
                            'supplemental_quarter_id',
                            $this->supplementalQuarterId
                        )->first()->fundDrafts->first()->draft_items->where(
                            'particular_id',
                            $this->supplies_particular_id
                        )->where('uom', $this->supplies_uom)->where(
                            'remarks',
                            $this->supplies_remarks
                        )->first();
                        $draft_items->quantity = json_encode($this->supplies[$key]['quantity']);
                        $draft_items->total_quantity = $this->supplies[$key]['total_quantity'];
                        $draft_items->estimated_budget = $this->supplies[$key]['estimated_budget'];
                        $draft_items->save();

                        break;
                    } else {
                        $existingDraftItem = $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->where(
                            'supplemental_quarter_id',
                            $this->supplementalQuarterId
                        )->first()->fundDrafts->first()->draft_items->where(
                            'particular_id',
                            $this->supplies_particular_id
                        )->where('uom', $this->supplies_uom)->where(
                            'remarks',
                            $this->supplies_remarks
                        )->first();
                        if (!$existingDraftItem) {

                            $this->supplies[] = [
                                'budget_category_id' => $this->supplies_category_attr->categoryItems->budget_category_id,
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

                            FundDraftItem::create(
                                [
                                    'fund_draft_id' => $this->record->fundAllocations->where(
                                        'wpf_type_id',
                                        $this->wfp_param
                                    )->where(
                                        'supplemental_quarter_id',
                                        $this->supplementalQuarterId
                                    )->first()->fundDrafts->first()->id,
                                    'budget_category_id' => $this->supplies_category_attr->categoryItems->budget_category_id,
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
                                    'quantity' => json_encode($this->supplies_quantity),
                                    'total_quantity' => $this->supplies_total_quantity,
                                    'uom' => $this->supplies_uom,
                                    'estimated_budget' => $intEstimatedBudget,
                                    'remarks' => $this->supplies_remarks,
                                ]
                            );
                            break;
                        }
                    }
                }
            } else {
                $this->supplies[] = [
                    'budget_category_id' => $this->supplies_category_attr->categoryItems->budget_category_id,
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


                if ($this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->first()->fundDrafts()->exists()) {
                    $draft_items = FundDraftItem::create(
                        [
                            'fund_draft_id' => $draft_id,
                            'budget_category_id' => $this->supplies_category_attr->categoryItems->budget_category_id,
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
                            'quantity' => json_encode($this->supplies_quantity),
                            'total_quantity' => $this->supplies_total_quantity,
                            'uom' => $this->supplies_uom,
                            'estimated_budget' => $intEstimatedBudget,
                            'remarks' => $this->supplies_remarks,
                        ]
                    );
                } else {
                    $draft = FundDraft::create([
                        'fund_allocation_id' => $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->where(
                            'supplemental_quarter_id',
                            $this->supplementalQuarterId
                        )->first()->id,
                    ]);

                    $draft_items = FundDraftItem::create(
                        [
                            'fund_draft_id' => $draft->id,
                            'budget_category_id' => $this->supplies_category_attr->categoryItems->budget_category_id,
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
                            'quantity' => json_encode($this->supplies_quantity),
                            'total_quantity' => $this->supplies_total_quantity,
                            'uom' => $this->supplies_uom,
                            'estimated_budget' => $intEstimatedBudget,
                            'remarks' => $this->supplies_remarks,
                        ]
                    );
                }
            }

            if ($this->wfp_fund->id === 2 || $this->wfp_fund->id === 4 || $this->wfp_fund->id === 5 || $this->wfp_fund->id === 6 || $this->wfp_fund->id === 7) {
                $categoryGroupId = $this->supplies_category_attr->categoryGroups->id;
                $found = false;
                foreach ($this->current_balance as $key => $balance) {
                    if ($balance && $balance['category_group_id'] == $categoryGroupId) {
                        // $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                        $found = true;
                        //if(!$this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)->first()->fundDrafts()->exists())
                        //{
                        if ($this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->where(
                            'supplemental_quarter_id',
                            $this->supplementalQuarterId
                        )->first()->fundDrafts()->exists()) {

                            $draft_id = $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $this->wfp_param
                            )->where(
                                'supplemental_quarter_id',
                                $this->supplementalQuarterId
                            )->first()->fundDrafts()->first()->id;
                            $draft_amounts = FundDraftAmount::where(
                                'fund_draft_id',
                                $draft_id
                            )->where('category_group_id', $categoryGroupId)->first();
                            $draft_amounts->current_total = $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                            $draft_amounts->balance = $this->current_balance[$key]['balance'];
                            $draft_amounts->save();
                        } else {
                            $draft_amounts = FundDraftAmount::where('category_group_id', $categoryGroupId)->first();
                            $draft_amounts->current_total = $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                            $draft_amounts->balance = $this->current_balance[$key]['balance'];
                            $draft_amounts->save();
                        }
                        //}

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

                    // if(!$this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)->first()->fundDrafts()->first()->draft_amounts()->exists())
                    // {
                    FundDraftAmount::create([
                        'fund_draft_id' => $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->where(
                            'supplemental_quarter_id',
                            $this->supplementalQuarterId
                        )->first()->fundDrafts->first()->id,
                        'category_group_id' => $categoryGroupId,
                        'category_group' => $this->supplies_category_attr->categoryGroups->name,
                        'initial_amount' => 0,
                        'current_total' => $intEstimatedBudget,
                        'balance' => $intEstimatedBudget,
                    ]);
                    // }
                }
            } else {
                //add current_total to current balance from estimated budget
                foreach ($this->current_balance as $key => $balance) {
                    if ($balance && $balance['category_group_id'] == $this->supplies_category_attr->categoryGroups->id) {
                        $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                        $this->current_balance[$key]['balance'] -= $intEstimatedBudget;
                    }
                }


                if (!$this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->first()->fundDrafts()->first()->draft_amounts()->exists()) {
                    foreach ($this->current_balance as $item) {
                        if ($item) {
                            $draft_amounts = FundDraftAmount::create([
                                'fund_draft_id' => $this->record->fundAllocations->where(
                                    'wpf_type_id',
                                    $this->wfp_param
                                )->where(
                                    'supplemental_quarter_id',
                                    $this->supplementalQuarterId
                                )->first()->fundDrafts->first()->id,
                                'category_group_id' => $item['category_group_id'],
                                'category_group' => $item['category_group'],
                                'initial_amount' => $item['initial_amount'],
                                'current_total' => $item['current_total'],
                                'balance' => $item['initial_amount'],
                            ]);
                        }
                    }
                } else {
                    foreach ($this->current_balance as $item) {
                        if ($item) {
                            if ($this->record->fundAllocations->where(
                                'wpf_type_id',
                                $this->wfp_param
                            )->where(
                                'supplemental_quarter_id',
                                $this->supplementalQuarterId
                            )->first()->fundDrafts->first()->draft_amounts()->exists()) {

                                $draft_amounts = FundDraftAmount::create([
                                    'fund_draft_id' => $this->record->fundAllocations->where(
                                        'wpf_type_id',
                                        $this->wfp_param
                                    )->where(
                                        'supplemental_quarter_id',
                                        $this->supplementalQuarterId
                                    )->first()->fundDrafts->first()->id,
                                    'category_group_id' => $item['category_group_id'],
                                    'category_group' => $item['category_group'],
                                    'initial_amount' => $item['initial_amount'],
                                    'current_total' => $item['current_total'],
                                    'balance' => $item['initial_amount'],
                                ]);
                            } else {

                                $draft_amounts = $this->record->fundAllocations->where(
                                    'wpf_type_id',
                                    $this->wfp_param
                                )->where(
                                    'supplemental_quarter_id',
                                    $this->supplementalQuarterId
                                )->first()->fundDrafts->first()->draft_amounts->where(
                                    'category_group_id',
                                    $item['category_group_id']
                                )->first();
                                $draft_amounts->current_total = $item['current_total'] ?? 0;
                                $draft_amounts->balance = $item['balance'] ?? 0;
                                $draft_amounts->save();
                            }
                        }
                    }
                }
            }
        } else {
            $is_valid_category_group = $this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)
                ->pluck('category_group_id')
                ->contains($this->supplies_category_attr->categoryGroups->id);

            if ($is_valid_category_group) {
                $fund_allocation = $this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)
                    ->where('category_group_id', $this->supplies_category_attr->categoryGroups->id)
                    ->first();

                if ($fund_allocation && $fund_allocation->initial_amount == 0) {
                    $this->dialog()->error(
                        $title = 'Operation Failed',
                        $description = 'You don\'t have fund allocation for this title group.',
                    );
                    return;
                }
            }

            $intEstimatedBudget = (float) str_replace(',', '', $this->supplies_estimated_budget);
            if ($this->record->fundAllocations->where(
                'wpf_type_id',
                $this->wfp_param
            )->first()->fundDrafts()->exists()) {
                $draft_id = $this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->first()->fundDrafts()->first()->id;
            }
            if ($this->supplies != null) {
                foreach ($this->supplies as $key => $supply) {
                    if ($supply['particular_id'] == $this->supplies_particular_id && $supply['uom'] == $this->supplies_uom && $supply['remarks'] == $this->supplies_remarks) {
                        $this->supplies[$key]['quantity'] = $this->supplies[$key]['quantity'] += $this->supplies_quantity;
                        $this->supplies[$key]['total_quantity'] = $this->supplies[$key]['total_quantity'] += $this->supplies_total_quantity;
                        $this->supplies[$key]['estimated_budget'] = $this->supplies[$key]['estimated_budget'] += $intEstimatedBudget;
                        $this->supplies[$key]['quantity'] = array_map(function ($a, $b) {
                            return $a + $b;
                        }, $this->supplies[$key]['quantity'], $this->supplies_quantity);

                        $draft_items = $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->first()->fundDrafts->first()->draft_items->where(
                            'particular_id',
                            $this->supplies_particular_id
                        )->where('uom', $this->supplies_uom)->where(
                            'remarks',
                            $this->supplies_remarks
                        )->first();
                        $draft_items->quantity = json_encode($this->supplies[$key]['quantity']);
                        $draft_items->total_quantity = $this->supplies[$key]['total_quantity'];
                        $draft_items->estimated_budget = $this->supplies[$key]['estimated_budget'];
                        $draft_items->save();

                        break;
                    } else {
                        $existingDraftItem = $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->first()->fundDrafts->first()->draft_items->where(
                            'particular_id',
                            $this->supplies_particular_id
                        )->where('uom', $this->supplies_uom)->where(
                            'remarks',
                            $this->supplies_remarks
                        )->first();
                        if (!$existingDraftItem) {

                            $this->supplies[] = [
                                'budget_category_id' => $this->supplies_category_attr->categoryItems->budget_category_id,
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

                            FundDraftItem::create(
                                [
                                    'fund_draft_id' => $this->record->fundAllocations->where(
                                        'wpf_type_id',
                                        $this->wfp_param
                                    )->first()->fundDrafts->first()->id,
                                    'budget_category_id' => $this->supplies_category_attr->categoryItems->budget_category_id,
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
                                    'quantity' => json_encode($this->supplies_quantity),
                                    'total_quantity' => $this->supplies_total_quantity,
                                    'uom' => $this->supplies_uom,
                                    'estimated_budget' => $intEstimatedBudget,
                                    'remarks' => $this->supplies_remarks,
                                ]
                            );
                            break;
                        }
                    }
                }
            } else {
                $this->supplies[] = [
                    'budget_category_id' => $this->supplies_category_attr->categoryItems->budget_category_id,
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


                if ($this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->first()->fundDrafts()->exists()) {
                    $draft_items = FundDraftItem::create(
                        [
                            'fund_draft_id' => $draft_id,
                            'budget_category_id' => $this->supplies_category_attr->categoryItems->budget_category_id,
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
                            'quantity' => json_encode($this->supplies_quantity),
                            'total_quantity' => $this->supplies_total_quantity,
                            'uom' => $this->supplies_uom,
                            'estimated_budget' => $intEstimatedBudget,
                            'remarks' => $this->supplies_remarks,
                        ]
                    );
                } else {
                    $draft = FundDraft::create([
                        'fund_allocation_id' => $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->first()->id,
                    ]);

                    $draft_items = FundDraftItem::create(
                        [
                            'fund_draft_id' => $draft->id,
                            'budget_category_id' => $this->supplies_category_attr->categoryItems->budget_category_id,
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
                            'quantity' => json_encode($this->supplies_quantity),
                            'total_quantity' => $this->supplies_total_quantity,
                            'uom' => $this->supplies_uom,
                            'estimated_budget' => $intEstimatedBudget,
                            'remarks' => $this->supplies_remarks,
                        ]
                    );
                }
            }

            if ($this->wfp_fund->id === 2 || $this->wfp_fund->id === 4 || $this->wfp_fund->id === 5 || $this->wfp_fund->id === 6 || $this->wfp_fund->id === 7) {
                $categoryGroupId = $this->supplies_category_attr->categoryGroups->id;
                $found = false;

                foreach ($this->current_balance as $key => $balance) {
                    if ($balance && $balance['category_group_id'] == $categoryGroupId) {
                        // $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                        $found = true;
                        // if(!$this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)->first()->fundDrafts()->exists())
                        // {
                        $draft_amounts = FundDraftAmount::where('category_group_id', $categoryGroupId)->first();

                        $draft_amounts->current_total = $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                        $draft_amounts->balance = $this->current_balance[$key]['balance'];
                        $draft_amounts->save();
                        // }

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
                    // if(!$this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)->first()->fundDrafts()->first()->draft_amounts()->exists())
                    // {
                    FundDraftAmount::create([
                        'fund_draft_id' => $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->first()->fundDrafts->first()->id,
                        'category_group_id' => $categoryGroupId,
                        'category_group' => $this->supplies_category_attr->categoryGroups->name,
                        'initial_amount' => 0,
                        'current_total' => $intEstimatedBudget,
                        'balance' => $intEstimatedBudget,
                    ]);
                    // }
                }
            } else {
                //add current_total to current balance from estimated budget
                foreach ($this->current_balance as $key => $balance) {
                    if ($balance && $balance['category_group_id'] == $this->supplies_category_attr->categoryGroups->id) {
                        $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                        $this->current_balance[$key]['balance'] -= $intEstimatedBudget;
                    }
                }


                if (!$this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->first()->fundDrafts()->first()->draft_amounts()->exists()) {
                    foreach ($this->current_balance as $item) {
                        $draft_amounts = FundDraftAmount::create([
                            'fund_draft_id' => $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $this->wfp_param
                            )->first()->fundDrafts->first()->id,
                            'category_group_id' => $item['category_group_id'],
                            'category_group' => $item['category_group'],
                            'initial_amount' => $item['initial_amount'],
                            'current_total' => $item['current_total'],
                            'balance' => $item['initial_amount'],
                        ]);
                    }
                } else {
                    foreach ($this->current_balance as $item) {

                        if ($this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->first()->fundDrafts->first()->draft_amounts()->exists()) {
                            $draft_amounts = FundDraftAmount::create([
                                'fund_draft_id' => $this->record->fundAllocations->where(
                                    'wpf_type_id',
                                    $this->wfp_param
                                )->first()->fundDrafts->first()->id,
                                'category_group_id' => $item['category_group_id'],
                                'category_group' => $item['category_group'],
                                'initial_amount' => $item['initial_amount'],
                                'current_total' => $item['current_total'],
                                'balance' => $item['initial_amount'],
                            ]);
                        } else {
                            $draft_amounts = $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $this->wfp_param
                            )->first()->fundDrafts->first()->draft_amounts->where(
                                'category_group_id',
                                $item['category_group_id']
                            )->first();
                            $draft_amounts->current_total = $item['current_total'] ?? 0;
                            $draft_amounts->balance = $item['balance'] ?? 0;
                            $draft_amounts->save();
                        }
                    }
                }
            }
        }


        $this->addDraft();
        switch ($this->global_index) {
            case 2:
                $this->clearSupplies();
                break;
            case 3:
                $this->clearMooe();
                break;
            case 4:
                $this->clearTrainings();
                break;
            case 5:
                $this->clearMachine();
                break;
            case 6:
                $this->clearBuilding();
                break;
            case 7:
                $this->clearPs();
                break;
        }
    }

    public function addDraft()
    {
        if ($this->is_supplemental) {
            if (!$this->record->fundAllocations->where(
                'wpf_type_id',
                $this->wfp_param
            )->where(
                'supplemental_quarter_id',
                $this->supplementalQuarterId
            )->first()->fundDrafts()->exists()) {
                FundDraft::create([
                    'fund_allocation_id' => $this->record->fundAllocations->where(
                        'wpf_type_id',
                        $this->wfp_param
                    )->where(
                        'supplemental_quarter_id',
                        $this->supplementalQuarterId
                    )->first()->id,
                ]);
            }
        } else {
            if (!$this->record->fundAllocations->where(
                'wpf_type_id',
                $this->wfp_param
            )->first()->fundDrafts()->exists()) {
                FundDraft::create([
                    'fund_allocation_id' => $this->record->fundAllocations->where(
                        'wpf_type_id',
                        $this->wfp_param
                    )->first()->id,
                ]);
            }
        }
    }

    public function showSuppliesDetails()
    {
        $this->suppliesDetailModal = true;
    }

    public function clearSupplies()
    {
        $this->supplies_particular = null;
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
        if ($this->mooe_particular_id != null) {
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
        } else {
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
        $mooe = $this->mooe_category_attr;
        $budget_category_id = $this->budgetCategoryTabIds[$this->global_index];
        switch ($budget_category_id) {
            case 1:
                $this->calculateSuppliesTotalQuantity();
                $cost_per_unit = $this->mooe_cost_per_unit == null ? 0 : $this->mooe_cost_per_unit;
                $this->supplies_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->mooe_quantity ?? [0]));
                $this->supplies_estimated_budget = number_format(
                    $this->supplies_total_quantity * $cost_per_unit,
                    2
                );
                break;
            case 2:
                $this->calculateMooeTotalQuantity();
                break;
            case 3:
                $this->calculateTrainingTotalQuantity();
                $cost_per_unit = $this->mooe_cost_per_unit == null ? 0 : $this->mooe_cost_per_unit;
                $this->training_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->mooe_quantity ?? [0]));
                $this->training_estimated_budget = number_format(
                    $this->training_total_quantity * $cost_per_unit,
                    2
                );
                break;
            case 4:
                $this->calculateMachineTotalQuantity();
                $cost_per_unit = $this->mooe_cost_per_unit == null ? 0 : $this->mooe_cost_per_unit;
                $this->machine_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->mooe_quantity ?? [0]));
                $this->machine_estimated_budget = number_format($this->machine_total_quantity * $cost_per_unit, 2);
                break;
            case 5:
                $this->calculateBuildingTotalQuantity();
                $cost_per_unit = $this->mooe_cost_per_unit == null ? 0 : $this->mooe_cost_per_unit;
                $this->building_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->mooe_quantity ?? [0]));
                $this->building_estimated_budget = number_format(
                    $this->building_total_quantity * $cost_per_unit,
                    2
                );
                break;
            case 6:
                $this->calculatePsTotalQuantity();
                $cost_per_unit = $this->mooe_cost_per_unit == null ? 0 : $this->mooe_cost_per_unit;
                $this->ps_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->mooe_quantity ?? [0]));
                $this->ps_estimated_budget = number_format($this->ps_total_quantity * $cost_per_unit, 2);
                break;
        }
    }

    public function updatedMooeCostPerUnit()
    {
        $mooe = $this->mooe_category_attr;
        $budget_category_id = $this->budgetCategoryTabIds[$this->global_index];
        switch ($budget_category_id) {
            case 1:
                $this->calculateSuppliesTotalQuantity();
                $cost_per_unit = $this->mooe_cost_per_unit == null ? 0 : $this->mooe_cost_per_unit;
                $this->supplies_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->mooe_quantity ?? [0]));
                $this->supplies_estimated_budget = number_format(
                    $this->supplies_total_quantity * $cost_per_unit,
                    2
                );
                break;
            case 2:
                $this->calculateMooeTotalQuantity();
                break;
            case 3:
                $this->calculateTrainingTotalQuantity();
                $cost_per_unit = $this->mooe_cost_per_unit == null ? 0 : $this->mooe_cost_per_unit;
                $this->training_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->mooe_quantity ?? [0]));
                $this->training_estimated_budget = number_format(
                    $this->training_total_quantity * $cost_per_unit,
                    2
                );
                break;
            case 4:
                $this->calculateMachineTotalQuantity();
                $cost_per_unit = $this->mooe_cost_per_unit == null ? 0 : $this->mooe_cost_per_unit;
                $this->machine_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->mooe_quantity ?? [0]));
                $this->machine_estimated_budget = number_format($this->machine_total_quantity * $cost_per_unit, 2);
                break;
            case 5:
                $this->calculateBuildingTotalQuantity();
                $cost_per_unit = $this->mooe_cost_per_unit == null ? 0 : $this->mooe_cost_per_unit;
                $this->building_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->mooe_quantity ?? [0]));
                $this->building_estimated_budget = number_format(
                    $this->building_total_quantity * $cost_per_unit,
                    2
                );
                break;
            case 6:
                $this->calculatePsTotalQuantity();
                $cost_per_unit = $this->mooe_cost_per_unit == null ? 0 : $this->mooe_cost_per_unit;
                $this->ps_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->mooe_quantity ?? [0]));
                $this->ps_estimated_budget = number_format($this->ps_total_quantity * $cost_per_unit, 2);
                break;
        }
    }

    public function calculateMooeTotalQuantity()
    {
        $cost_per_unit = $this->mooe_cost_per_unit == null ? 0 : $this->mooe_cost_per_unit;
        $this->mooe_total_quantity = array_sum(array_map(function ($quantity) {
            return is_numeric($quantity) ? (int) $quantity : 0;
        }, $this->mooe_quantity ?? [0]));
        $this->mooe_estimated_budget = number_format($this->mooe_total_quantity * $cost_per_unit, 2);
    }

    public function updatedMooeIsRemarks()
    {
        if ($this->mooe_is_remarks === false) {
            $this->mooe_remarks = null;
        }
    }

    public function addMooe()
    {
        //validate all step 2
        $this->validate(
            [
                'mooe_particular_id' => 'required',
                'mooe_uom' => 'required',
                // 'mooe_cost_per_unit' => 'required|gt:0',
                'mooe_total_quantity' => 'gt:0',
            ],
            [
                'mooe_particular_id.required' => 'Particulars is required',
                'mooe_uom.required' => 'UOM is required',
                'mooe_cost_per_unit.required' => 'Cost per unit is required',
                // 'mooe_cost_per_unit.gt' => 'Cost per unit must be greater than 0',
                'mooe_total_quantity.gt' => 'Total quantity must be greater than 0',
            ]
        );

        if ($this->is_supplemental) {
            $is_valid_category_group = $this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)
                ->where('supplemental_quarter_id', $this->supplementalQuarterId)
                ->pluck('category_group_id')
                ->contains($this->mooe_category_attr->categoryGroups->id);

            if ($is_valid_category_group) {
                $fund_allocation = $this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)
                    ->where('supplemental_quarter_id', $this->supplementalQuarterId)
                    ->where('category_group_id', $this->mooe_category_attr->categoryGroups->id)
                    ->first();

                if ($fund_allocation && $fund_allocation->initial_amount == 0) {
                    $this->dialog()->error(
                        $title = 'Operation Failed',
                        $description = 'You don\'t have fund allocation for this title group.',
                    );
                    return;
                }
            }

            $intEstimatedBudget = (float) str_replace(',', '', $this->mooe_estimated_budget);
            if ($this->record->fundAllocations->where(
                'wpf_type_id',
                $this->wfp_param
            )->where(
                'supplemental_quarter_id',
                $this->supplementalQuarterId
            )->first()->fundDrafts()->exists()) {
                $draft_id = $this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->first()->fundDrafts()->first()->id;
            }
            if ($this->mooe != null) {
                foreach ($this->mooe as $key => $mooe) {
                    if ($mooe['particular_id'] == $this->mooe_particular_id && $mooe['uom'] == $this->mooe_uom && $mooe['remarks'] == $this->mooe_remarks) {
                        $this->mooe[$key]['quantity'] = $this->mooe[$key]['quantity'] += $this->mooe_quantity;
                        $this->mooe[$key]['total_quantity'] = $this->mooe[$key]['total_quantity'] += $this->mooe_total_quantity;
                        $this->mooe[$key]['estimated_budget'] = $this->mooe[$key]['estimated_budget'] += $intEstimatedBudget;
                        $this->mooe[$key]['quantity'] = array_map(function ($a, $b) {
                            return $a + $b;
                        }, $this->mooe[$key]['quantity'], $this->mooe_quantity);

                        $draft_items = $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->where(
                            'supplemental_quarter_id',
                            $this->supplementalQuarterId
                        )->first()->fundDrafts->first()->draft_items->where(
                            'particular_id',
                            $this->mooe_particular_id
                        )->where('uom', $this->mooe_uom)->where(
                            'remarks',
                            $this->mooe_remarks
                        )->first();
                        $draft_items->quantity = json_encode($this->mooe[$key]['quantity']);
                        $draft_items->total_quantity = $this->mooe[$key]['total_quantity'];
                        $draft_items->estimated_budget = $this->mooe[$key]['estimated_budget'];
                        $draft_items->save();
                        break;
                    } else {
                        $existingDraftItem = $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->where(
                            'supplemental_quarter_id',
                            $this->supplementalQuarterId
                        )->first()->fundDrafts->first()->draft_items->where(
                            'particular_id',
                            $this->mooe_particular_id
                        )->where('uom', $this->mooe_uom)->where(
                            'remarks',
                            $this->mooe_remarks
                        )->first();
                        if (!$existingDraftItem) {
                            $this->mooe[] = [
                                'budget_category_id' => $this->mooe_category_attr->categoryItems->budget_category_id,
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

                            FundDraftItem::create(
                                [
                                    'fund_draft_id' => $this->record->fundAllocations->where(
                                        'wpf_type_id',
                                        $this->wfp_param
                                    )->where(
                                        'supplemental_quarter_id',
                                        $this->supplementalQuarterId
                                    )->first()->fundDrafts->first()->id,
                                    'budget_category_id' => $this->mooe_category_attr->categoryItems->budget_category_id,
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
                                    'quantity' => json_encode($this->mooe_quantity),
                                    'total_quantity' => $this->mooe_total_quantity,
                                    'uom' => $this->mooe_uom,
                                    'estimated_budget' => $intEstimatedBudget,
                                    'remarks' => $this->mooe_remarks,
                                ]
                            );
                            break;
                        }
                    }
                }
            } else {
                $this->mooe[] = [
                    'budget_category_id' => $this->mooe_category_attr->categoryItems->budget_category_id,
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
                if ($this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->first()->fundDrafts()->exists()) {
                    $draft_items = FundDraftItem::create(
                        [
                            'fund_draft_id' => $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $this->wfp_param
                            )->where(
                                'supplemental_quarter_id',
                                $this->supplementalQuarterId
                            )->first()->fundDrafts->first()->id,
                            'budget_category_id' => $this->mooe_category_attr->categoryItems->budget_category_id,
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
                            'quantity' => json_encode($this->mooe_quantity),
                            'total_quantity' => $this->mooe_total_quantity,
                            'uom' => $this->mooe_uom,
                            'estimated_budget' => $intEstimatedBudget,
                            'remarks' => $this->mooe_remarks,
                        ]
                    );
                } else {
                    $draft = FundDraft::create([
                        'fund_allocation_id' => $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->where(
                            'supplemental_quarter_id',
                            $this->supplementalQuarterId
                        )->first()->id,
                    ]);

                    $draft_items = FundDraftItem::create(
                        [
                            'fund_draft_id' => $draft->id,
                            'budget_category_id' => $this->mooe_category_attr->categoryItems->budget_category_id,
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
                            'quantity' => json_encode($this->mooe_quantity),
                            'total_quantity' => $this->mooe_total_quantity,
                            'uom' => $this->mooe_uom,
                            'estimated_budget' => $intEstimatedBudget,
                            'remarks' => $this->mooe_remarks,
                        ]
                    );
                }
            }

            if ($this->wfp_fund->id === 2 || $this->wfp_fund->id === 4 || $this->wfp_fund->id === 5 || $this->wfp_fund->id === 6 || $this->wfp_fund->id === 7) {
                $categoryGroupId = $this->mooe_category_attr->categoryGroups->id;
                $found = false;

                foreach ($this->current_balance as $key => $balance) {
                    if ($balance && $balance['category_group_id'] == $categoryGroupId) {
                        // $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                        $found = true;
                        // if(!$this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)->first()->fundDrafts()->exists())
                        // {
                        if ($this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->where(
                            'supplemental_quarter_id',
                            $this->supplementalQuarterId
                        )->first()->fundDrafts()->exists()) {
                            $draft_id = $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $this->wfp_param
                            )->where(
                                'supplemental_quarter_id',
                                $this->supplementalQuarterId
                            )->first()->fundDrafts()->first()->id;
                            $draft_amounts = FundDraftAmount::where(
                                'fund_draft_id',
                                $draft_id
                            )->where('category_group_id', $categoryGroupId)->first();
                            if (!is_null($draft_amounts)) {
                                $draft_amounts->current_total = $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                                $draft_amounts->balance = $this->current_balance[$key]['balance'];
                                $draft_amounts->save();
                            } else {
                                FundDraftAmount::create([
                                    'fund_draft_id' => $draft_id,
                                    'category_group_id' => $categoryGroupId,
                                    'category_group' => $this->mooe_category_attr->categoryGroups->name,
                                    'initial_amount' => 0,
                                    'current_total' => $intEstimatedBudget,
                                    'balance' => $this->current_balance[$key]['balance'],
                                ]);
                            }
                        } else {
                            $draft_amounts = FundDraftAmount::where('category_group_id', $categoryGroupId)->first();
                            $draft_amounts->current_total = $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                            $draft_amounts->balance = $this->current_balance[$key]['balance'];
                            $draft_amounts->save();
                        }
                        //}

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
                    // if(!$this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)->first()->fundDrafts()->first()->draft_amounts()->exists())
                    // {
                    FundDraftAmount::create([
                        'fund_draft_id' => $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->where(
                            'supplemental_quarter_id',
                            $this->supplementalQuarterId
                        )->first()->fundDrafts->first()->id,
                        'category_group_id' => $categoryGroupId,
                        'category_group' => $this->mooe_category_attr->categoryGroups->name,
                        'initial_amount' => 0,
                        'current_total' => $intEstimatedBudget,
                        'balance' => $intEstimatedBudget,
                    ]);
                    //}
                }
            } else {
                //add current_total to current balance from estimated budget
                foreach ($this->current_balance as $key => $balance) {
                    if ($balance && $balance['category_group_id'] == $this->mooe_category_attr->categoryGroups->id) {
                        $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                        $this->current_balance[$key]['balance'] -= $intEstimatedBudget;
                    }
                }

                if (!$this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->first()->fundDrafts()->first()->draft_amounts()->exists()) {
                    foreach ($this->current_balance as $item) {
                        $draft_amounts = FundDraftAmount::create([
                            'fund_draft_id' => $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $this->wfp_param
                            )->where(
                                'supplemental_quarter_id',
                                $this->supplementalQuarterId
                            )->first()->fundDrafts->first()->id,
                            'category_group_id' => $item['category_group_id'],
                            'category_group' => $item['category_group'],
                            'initial_amount' => $item['initial_amount'],
                            'current_total' => $item['current_total'],
                            'balance' => $item['initial_amount'],
                        ]);
                    }
                } else {
                    foreach ($this->current_balance as $item) {
                        if ($this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->where(
                            'supplemental_quarter_id',
                            $this->supplementalQuarterId
                        )->first()->fundDrafts->first()->draft_amounts()->exists()) {
                            $draft_amounts = FundDraftAmount::create([
                                'fund_draft_id' => $this->record->fundAllocations->where(
                                    'wpf_type_id',
                                    $this->wfp_param
                                )->where(
                                    'supplemental_quarter_id',
                                    $this->supplementalQuarterId
                                )->first()->fundDrafts->first()->id,
                                'category_group_id' => $item['category_group_id'],
                                'category_group' => $item['category_group'],
                                'initial_amount' => $item['initial_amount'],
                                'current_total' => $item['current_total'],
                                'balance' => $item['initial_amount'],
                            ]);
                        } else {
                            $draft_amounts = $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $this->wfp_param
                            )->where(
                                'supplemental_quarter_id',
                                $this->supplementalQuarterId
                            )->first()->fundDrafts->first()->draft_amounts->where(
                                'category_group_id',
                                $item['category_group_id']
                            )->first();
                            $draft_amounts->current_total = $item['current_total'] ?? 0;
                            $draft_amounts->balance = $item['balance'] ?? 0;
                            $draft_amounts->save();
                        }
                    }
                }
            }
        } else {
            $is_valid_category_group = $this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)
                ->pluck('category_group_id')
                ->contains($this->mooe_category_attr->categoryGroups->id);

            if ($is_valid_category_group) {
                $fund_allocation = $this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)
                    ->where('category_group_id', $this->mooe_category_attr->categoryGroups->id)
                    ->first();

                if ($fund_allocation && $fund_allocation->initial_amount == 0) {
                    $this->dialog()->error(
                        $title = 'Operation Failed',
                        $description = 'You don\'t have fund allocation for this title group.',
                    );
                    return;
                }
            }

            $intEstimatedBudget = (float) str_replace(',', '', $this->mooe_estimated_budget);
            if ($this->record->fundAllocations->where(
                'wpf_type_id',
                $this->wfp_param
            )->first()->fundDrafts()->exists()) {
                $draft_id = $this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->first()->fundDrafts()->first()->id;
            }
            if ($this->mooe != null) {
                foreach ($this->mooe as $key => $mooe) {
                    if ($mooe['particular_id'] == $this->mooe_particular_id && $mooe['uom'] == $this->mooe_uom && $mooe['remarks'] == $this->mooe_remarks) {
                        $this->mooe[$key]['quantity'] = $this->mooe[$key]['quantity'] += $this->mooe_quantity;
                        $this->mooe[$key]['total_quantity'] = $this->mooe[$key]['total_quantity'] += $this->mooe_total_quantity;
                        $this->mooe[$key]['estimated_budget'] = $this->mooe[$key]['estimated_budget'] += $intEstimatedBudget;
                        $this->mooe[$key]['quantity'] = array_map(function ($a, $b) {
                            return $a + $b;
                        }, $this->mooe[$key]['quantity'], $this->mooe_quantity);

                        $draft_items = $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->first()->fundDrafts->first()->draft_items->where(
                            'particular_id',
                            $this->mooe_particular_id
                        )->where('uom', $this->mooe_uom)->where(
                            'remarks',
                            $this->mooe_remarks
                        )->first();
                        $draft_items->quantity = json_encode($this->mooe[$key]['quantity']);
                        $draft_items->total_quantity = $this->mooe[$key]['total_quantity'];
                        $draft_items->estimated_budget = $this->mooe[$key]['estimated_budget'];
                        $draft_items->save();
                        break;
                    } else {
                        $existingDraftItem = $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->first()->fundDrafts->first()->draft_items->where(
                            'particular_id',
                            $this->mooe_particular_id
                        )->where('uom', $this->mooe_uom)->where(
                            'remarks',
                            $this->mooe_remarks
                        )->first();
                        if (!$existingDraftItem) {
                            $this->mooe[] = [
                                'budget_category_id' => $this->mooe_category_attr->categoryItems->budget_category_id,
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

                            FundDraftItem::create(
                                [
                                    'fund_draft_id' => $this->record->fundAllocations->where(
                                        'wpf_type_id',
                                        $this->wfp_param
                                    )->first()->fundDrafts->first()->id,
                                    'budget_category_id' => $this->mooe_category_attr->categoryItems->budget_category_id,
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
                                    'quantity' => json_encode($this->mooe_quantity),
                                    'total_quantity' => $this->mooe_total_quantity,
                                    'uom' => $this->mooe_uom,
                                    'estimated_budget' => $intEstimatedBudget,
                                    'remarks' => $this->mooe_remarks,
                                ]
                            );
                            break;
                        }
                    }
                }
            } else {
                $this->mooe[] = [
                    'budget_category_id' => $this->mooe_category_attr->categoryItems->budget_category_id,
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
                if ($this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->first()->fundDrafts()->exists()) {
                    $draft_items = FundDraftItem::create(
                        [
                            'fund_draft_id' => $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $this->wfp_param
                            )->first()->fundDrafts->first()->id,
                            'budget_category_id' => $this->mooe_category_attr->categoryItems->budget_category_id,
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
                            'quantity' => json_encode($this->mooe_quantity),
                            'total_quantity' => $this->mooe_total_quantity,
                            'uom' => $this->mooe_uom,
                            'estimated_budget' => $intEstimatedBudget,
                            'remarks' => $this->mooe_remarks,
                        ]
                    );
                } else {
                    $draft = FundDraft::create([
                        'fund_allocation_id' => $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->first()->id,
                    ]);

                    $draft_items = FundDraftItem::create(
                        [
                            'fund_draft_id' => $draft->id,
                            'budget_category_id' => $this->mooe_category_attr->categoryItems->budget_category_id,
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
                            'quantity' => json_encode($this->mooe_quantity),
                            'total_quantity' => $this->mooe_total_quantity,
                            'uom' => $this->mooe_uom,
                            'estimated_budget' => $intEstimatedBudget,
                            'remarks' => $this->mooe_remarks,
                        ]
                    );
                }
            }

            if ($this->wfp_fund->id === 2 || $this->wfp_fund->id === 4 || $this->wfp_fund->id === 5 || $this->wfp_fund->id === 6 || $this->wfp_fund->id === 7) {
                $categoryGroupId = $this->mooe_category_attr->categoryGroups->id;
                $found = false;

                foreach ($this->current_balance as $key => $balance) {
                    if ($balance && $balance['category_group_id'] == $categoryGroupId) {
                        // $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                        $found = true;
                        // if(!$this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)->first()->fundDrafts()->exists())
                        // {
                        $draft_amounts = FundDraftAmount::where('category_group_id', $categoryGroupId)->first();

                        $draft_amounts->current_total = $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                        $draft_amounts->balance = $this->current_balance[$key]['balance'];
                        $draft_amounts->save();
                        //}

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
                    // if(!$this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)->first()->fundDrafts()->first()->draft_amounts()->exists())
                    // {
                    FundDraftAmount::create([
                        'fund_draft_id' => $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->first()->fundDrafts->first()->id,
                        'category_group_id' => $categoryGroupId,
                        'category_group' => $this->mooe_category_attr->categoryGroups->name,
                        'initial_amount' => 0,
                        'current_total' => $intEstimatedBudget,
                        'balance' => $intEstimatedBudget,
                    ]);
                    //}
                }
            } else {
                //add current_total to current balance from estimated budget
                foreach ($this->current_balance as $key => $balance) {
                    if ($balance && $balance['category_group_id'] == $this->mooe_category_attr->categoryGroups->id) {
                        $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                        $this->current_balance[$key]['balance'] -= $intEstimatedBudget;
                    }
                }

                if (!$this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->first()->fundDrafts()->first()->draft_amounts()->exists()) {
                    foreach ($this->current_balance as $item) {
                        $draft_amounts = FundDraftAmount::create([
                            'fund_draft_id' => $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $this->wfp_param
                            )->first()->fundDrafts->first()->id,
                            'category_group_id' => $item['category_group_id'],
                            'category_group' => $item['category_group'],
                            'initial_amount' => $item['initial_amount'],
                            'current_total' => $item['current_total'],
                            'balance' => $item['initial_amount'],
                        ]);
                    }
                } else {
                    foreach ($this->current_balance as $item) {
                        if ($this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->first()->fundDrafts->first()->draft_amounts()->exists()) {
                            $draft_amounts = FundDraftAmount::create([
                                'fund_draft_id' => $this->record->fundAllocations->where(
                                    'wpf_type_id',
                                    $this->wfp_param
                                )->first()->fundDrafts->first()->id,
                                'category_group_id' => $item['category_group_id'],
                                'category_group' => $item['category_group'],
                                'initial_amount' => $item['initial_amount'],
                                'current_total' => $item['current_total'],
                                'balance' => $item['initial_amount'],
                            ]);
                        } else {
                            $draft_amounts = $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $this->wfp_param
                            )->first()->fundDrafts->first()->draft_amounts->where(
                                'category_group_id',
                                $item['category_group_id']
                            )->first();
                            $draft_amounts->current_total = $item['current_total'];
                            $draft_amounts->balance = $item['balance'];
                            $draft_amounts->save();
                        }
                    }
                }
            }
        }


        $this->addDraft();
        switch ($this->global_index) {
            case 2:
                $this->clearSupplies();
                break;
            case 3:
                $this->clearMooe();
                break;
            case 4:
                $this->clearTrainings();
                break;
            case 5:
                $this->clearMachine();
                break;
            case 6:
                $this->clearBuilding();
                break;
            case 7:
                $this->clearPs();
                break;
        }
    }

    public function showMooeDetails()
    {
        $this->mooeDetailModal = true;
    }

    public function clearMooe()
    {
        $this->supplies_particular = null;
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
        if ($this->training_particular_id != null) {
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
        } else {
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
        $training = $this->training_category_attr;
         $budget_category_id = $this->budgetCategoryTabIds[$this->global_index];
        switch ($budget_category_id) {
            case 1:
                $this->calculateSuppliesTotalQuantity();
                $cost_per_unit = $this->training_cost_per_unit == null ? 0 : $this->training_cost_per_unit;
                $this->supplies_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->training_quantity ?? [0]));
                $this->supplies_estimated_budget = number_format(
                    $this->supplies_total_quantity * $cost_per_unit,
                    2
                );
                break;
            case 2:
                $this->calculateMooeTotalQuantity();
                $cost_per_unit = $this->training_cost_per_unit == null ? 0 : $this->training_cost_per_unit;
                $this->mooe_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->training_quantity ?? [0]));
                $this->mooe_estimated_budget = number_format($this->mooe_total_quantity * $cost_per_unit, 2);
                break;
            case 3:
                $this->calculateTrainingTotalQuantity();
                break;
            case 4:
                $this->calculateMachineTotalQuantity();
                $cost_per_unit = $this->training_cost_per_unit == null ? 0 : $this->training_cost_per_unit;
                $this->machine_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->training_quantity ?? [0]));
                $this->machine_estimated_budget = number_format($this->machine_total_quantity * $cost_per_unit, 2);
                break;
            case 5:
                $this->calculateBuildingTotalQuantity();
                $cost_per_unit = $this->training_cost_per_unit == null ? 0 : $this->training_cost_per_unit;
                $this->building_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->training_quantity ?? [0]));
                $this->building_estimated_budget = number_format(
                    $this->building_total_quantity * $cost_per_unit,
                    2
                );
                break;
            case 6:
                $this->calculatePsTotalQuantity();
                $cost_per_unit = $this->training_cost_per_unit == null ? 0 : $this->training_cost_per_unit;
                $this->ps_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->training_quantity ?? [0]));
                $this->ps_estimated_budget = number_format($this->ps_total_quantity * $cost_per_unit, 2);
                break;
        }
    }

    public function updatedTrainingCostPerUnit()
    {
        $training = $this->training_category_attr;
      $budget_category_id = $this->budgetCategoryTabIds[$this->global_index];
        switch ($budget_category_id) {
            case 1:
                $this->calculateSuppliesTotalQuantity();
                $cost_per_unit = $this->training_cost_per_unit == null ? 0 : $this->training_cost_per_unit;
                $this->supplies_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->training_quantity ?? [0]));
                $this->supplies_estimated_budget = number_format(
                    $this->supplies_total_quantity * $cost_per_unit,
                    2
                );
                break;
            case 2:
                $this->calculateMooeTotalQuantity();
                $cost_per_unit = $this->training_cost_per_unit == null ? 0 : $this->training_cost_per_unit;
                $this->mooe_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->training_quantity ?? [0]));
                $this->mooe_estimated_budget = number_format($this->mooe_total_quantity * $cost_per_unit, 2);
                break;
            case 3:
                $this->calculateTrainingTotalQuantity();
                break;
            case 4:
                $this->calculateMachineTotalQuantity();
                $cost_per_unit = $this->training_cost_per_unit == null ? 0 : $this->training_cost_per_unit;
                $this->machine_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->training_quantity ?? [0]));
                $this->machine_estimated_budget = number_format($this->machine_total_quantity * $cost_per_unit, 2);
                break;
            case 5:
                $this->calculateBuildingTotalQuantity();
                $cost_per_unit = $this->training_cost_per_unit == null ? 0 : $this->training_cost_per_unit;
                $this->building_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->training_quantity ?? [0]));
                $this->building_estimated_budget = number_format(
                    $this->building_total_quantity * $cost_per_unit,
                    2
                );
                break;
            case 6:
                $this->calculatePsTotalQuantity();
                $cost_per_unit = $this->training_cost_per_unit == null ? 0 : $this->training_cost_per_unit;
                $this->ps_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->training_quantity ?? [0]));
                $this->ps_estimated_budget = number_format($this->ps_total_quantity * $cost_per_unit, 2);
                break;
        }
    }

    public function calculateTrainingTotalQuantity()
    {
        $cost_per_unit = $this->training_cost_per_unit == null ? 0 : $this->training_cost_per_unit;
        $this->training_total_quantity = array_sum(array_map(function ($quantity) {
            return is_numeric($quantity) ? (int) $quantity : 0;
        }, $this->training_quantity ?? [0]));
        $this->training_estimated_budget = number_format($this->training_total_quantity * $cost_per_unit, 2);
    }

    public function updatedTrainingIsRemarks()
    {
        if ($this->training_is_remarks === false) {
            $this->training_remarks = null;
        }
    }

    public function addTraining()
    {
        //validate all step 2
        $this->validate(
            [
                'training_particular_id' => 'required',
                'training_uom' => 'required',
                // 'training_cost_per_unit' => 'required|gt:0',
                'training_total_quantity' => 'gt:0',
            ],
            [
                'training_particular_id.required' => 'Particulars is required',
                'training_uom.required' => 'UOM is required',
                'training_cost_per_unit.required' => 'Cost per unit is required',
                // 'training_cost_per_unit.gt' => 'Cost per unit must be greater than 0',
                'training_total_quantity.gt' => 'Total quantity must be greater than 0',
            ]
        );

        if ($this->is_supplemental) {
            $is_valid_category_group = $this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)
                ->where('supplemental_quarter_id', $this->supplementalQuarterId)
                ->pluck('category_group_id')
                ->contains($this->training_category_attr->categoryGroups->id);

            if ($is_valid_category_group) {
                $fund_allocation = $this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)
                    ->where('supplemental_quarter_id', $this->supplementalQuarterId)
                    ->where('category_group_id', $this->training_category_attr->categoryGroups->id)
                    ->first();

                if ($fund_allocation && $fund_allocation->initial_amount == 0) {
                    $this->dialog()->error(
                        $title = 'Operation Failed',
                        $description = 'You don\'t have fund allocation for this title group.',
                    );
                    return;
                }
            }

            $intEstimatedBudget = (float) str_replace(',', '', $this->training_estimated_budget);
            if ($this->record->fundAllocations->where(
                'wpf_type_id',
                $this->wfp_param
            )->where(
                'supplemental_quarter_id',
                $this->supplementalQuarterId
            )->first()->fundDrafts()->exists()) {
                $draft_id = $this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->first()->fundDrafts()->first()->id;
            }
            //$draft_id = $this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)->first()->fundDrafts()->first()->id;
            if ($this->trainings != null) {
                foreach ($this->trainings as $key => $training) {
                    if ($training['particular_id'] == $this->training_particular_id && $training['uom'] == $this->training_uom && $training['remarks'] == $this->training_remarks) {
                        $this->trainings[$key]['quantity'] = $this->trainings[$key]['quantity'] += $this->training_quantity;
                        $this->trainings[$key]['total_quantity'] = $this->trainings[$key]['total_quantity'] += $this->training_total_quantity;
                        $this->trainings[$key]['estimated_budget'] = $this->trainings[$key]['estimated_budget'] += $intEstimatedBudget;
                        $this->trainings[$key]['quantity'] = array_map(function ($a, $b) {
                            return $a + $b;
                        }, $this->trainings[$key]['quantity'], $this->training_quantity);

                        $draft_items = $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->where(
                            'supplemental_quarter_id',
                            $this->supplementalQuarterId
                        )->first()->fundDrafts->first()->draft_items->where(
                            'particular_id',
                            $this->training_particular_id
                        )->where('uom', $this->training_uom)->where(
                            'remarks',
                            $this->training_remarks
                        )->first();
                        $draft_items->quantity = json_encode($this->trainings[$key]['quantity']);
                        $draft_items->total_quantity = $this->trainings[$key]['total_quantity'];
                        $draft_items->estimated_budget = $this->trainings[$key]['estimated_budget'];
                        $draft_items->save();
                        break;
                    } else {
                        $existingDraftItem = $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->where(
                            'supplemental_quarter_id',
                            $this->supplementalQuarterId
                        )->first()->fundDrafts->first()->draft_items->where(
                            'particular_id',
                            $this->training_particular_id
                        )->where('uom', $this->training_uom)->where(
                            'remarks',
                            $this->training_remarks
                        )->first();
                        if (!$existingDraftItem) {
                            $this->trainings[] = [
                                'budget_category_id' => $this->training_category_attr->categoryItems->budget_category_id,
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

                            FundDraftItem::create(
                                [
                                    'fund_draft_id' => $this->record->fundAllocations->where(
                                        'wpf_type_id',
                                        $this->wfp_param
                                    )->where(
                                        'supplemental_quarter_id',
                                        $this->supplementalQuarterId
                                    )->first()->fundDrafts->first()->id,
                                    'budget_category_id' => $this->training_category_attr->categoryItems->budget_category_id,
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
                                    'quantity' => json_encode($this->training_quantity),
                                    'total_quantity' => $this->training_total_quantity,
                                    'uom' => $this->training_uom,
                                    'estimated_budget' => $intEstimatedBudget,
                                    'remarks' => $this->training_remarks,
                                ]
                            );
                            break;
                        }
                    }
                }
            } else {
                $this->trainings[] = [
                    'budget_category_id' => $this->training_category_attr->categoryItems->budget_category_id,
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
                if ($this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->first()->fundDrafts()->exists()) {
                    $draft_items = FundDraftItem::create(
                        [
                            'fund_draft_id' => $draft_id,
                            'budget_category_id' => $this->training_category_attr->categoryItems->budget_category_id,
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
                            'quantity' => json_encode($this->training_quantity),
                            'total_quantity' => $this->training_total_quantity,
                            'uom' => $this->training_uom,
                            'estimated_budget' => $intEstimatedBudget,
                            'remarks' => $this->training_remarks,
                        ]
                    );
                } else {
                    $draft = FundDraft::create([
                        'fund_allocation_id' => $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->where(
                            'supplemental_quarter_id',
                            $this->supplementalQuarterId
                        )->first()->id,
                    ]);

                    $draft_items = FundDraftItem::create(
                        [
                            'fund_draft_id' => $draft->id,
                            'budget_category_id' => $this->training_category_attr->categoryItems->budget_category_id,
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
                            'quantity' => json_encode($this->training_quantity),
                            'total_quantity' => $this->training_total_quantity,
                            'uom' => $this->training_uom,
                            'estimated_budget' => $intEstimatedBudget,
                            'remarks' => $this->training_remarks,
                        ]
                    );
                }
            }

            if ($this->wfp_fund->id === 2 || $this->wfp_fund->id === 4 || $this->wfp_fund->id === 5 || $this->wfp_fund->id === 6 || $this->wfp_fund->id === 7) {
                $categoryGroupId = $this->training_category_attr->categoryGroups->id;
                $found = false;

                foreach ($this->current_balance as $key => $balance) {
                    if ($balance && $balance['category_group_id'] == $categoryGroupId) {
                        // $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                        $found = true;
                        // if(!$this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)->first()->fundDrafts()->exists())
                        // {
                        if ($this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->where(
                            'supplemental_quarter_id',
                            $this->supplementalQuarterId
                        )->first()->fundDrafts()->exists()) {
                            $draft_id = $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $this->wfp_param
                            )->where(
                                'supplemental_quarter_id',
                                $this->supplementalQuarterId
                            )->first()->fundDrafts()->first()->id;
                            $draft_amounts = FundDraftAmount::where(
                                'fund_draft_id',
                                $draft_id
                            )->where('category_group_id', $categoryGroupId)->first();
                            $draft_amounts->current_total = $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                            $draft_amounts->balance = $this->current_balance[$key]['balance'];
                            $draft_amounts->save();
                        } else {
                            $draft_amounts = FundDraftAmount::where('category_group_id', $categoryGroupId)->first();
                            $draft_amounts->current_total = $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                            $draft_amounts->balance = $this->current_balance[$key]['balance'];
                            $draft_amounts->save();
                        }
                        //}

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
                    // if(!$this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)->first()->fundDrafts()->first()->draft_amounts()->exists())
                    // {
                    FundDraftAmount::create([
                        'fund_draft_id' => $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->where(
                            'supplemental_quarter_id',
                            $this->supplementalQuarterId
                        )->first()->fundDrafts->first()->id,
                        'category_group_id' => $categoryGroupId,
                        'category_group' => $this->training_category_attr->categoryGroups->name,
                        'initial_amount' => 0,
                        'current_total' => $intEstimatedBudget,
                        'balance' => $intEstimatedBudget,
                    ]);
                    //}
                }
            } else {
                //add current_total to current balance from estimated budget
                foreach ($this->current_balance as $key => $balance) {
                    if ($balance && $balance['category_group_id'] == $this->training_category_attr->categoryGroups->id) {
                        $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                        $this->current_balance[$key]['balance'] -= $intEstimatedBudget;
                    }
                }

                if (!$this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->first()->fundDrafts()->first()->draft_amounts()->exists()) {
                    foreach ($this->current_balance as $item) {
                        $draft_amounts = FundDraftAmount::create([
                            'fund_draft_id' => $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $this->wfp_param
                            )->where(
                                'supplemental_quarter_id',
                                $this->supplementalQuarterId
                            )->first()->fundDrafts->first()->id,
                            'category_group_id' => $item['category_group_id'],
                            'category_group' => $item['category_group'],
                            'initial_amount' => $item['initial_amount'],
                            'current_total' => $item['current_total'],
                            'balance' => $item['initial_amount'],
                        ]);
                    }
                } else {
                    foreach ($this->current_balance as $item) {
                        if ($this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->where(
                            'supplemental_quarter_id',
                            $this->supplementalQuarterId
                        )->first()->fundDrafts->first()->draft_amounts()->exists()) {
                            $draft_amounts = FundDraftAmount::create([
                                'fund_draft_id' => $this->record->fundAllocations->where(
                                    'wpf_type_id',
                                    $this->wfp_param
                                )->where(
                                    'supplemental_quarter_id',
                                    $this->supplementalQuarterId
                                )->first()->fundDrafts->first()->id,
                                'category_group_id' => $item['category_group_id'],
                                'category_group' => $item['category_group'],
                                'initial_amount' => $item['initial_amount'],
                                'current_total' => $item['current_total'],
                                'balance' => $item['initial_amount'],
                            ]);
                        } else {
                            $draft_amounts = $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $this->wfp_param
                            )->where(
                                'supplemental_quarter_id',
                                $this->supplementalQuarterId
                            )->first()->fundDrafts->first()->draft_amounts->where(
                                'category_group_id',
                                $item['category_group_id']
                            )->first();
                            $draft_amounts->current_total = $item['current_total'] ?? 0;
                            $draft_amounts->balance = $item['balance'] ?? 0;
                            $draft_amounts->save();
                        }
                    }
                }
            }
        } else {
            $is_valid_category_group = $this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)
                ->pluck('category_group_id')
                ->contains($this->training_category_attr->categoryGroups->id);

            if ($is_valid_category_group) {
                $fund_allocation = $this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)
                    ->where('category_group_id', $this->training_category_attr->categoryGroups->id)
                    ->first();

                if ($fund_allocation && $fund_allocation->initial_amount == 0) {
                    $this->dialog()->error(
                        $title = 'Operation Failed',
                        $description = 'You don\'t have fund allocation for this title group.',
                    );
                    return;
                }
            }

            $intEstimatedBudget = (float) str_replace(',', '', $this->training_estimated_budget);
            //$draft_id = $this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)->first()->fundDrafts()->first()->id;
            if ($this->trainings != null) {
                foreach ($this->trainings as $key => $training) {
                    if ($training['particular_id'] == $this->training_particular_id && $training['uom'] == $this->training_uom && $training['remarks'] == $this->training_remarks) {
                        $this->trainings[$key]['quantity'] = $this->trainings[$key]['quantity'] += $this->training_quantity;
                        $this->trainings[$key]['total_quantity'] = $this->trainings[$key]['total_quantity'] += $this->training_total_quantity;
                        $this->trainings[$key]['estimated_budget'] = $this->trainings[$key]['estimated_budget'] += $intEstimatedBudget;
                        $this->trainings[$key]['quantity'] = array_map(function ($a, $b) {
                            return $a + $b;
                        }, $this->trainings[$key]['quantity'], $this->training_quantity);

                        $draft_items = $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->first()->fundDrafts->first()->draft_items->where(
                            'particular_id',
                            $this->training_particular_id
                        )->where('uom', $this->training_uom)->where(
                            'remarks',
                            $this->training_remarks
                        )->first();
                        $draft_items->quantity = json_encode($this->trainings[$key]['quantity']);
                        $draft_items->total_quantity = $this->trainings[$key]['total_quantity'];
                        $draft_items->estimated_budget = $this->trainings[$key]['estimated_budget'];
                        $draft_items->save();
                        break;
                    } else {
                        $existingDraftItem = $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->first()->fundDrafts->first()->draft_items->where(
                            'particular_id',
                            $this->training_particular_id
                        )->where('uom', $this->training_uom)->where(
                            'remarks',
                            $this->training_remarks
                        )->first();
                        if (!$existingDraftItem) {
                            $this->trainings[] = [
                                'budget_category_id' => $this->training_category_attr->categoryItems->budget_category_id,
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

                            FundDraftItem::create(
                                [
                                    'fund_draft_id' => $this->record->fundAllocations->where(
                                        'wpf_type_id',
                                        $this->wfp_param
                                    )->first()->fundDrafts->first()->id,
                                    'budget_category_id' => $this->training_category_attr->categoryItems->budget_category_id,
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
                                    'quantity' => json_encode($this->training_quantity),
                                    'total_quantity' => $this->training_total_quantity,
                                    'uom' => $this->training_uom,
                                    'estimated_budget' => $intEstimatedBudget,
                                    'remarks' => $this->training_remarks,
                                ]
                            );
                            break;
                        }
                    }
                }
            } else {
                $this->trainings[] = [
                    'budget_category_id' => $this->training_category_attr->categoryItems->budget_category_id,
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
                if ($this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->first()->fundDrafts()->exists()) {
                    $draft_items = FundDraftItem::create(
                        [
                            'fund_draft_id' => $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $this->wfp_param
                            )->first()->fundDrafts->first()->id,
                            'budget_category_id' => $this->training_category_attr->categoryItems->budget_category_id,
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
                            'quantity' => json_encode($this->training_quantity),
                            'total_quantity' => $this->training_total_quantity,
                            'uom' => $this->training_uom,
                            'estimated_budget' => $intEstimatedBudget,
                            'remarks' => $this->training_remarks,
                        ]
                    );
                } else {
                    $draft = FundDraft::create([
                        'fund_allocation_id' => $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->first()->id,
                    ]);

                    $draft_items = FundDraftItem::create(
                        [
                            'fund_draft_id' => $draft->id,
                            'budget_category_id' => $this->training_category_attr->categoryItems->budget_category_id,
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
                            'quantity' => json_encode($this->training_quantity),
                            'total_quantity' => $this->training_total_quantity,
                            'uom' => $this->training_uom,
                            'estimated_budget' => $intEstimatedBudget,
                            'remarks' => $this->training_remarks,
                        ]
                    );
                }
            }

            if ($this->wfp_fund->id === 2 || $this->wfp_fund->id === 4 || $this->wfp_fund->id === 5 || $this->wfp_fund->id === 6 || $this->wfp_fund->id === 7) {
                $categoryGroupId = $this->training_category_attr->categoryGroups->id;
                $found = false;

                foreach ($this->current_balance as $key => $balance) {
                    if ($balance && $balance['category_group_id'] == $categoryGroupId) {
                        // $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                        $found = true;
                        // if(!$this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)->first()->fundDrafts()->exists())
                        // {
                        $draft_amounts = FundDraftAmount::where('category_group_id', $categoryGroupId)->first();

                        $draft_amounts->current_total = $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                        $draft_amounts->balance = $this->current_balance[$key]['balance'];
                        $draft_amounts->save();
                        //}

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
                    // if(!$this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)->first()->fundDrafts()->first()->draft_amounts()->exists())
                    // {
                    FundDraftAmount::create([
                        'fund_draft_id' => $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->first()->fundDrafts->first()->id,
                        'category_group_id' => $categoryGroupId,
                        'category_group' => $this->training_category_attr->categoryGroups->name,
                        'initial_amount' => 0,
                        'current_total' => $intEstimatedBudget,
                        'balance' => $intEstimatedBudget,
                    ]);
                    //}
                }
            } else {
                //add current_total to current balance from estimated budget
                foreach ($this->current_balance as $key => $balance) {
                    if ($balance && $balance['category_group_id'] == $this->training_category_attr->categoryGroups->id) {
                        $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                        $this->current_balance[$key]['balance'] -= $intEstimatedBudget;
                    }
                }

                if (!$this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->first()->fundDrafts()->first()->draft_amounts()->exists()) {
                    foreach ($this->current_balance as $item) {
                        $draft_amounts = FundDraftAmount::create([
                            'fund_draft_id' => $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $this->wfp_param
                            )->first()->fundDrafts->first()->id,
                            'category_group_id' => $item['category_group_id'],
                            'category_group' => $item['category_group'],
                            'initial_amount' => $item['initial_amount'],
                            'current_total' => $item['current_total'],
                            'balance' => $item['initial_amount'],
                        ]);
                    }
                } else {
                    foreach ($this->current_balance as $item) {
                        if ($this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->first()->fundDrafts->first()->draft_amounts()->exists()) {
                            $draft_amounts = FundDraftAmount::create([
                                'fund_draft_id' => $this->record->fundAllocations->where(
                                    'wpf_type_id',
                                    $this->wfp_param
                                )->first()->fundDrafts->first()->id,
                                'category_group_id' => $item['category_group_id'],
                                'category_group' => $item['category_group'],
                                'initial_amount' => $item['initial_amount'],
                                'current_total' => $item['current_total'],
                                'balance' => $item['initial_amount'],
                            ]);
                        } else {
                            $draft_amounts = $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $this->wfp_param
                            )->first()->fundDrafts->first()->draft_amounts->where(
                                'category_group_id',
                                $item['category_group_id']
                            )->first();
                            $draft_amounts->current_total = $item['current_total'];
                            $draft_amounts->balance = $item['balance'];
                            $draft_amounts->save();
                        }
                    }
                }
            }
        }

        $this->addDraft();
        switch ($this->global_index) {
            case 2:
                $this->clearSupplies();
                break;
            case 3:
                $this->clearMooe();
                break;
            case 4:
                $this->clearTrainings();
                break;
            case 5:
                $this->clearMachine();
                break;
            case 6:
                $this->clearBuilding();
                break;
            case 7:
                $this->clearPs();
                break;
        }
    }

    public function showTrainingDetails()
    {
        $this->trainingDetailModal = true;
    }

    public function clearTrainings()
    {
        $this->supplies_particular = null;
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
        if ($this->machine_particular_id != null) {
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
        } else {
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
        $machine = $this->machine_category_attr;
        $budget_category_id = $this->budgetCategoryTabIds[$this->global_index];

        switch ($budget_category_id) {
            case 1:
                $this->calculateSuppliesTotalQuantity();
                $cost_per_unit = $this->machine_cost_per_unit == null ? 0 : $this->machine_cost_per_unit;
                $this->supplies_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->machine_quantity ?? [0]));
                $this->supplies_estimated_budget = number_format(
                    $this->supplies_total_quantity * $cost_per_unit,
                    2
                );
                break;
            case 2:
                $this->calculateMooeTotalQuantity();
                $cost_per_unit = $this->machine_cost_per_unit == null ? 0 : $this->machine_cost_per_unit;
                $this->mooe_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->machine_quantity ?? [0]));
                $this->mooe_estimated_budget = number_format($this->mooe_total_quantity * $cost_per_unit, 2);
                break;
            case 3:
                $this->calculateTrainingTotalQuantity();
                $cost_per_unit = $this->machine_cost_per_unit == null ? 0 : $this->machine_cost_per_unit;
                $this->training_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->machine_quantity ?? [0]));
                $this->training_estimated_budget = number_format(
                    $this->training_total_quantity * $cost_per_unit,
                    2
                );
                break;
            case 4:
                $this->calculateMachineTotalQuantity();
                break;
            case 5:
                $this->calculateBuildingTotalQuantity();
                $cost_per_unit = $this->machine_cost_per_unit == null ? 0 : $this->machine_cost_per_unit;
                $this->building_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->machine_quantity ?? [0]));
                $this->building_estimated_budget = number_format(
                    $this->building_total_quantity * $cost_per_unit,
                    2
                );
                break;
            case 6:
                $this->calculatePsTotalQuantity();
                $cost_per_unit = $this->machine_cost_per_unit == null ? 0 : $this->machine_cost_per_unit;
                $this->ps_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->machine_quantity ?? [0]));
                $this->ps_estimated_budget = number_format($this->ps_total_quantity * $cost_per_unit, 2);
                break;
        }
    }

    public function updatedMachineCostPerUnit()
    {
        $machine = $this->machine_category_attr;
     $budget_category_id = $this->budgetCategoryTabIds[$this->global_index];

        switch ($budget_category_id) {
            case 1:
                $this->calculateSuppliesTotalQuantity();
                $cost_per_unit = $this->machine_cost_per_unit == null ? 0 : $this->machine_cost_per_unit;
                $this->supplies_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->machine_quantity ?? [0]));
                $this->supplies_estimated_budget = number_format(
                    $this->supplies_total_quantity * $cost_per_unit,
                    2
                );
                break;
            case 2:
                $this->calculateMooeTotalQuantity();
                $cost_per_unit = $this->machine_cost_per_unit == null ? 0 : $this->machine_cost_per_unit;
                $this->mooe_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->machine_quantity ?? [0]));
                $this->mooe_estimated_budget = number_format($this->mooe_total_quantity * $cost_per_unit, 2);
                break;
            case 3:
                $this->calculateTrainingTotalQuantity();
                $cost_per_unit = $this->machine_cost_per_unit == null ? 0 : $this->machine_cost_per_unit;
                $this->training_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->machine_quantity ?? [0]));
                $this->training_estimated_budget = number_format(
                    $this->training_total_quantity * $cost_per_unit,
                    2
                );
                break;
            case 4:
                $this->calculateMachineTotalQuantity();
                break;
            case 5:
                $this->calculateBuildingTotalQuantity();
                $cost_per_unit = $this->machine_cost_per_unit == null ? 0 : $this->machine_cost_per_unit;
                $this->building_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->machine_quantity ?? [0]));
                $this->building_estimated_budget = number_format(
                    $this->building_total_quantity * $cost_per_unit,
                    2
                );
                break;
            case 6:
                $this->calculatePsTotalQuantity();
                $cost_per_unit = $this->machine_cost_per_unit == null ? 0 : $this->machine_cost_per_unit;
                $this->ps_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->machine_quantity ?? [0]));
                $this->ps_estimated_budget = number_format($this->ps_total_quantity * $cost_per_unit, 2);
                break;
        }
    }

    public function calculateMachineTotalQuantity()
    {
        $cost_per_unit = $this->machine_cost_per_unit == null ? 0 : $this->machine_cost_per_unit;
        $this->machine_total_quantity = array_sum(array_map(function ($quantity) {
            return is_numeric($quantity) ? (int) $quantity : 0;
        }, $this->machine_quantity ?? [0]));
        $this->machine_estimated_budget = number_format($this->machine_total_quantity * $cost_per_unit, 2);
    }

    public function updatedMachineIsRemarks()
    {
        if ($this->machine_is_remarks === false) {
            $this->machine_remarks = null;
        }
    }

    public function addMachine()
    {
        //validate all step 2
        $this->validate(
            [
                'machine_particular_id' => 'required',
                'machine_uom' => 'required',
                // 'machine_cost_per_unit' => 'required|gt:0',
                'machine_total_quantity' => 'gt:0',
            ],
            [
                'machine_particular_id.required' => 'Particulars is required',
                'machine_uom.required' => 'UOM is required',
                'machine_cost_per_unit.required' => 'Cost per unit is required',
                // 'machine_cost_per_unit.gt' => 'Cost per unit must be greater than 0',
                'machine_total_quantity.gt' => 'Total quantity must be greater than 0',
            ]
        );

        if ($this->is_supplemental) {
            $is_valid_category_group = $this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)
                ->where('supplemental_quarter_id', $this->supplementalQuarterId)
                ->pluck('category_group_id')
                ->contains($this->machine_category_attr->categoryGroups->id);

            if ($is_valid_category_group) {
                $fund_allocation = $this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)
                    ->where('supplemental_quarter_id', $this->supplementalQuarterId)
                    ->where('category_group_id', $this->machine_category_attr->categoryGroups->id)
                    ->first();

                if ($fund_allocation && $fund_allocation->initial_amount == 0) {
                    $this->dialog()->error(
                        $title = 'Operation Failed',
                        $description = 'You don\'t have fund allocation for this title group.',
                    );
                    return;
                }
            }

            $intEstimatedBudget = (float) str_replace(',', '', $this->machine_estimated_budget);
            if ($this->record->fundAllocations->where(
                'wpf_type_id',
                $this->wfp_param
            )->where(
                'supplemental_quarter_id',
                $this->supplementalQuarterId
            )->first()->fundDrafts()->exists()) {
                $draft_id = $this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->first()->fundDrafts()->first()->id;
            }
            //$draft_id = $this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)->first()->fundDrafts()->first()->id;
            if ($this->machines != null) {
                foreach ($this->machines as $key => $machine) {
                    if ($machine['particular_id'] == $this->machine_particular_id && $machine['uom'] == $this->machine_uom && $machine['remarks'] == $this->machine_remarks) {
                        $this->machines[$key]['quantity'] = $this->machines[$key]['quantity'] += $this->machine_quantity;
                        $this->machines[$key]['total_quantity'] = $this->machines[$key]['total_quantity'] += $this->machine_total_quantity;
                        $this->machines[$key]['estimated_budget'] = $this->machines[$key]['estimated_budget'] += $intEstimatedBudget;
                        $this->machines[$key]['quantity'] = array_map(function ($a, $b) {
                            return (int) $a + (int) $b;
                        }, $this->machines[$key]['quantity'], $this->machine_quantity);

                        $draft_items = $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->where(
                            'supplemental_quarter_id',
                            $this->supplementalQuarterId
                        )->first()->fundDrafts->first()->draft_items->where(
                            'particular_id',
                            $this->machine_particular_id
                        )->where('uom', $this->machine_uom)->where(
                            'remarks',
                            $this->machine_remarks
                        )->first();
                        $draft_items->quantity = json_encode($this->machines[$key]['quantity']);
                        $draft_items->total_quantity = $this->machines[$key]['total_quantity'];
                        $draft_items->estimated_budget = $this->machines[$key]['estimated_budget'];
                        $draft_items->save();
                        break;
                    } else {
                        $existingDraftItem = $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->where(
                            'supplemental_quarter_id',
                            $this->supplementalQuarterId
                        )->first()->fundDrafts->first()->draft_items->where(
                            'particular_id',
                            $this->machine_particular_id
                        )->where('uom', $this->machine_uom)->where(
                            'remarks',
                            $this->machine_remarks
                        )->first();
                        if (!$existingDraftItem) {
                            $this->machines[] = [
                                'budget_category_id' => $this->machine_category_attr->categoryItems->budget_category_id,
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

                            FundDraftItem::create(
                                [
                                    'fund_draft_id' => $this->record->fundAllocations->where(
                                        'wpf_type_id',
                                        $this->wfp_param
                                    )->where(
                                        'supplemental_quarter_id',
                                        $this->supplementalQuarterId
                                    )->first()->fundDrafts->first()->id,
                                    'budget_category_id' => $this->machine_category_attr->categoryItems->budget_category_id,
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
                                    'quantity' => json_encode($this->machine_quantity),
                                    'total_quantity' => $this->machine_total_quantity,
                                    'uom' => $this->machine_uom,
                                    'estimated_budget' => $intEstimatedBudget,
                                    'remarks' => $this->machine_remarks,
                                ]
                            );
                            break;
                        }
                    }
                }
            } else {

                $this->machines[] = [
                    'budget_category_id' => $this->machine_category_attr->categoryItems->budget_category_id,
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
                if ($this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->first()->fundDrafts()->exists()) {
                    $draft_items = FundDraftItem::create(
                        [
                            'fund_draft_id' => $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $this->wfp_param
                            )->where(
                                'supplemental_quarter_id',
                                $this->supplementalQuarterId
                            )->first()->fundDrafts->first()->id,
                            'budget_category_id' => $this->machine_category_attr->categoryItems->budget_category_id,
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
                            'quantity' => json_encode($this->machine_quantity),
                            'total_quantity' => $this->machine_total_quantity,
                            'uom' => $this->machine_uom,
                            'estimated_budget' => $intEstimatedBudget,
                            'remarks' => $this->machine_remarks,
                        ]
                    );
                } else {
                    $draft = FundDraft::create([
                        'fund_allocation_id' => $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->where(
                            'supplemental_quarter_id',
                            $this->supplementalQuarterId
                        )->first()->id,
                    ]);

                    $draft_items = FundDraftItem::create(
                        [
                            'fund_draft_id' => $draft->id,
                            'budget_category_id' => $this->machine_category_attr->categoryItems->budget_category_id,
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
                            'quantity' => json_encode($this->machine_quantity),
                            'total_quantity' => $this->machine_total_quantity,
                            'uom' => $this->machine_uom,
                            'estimated_budget' => $intEstimatedBudget,
                            'remarks' => $this->machine_remarks,
                        ]
                    );
                }
            }


            if ($this->wfp_fund->id === 2 || $this->wfp_fund->id === 4 || $this->wfp_fund->id === 5 || $this->wfp_fund->id === 6 || $this->wfp_fund->id === 7) {
                $categoryGroupId = $this->machine_category_attr->categoryGroups->id;
                $found = false;

                foreach ($this->current_balance as $key => $balance) {
                    if ($balance && $balance['category_group_id'] == $categoryGroupId) {
                        // $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                        $found = true;
                        // if(!$this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)->first()->fundDrafts()->exists())
                        // {
                        if ($this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->where(
                            'supplemental_quarter_id',
                            $this->supplementalQuarterId
                        )->first()->fundDrafts()->exists()) {
                            $draft_id = $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $this->wfp_param
                            )->where(
                                'supplemental_quarter_id',
                                $this->supplementalQuarterId
                            )->first()->fundDrafts()->first()->id;
                            $draft_amounts = FundDraftAmount::where(
                                'fund_draft_id',
                                $draft_id
                            )->where('category_group_id', $categoryGroupId)->first();
                            $draft_amounts->current_total = $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                            $draft_amounts->balance = $this->current_balance[$key]['balance'];
                            $draft_amounts->save();
                        } else {
                            $draft_amounts = FundDraftAmount::where('category_group_id', $categoryGroupId)->first();
                            $draft_amounts->current_total = $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                            $draft_amounts->balance = $this->current_balance[$key]['balance'];
                            $draft_amounts->save();
                        }
                        // }

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
                    // if(!$this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)->first()->fundDrafts()->first()->draft_amounts()->exists())
                    // {
                    FundDraftAmount::create([
                        'fund_draft_id' => $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->where(
                            'supplemental_quarter_id',
                            $this->supplementalQuarterId
                        )->first()->fundDrafts->first()->id,
                        'category_group_id' => $categoryGroupId,
                        'category_group' => $this->machine_category_attr->categoryGroups->name,
                        'initial_amount' => 0,
                        'current_total' => $intEstimatedBudget,
                        'balance' => $intEstimatedBudget,
                    ]);
                    //}
                }
            } else {
                //add current_total to current balance from estimated budget
                foreach ($this->current_balance as $key => $balance) {
                    if ($balance && $balance['category_group_id'] == $this->machine_category_attr->categoryGroups->id) {
                        $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                        $this->current_balance[$key]['balance'] -= $intEstimatedBudget;
                    }
                }

                if (!$this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->first()->fundDrafts()->first()->draft_amounts()->exists()) {
                    foreach ($this->current_balance as $item) {
                        $draft_amounts = FundDraftAmount::create([
                            'fund_draft_id' => $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $this->wfp_param
                            )->where(
                                'supplemental_quarter_id',
                                $this->supplementalQuarterId
                            )->first()->fundDrafts->first()->id,
                            'category_group_id' => $item['category_group_id'],
                            'category_group' => $item['category_group'],
                            'initial_amount' => $item['initial_amount'],
                            'current_total' => $item['current_total'],
                            'balance' => $item['initial_amount'],
                        ]);
                    }
                } else {
                    foreach ($this->current_balance as $item) {
                        if ($this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->where(
                            'supplemental_quarter_id',
                            $this->supplementalQuarterId
                        )->first()->fundDrafts->first()->draft_amounts()->exists()) {
                            $draft_amounts = FundDraftAmount::create([
                                'fund_draft_id' => $this->record->fundAllocations->where(
                                    'wpf_type_id',
                                    $this->wfp_param
                                )->where(
                                    'supplemental_quarter_id',
                                    $this->supplementalQuarterId
                                )->first()->fundDrafts->first()->id,
                                'category_group_id' => $item['category_group_id'],
                                'category_group' => $item['category_group'],
                                'initial_amount' => $item['initial_amount'],
                                'current_total' => $item['current_total'],
                                'balance' => $item['initial_amount'],
                            ]);
                        } else {
                            $draft_amounts = $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $this->wfp_param
                            )->where(
                                'supplemental_quarter_id',
                                $this->supplementalQuarterId
                            )->first()->fundDrafts->first()->draft_amounts->where(
                                'category_group_id',
                                $item['category_group_id']
                            )->first();
                            $draft_amounts->current_total = $item['current_total'];
                            $draft_amounts->balance = $item['balance'];
                            $draft_amounts->save();
                        }
                    }
                }
            }
        } else {
            $is_valid_category_group = $this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)
                ->pluck('category_group_id')
                ->contains($this->machine_category_attr->categoryGroups->id);

            if ($is_valid_category_group) {
                $fund_allocation = $this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)
                    ->where('category_group_id', $this->machine_category_attr->categoryGroups->id)
                    ->first();

                if ($fund_allocation && $fund_allocation->initial_amount == 0) {
                    $this->dialog()->error(
                        $title = 'Operation Failed',
                        $description = 'You don\'t have fund allocation for this title group.',
                    );
                    return;
                }
            }

            $intEstimatedBudget = (float) str_replace(',', '', $this->machine_estimated_budget);
            //$draft_id = $this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)->first()->fundDrafts()->first()->id;
            if ($this->machines != null) {
                foreach ($this->machines as $key => $machine) {
                    if ($machine['particular_id'] == $this->machine_particular_id && $machine['uom'] == $this->machine_uom && $machine['remarks'] == $this->machine_remarks) {
                        $this->machines[$key]['quantity'] = $this->machines[$key]['quantity'] += $this->machine_quantity;
                        $this->machines[$key]['total_quantity'] = $this->machines[$key]['total_quantity'] += $this->machine_total_quantity;
                        $this->machines[$key]['estimated_budget'] = $this->machines[$key]['estimated_budget'] += $intEstimatedBudget;
                        $this->machines[$key]['quantity'] = array_map(function ($a, $b) {
                            return $a + $b;
                        }, $this->machines[$key]['quantity'], $this->machine_quantity);

                        $draft_items = $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->first()->fundDrafts->first()->draft_items->where(
                            'particular_id',
                            $this->machine_particular_id
                        )->where('uom', $this->machine_uom)->where(
                            'remarks',
                            $this->machine_remarks
                        )->first();
                        $draft_items->quantity = json_encode($this->machines[$key]['quantity']);
                        $draft_items->total_quantity = $this->machines[$key]['total_quantity'];
                        $draft_items->estimated_budget = $this->machines[$key]['estimated_budget'];
                        $draft_items->save();
                        break;
                    } else {
                        $existingDraftItem = $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->first()->fundDrafts->first()->draft_items->where(
                            'particular_id',
                            $this->machine_particular_id
                        )->where('uom', $this->machine_uom)->where(
                            'remarks',
                            $this->machine_remarks
                        )->first();
                        if (!$existingDraftItem) {
                            $this->machines[] = [
                                'budget_category_id' => $this->machine_category_attr->categoryItems->budget_category_id,
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

                            FundDraftItem::create(
                                [
                                    'fund_draft_id' => $this->record->fundAllocations->where(
                                        'wpf_type_id',
                                        $this->wfp_param
                                    )->first()->fundDrafts->first()->id,
                                    'budget_category_id' => $this->machine_category_attr->categoryItems->budget_category_id,
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
                                    'quantity' => json_encode($this->machine_quantity),
                                    'total_quantity' => $this->machine_total_quantity,
                                    'uom' => $this->machine_uom,
                                    'estimated_budget' => $intEstimatedBudget,
                                    'remarks' => $this->machine_remarks,
                                ]
                            );
                            break;
                        }
                    }
                }
            } else {
                $this->machines[] = [
                    'budget_category_id' => $this->machine_category_attr->categoryItems->budget_category_id,
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
                if ($this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->first()->fundDrafts()->exists()) {
                    $draft_items = FundDraftItem::create(
                        [
                            'fund_draft_id' => $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $this->wfp_param
                            )->first()->fundDrafts->first()->id,
                            'budget_category_id' => $this->machine_category_attr->categoryItems->budget_category_id,
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
                            'quantity' => json_encode($this->machine_quantity),
                            'total_quantity' => $this->machine_total_quantity,
                            'uom' => $this->machine_uom,
                            'estimated_budget' => $intEstimatedBudget,
                            'remarks' => $this->machine_remarks,
                        ]
                    );
                } else {
                    $draft = FundDraft::create([
                        'fund_allocation_id' => $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->first()->id,
                    ]);

                    $draft_items = FundDraftItem::create(
                        [
                            'fund_draft_id' => $draft->id,
                            'budget_category_id' => $this->machine_category_attr->categoryItems->budget_category_id,
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
                            'quantity' => json_encode($this->machine_quantity),
                            'total_quantity' => $this->machine_total_quantity,
                            'uom' => $this->machine_uom,
                            'estimated_budget' => $intEstimatedBudget,
                            'remarks' => $this->machine_remarks,
                        ]
                    );
                }
            }


            if ($this->wfp_fund->id === 2 || $this->wfp_fund->id === 4 || $this->wfp_fund->id === 5 || $this->wfp_fund->id === 6 || $this->wfp_fund->id === 7) {
                $categoryGroupId = $this->machine_category_attr->categoryGroups->id;
                $found = false;

                foreach ($this->current_balance as $key => $balance) {
                    if ($balance && $balance['category_group_id'] == $categoryGroupId) {
                        // $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                        $found = true;
                        // if(!$this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)->first()->fundDrafts()->exists())
                        // {
                        $draft_amounts = FundDraftAmount::where('category_group_id', $categoryGroupId)->first();

                        $draft_amounts->current_total = $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                        $draft_amounts->balance = $this->current_balance[$key]['balance'];
                        $draft_amounts->save();
                        // }

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
                    // if(!$this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)->first()->fundDrafts()->first()->draft_amounts()->exists())
                    // {
                    FundDraftAmount::create([
                        'fund_draft_id' => $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->first()->fundDrafts->first()->id,
                        'category_group_id' => $categoryGroupId,
                        'category_group' => $this->machine_category_attr->categoryGroups->name,
                        'initial_amount' => 0,
                        'current_total' => $intEstimatedBudget,
                        'balance' => $intEstimatedBudget,
                    ]);
                    //}
                }
            } else {
                //add current_total to current balance from estimated budget
                foreach ($this->current_balance as $key => $balance) {
                    if ($balance && $balance['category_group_id'] == $this->machine_category_attr->categoryGroups->id) {
                        $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                        $this->current_balance[$key]['balance'] -= $intEstimatedBudget;
                    }
                }

                if (!$this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->first()->fundDrafts()->first()->draft_amounts()->exists()) {
                    foreach ($this->current_balance as $item) {
                        $draft_amounts = FundDraftAmount::create([
                            'fund_draft_id' => $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $this->wfp_param
                            )->first()->fundDrafts->first()->id,
                            'category_group_id' => $item['category_group_id'],
                            'category_group' => $item['category_group'],
                            'initial_amount' => $item['initial_amount'],
                            'current_total' => $item['current_total'],
                            'balance' => $item['initial_amount'],
                        ]);
                    }
                } else {
                    foreach ($this->current_balance as $item) {
                        if ($this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->first()->fundDrafts->first()->draft_amounts()->exists()) {
                            $draft_amounts = FundDraftAmount::create([
                                'fund_draft_id' => $this->record->fundAllocations->where(
                                    'wpf_type_id',
                                    $this->wfp_param
                                )->first()->fundDrafts->first()->id,
                                'category_group_id' => $item['category_group_id'],
                                'category_group' => $item['category_group'],
                                'initial_amount' => $item['initial_amount'],
                                'current_total' => $item['current_total'],
                                'balance' => $item['initial_amount'],
                            ]);
                        } else {
                            $draft_amounts = $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $this->wfp_param
                            )->first()->fundDrafts->first()->draft_amounts->where(
                                'category_group_id',
                                $item['category_group_id']
                            )->first();
                            $draft_amounts->current_total = $item['current_total'];
                            $draft_amounts->balance = $item['balance'];
                            $draft_amounts->save();
                        }
                    }
                }
            }
        }


        $this->addDraft();
        switch ($this->global_index) {
            case 2:
                $this->clearSupplies();
                break;
            case 3:
                $this->clearMooe();
                break;
            case 4:
                $this->clearTrainings();
                break;
            case 5:
                $this->clearMachine();
                break;
            case 6:
                $this->clearBuilding();
                break;
            case 7:
                $this->clearPs();
                break;
        }
    }

    public function showMachineDetails()
    {
        $this->machineDetailModal = true;
    }

    public function clearMachine()
    {
        $this->supplies_particular = null;
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
        if ($this->building_particular_id != null) {
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
        } else {
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
        $building = $this->building_category_attr;
     $budget_category_id = $this->budgetCategoryTabIds[$this->global_index];

        switch ($budget_category_id) {
            case 1:
                $this->calculateSuppliesTotalQuantity();
                $cost_per_unit = $this->building_cost_per_unit == null ? 0 : $this->building_cost_per_unit;
                $this->supplies_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->building_quantity ?? [0]));
                $this->supplies_estimated_budget = number_format(
                    $this->supplies_total_quantity * $cost_per_unit,
                    2
                );
                break;
            case 2:
                $this->calculateMooeTotalQuantity();
                $cost_per_unit = $this->building_cost_per_unit == null ? 0 : $this->building_cost_per_unit;
                $this->mooe_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->building_quantity ?? [0]));
                $this->mooe_estimated_budget = number_format($this->mooe_total_quantity * $cost_per_unit, 2);
                break;
            case 3:
                $this->calculateTrainingTotalQuantity();
                $cost_per_unit = $this->building_cost_per_unit == null ? 0 : $this->building_cost_per_unit;
                $this->training_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->building_quantity ?? [0]));
                $this->training_estimated_budget = number_format(
                    $this->training_total_quantity * $cost_per_unit,
                    2
                );
                break;
            case 4:
                $this->calculateMachineTotalQuantity();
                $cost_per_unit = $this->building_cost_per_unit == null ? 0 : $this->building_cost_per_unit;
                $this->machine_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->building_quantity ?? [0]));
                $this->machine_estimated_budget = number_format($this->machine_total_quantity * $cost_per_unit, 2);
                break;
            case 5:
                $this->calculateBuildingTotalQuantity();
                break;
            case 6:
                $this->calculatePsTotalQuantity();
                $cost_per_unit = $this->building_cost_per_unit == null ? 0 : $this->building_cost_per_unit;
                $this->ps_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->building_quantity ?? [0]));
                $this->ps_estimated_budget = number_format($this->ps_total_quantity * $cost_per_unit, 2);
                break;
        }
    }

    public function updatedBuildingCostPerUnit()
    {
        $building = $this->building_category_attr;
      $budget_category_id = $this->budgetCategoryTabIds[$this->global_index];

        switch ($budget_category_id) {
            case 1:
                $this->calculateSuppliesTotalQuantity();
                $cost_per_unit = $this->building_cost_per_unit == null ? 0 : $this->building_cost_per_unit;
                $this->supplies_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->building_quantity ?? [0]));
                $this->supplies_estimated_budget = number_format(
                    $this->supplies_total_quantity * $cost_per_unit,
                    2
                );
                break;
            case 2:
                $this->calculateMooeTotalQuantity();
                $cost_per_unit = $this->building_cost_per_unit == null ? 0 : $this->building_cost_per_unit;
                $this->mooe_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->building_quantity ?? [0]));
                $this->mooe_estimated_budget = number_format($this->mooe_total_quantity * $cost_per_unit, 2);
                break;
            case 3:
                $this->calculateTrainingTotalQuantity();
                $cost_per_unit = $this->building_cost_per_unit == null ? 0 : $this->building_cost_per_unit;
                $this->training_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->building_quantity ?? [0]));
                $this->training_estimated_budget = number_format(
                    $this->training_total_quantity * $cost_per_unit,
                    2
                );
                break;
            case 4:
                $this->calculateMachineTotalQuantity();
                $cost_per_unit = $this->building_cost_per_unit == null ? 0 : $this->building_cost_per_unit;
                $this->machine_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->building_quantity ?? [0]));
                $this->machine_estimated_budget = number_format($this->machine_total_quantity * $cost_per_unit, 2);
                break;
            case 5:
                $this->calculateBuildingTotalQuantity();
                break;
            case 6:
                $this->calculatePsTotalQuantity();
                $cost_per_unit = $this->building_cost_per_unit == null ? 0 : $this->building_cost_per_unit;
                $this->ps_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->building_quantity ?? [0]));
                $this->ps_estimated_budget = number_format($this->ps_total_quantity * $cost_per_unit, 2);
                break;
        }
    }

    public function calculateBuildingTotalQuantity()
    {
        $cost_per_unit = $this->building_cost_per_unit == null ? 0 : $this->building_cost_per_unit;
        $this->building_total_quantity = array_sum(array_map(function ($quantity) {
            return is_numeric($quantity) ? (int) $quantity : 0;
        }, $this->building_quantity ?? [0]));
        $this->building_estimated_budget = number_format($this->building_total_quantity * $cost_per_unit, 2);
    }

    public function updatedBuildingIsRemarks()
    {
        if ($this->building_is_remarks === false) {
            $this->building_remarks = null;
        }
    }

    public function addBuilding()
    {
        //validate all step 2
        $this->validate(
            [
                'building_particular_id' => 'required',
                'building_uom' => 'required',
                // 'building_cost_per_unit' => 'required|gt:0',
                'building_total_quantity' => 'gt:0',
            ],
            [
                'building_particular_id.required' => 'Particulars is required',
                'building_uom.required' => 'UOM is required',
                'building_cost_per_unit.required' => 'Cost per unit is required',
                // 'building_cost_per_unit.gt' => 'Cost per unit must be greater than 0',
                'building_total_quantity.gt' => 'Total quantity must be greater than 0',
            ]
        );

        if ($this->is_supplemental) {
            $is_valid_category_group = $this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)
                ->where('supplemental_quarter_id', $this->supplementalQuarterId)
                ->pluck('category_group_id')
                ->contains($this->building_category_attr->categoryGroups->id);

            if ($is_valid_category_group) {
                $fund_allocation = $this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)
                    ->where('supplemental_quarter_id', $this->supplementalQuarterId)
                    ->where('category_group_id', $this->building_category_attr->categoryGroups->id)
                    ->first();

                if ($fund_allocation && $fund_allocation->initial_amount == 0) {
                    $this->dialog()->error(
                        $title = 'Operation Failed',
                        $description = 'You don\'t have fund allocation for this title group.',
                    );
                    return;
                }
            }

            $intEstimatedBudget = (float) str_replace(',', '', $this->building_estimated_budget);
            if ($this->record->fundAllocations->where(
                'wpf_type_id',
                $this->wfp_param
            )->where(
                'supplemental_quarter_id',
                $this->supplementalQuarterId
            )->first()->fundDrafts()->exists()) {
                $draft_id = $this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->first()->fundDrafts()->first()->id;
            }
            //$draft_id = $this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)->first()->fundDrafts()->first()->id;
            if ($this->buildings != null) {
                foreach ($this->buildings as $key => $building) {
                    if ($building['particular_id'] == $this->building_particular_id && $building['uom'] == $this->building_uom && $building['remarks'] == $this->building_remarks) {
                        $this->buildings[$key]['quantity'] = $this->buildings[$key]['quantity'] += $this->building_quantity;
                        $this->buildings[$key]['total_quantity'] = $this->buildings[$key]['total_quantity'] += $this->building_total_quantity;
                        $this->buildings[$key]['estimated_budget'] = $this->buildings[$key]['estimated_budget'] += $intEstimatedBudget;
                        $this->buildings[$key]['quantity'] = array_map(function ($a, $b) {
                            return $a + $b;
                        }, $this->buildings[$key]['quantity'], $this->building_quantity);

                        $draft_items = $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->where(
                            'supplemental_quarter_id',
                            $this->supplementalQuarterId
                        )->first()->fundDrafts->first()->draft_items->where(
                            'particular_id',
                            $this->building_particular_id
                        )->where('uom', $this->building_uom)->where(
                            'remarks',
                            $this->building_remarks
                        )->first();
                        $draft_items->quantity = json_encode($this->buildings[$key]['quantity']);
                        $draft_items->total_quantity = $this->buildings[$key]['total_quantity'];
                        $draft_items->estimated_budget = $this->buildings[$key]['estimated_budget'];
                        $draft_items->save();
                        break;
                    } else {
                        $existingDraftItem = $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->where(
                            'supplemental_quarter_id',
                            $this->supplementalQuarterId
                        )->first()->fundDrafts->first()->draft_items->where(
                            'particular_id',
                            $this->building_particular_id
                        )->where('uom', $this->building_uom)->where(
                            'remarks',
                            $this->building_remarks
                        )->first();
                        if (!$existingDraftItem) {
                            $this->buildings[] = [
                                'budget_category_id' => $this->building_category_attr->categoryItems->budget_category_id,
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

                            FundDraftItem::create(
                                [
                                    'fund_draft_id' => $this->record->fundAllocations->where(
                                        'wpf_type_id',
                                        $this->wfp_param
                                    )->where(
                                        'supplemental_quarter_id',
                                        $this->supplementalQuarterId
                                    )->first()->fundDrafts->first()->id,
                                    'budget_category_id' => $this->building_category_attr->categoryItems->budget_category_id,
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
                                    'quantity' => json_encode($this->building_quantity),
                                    'total_quantity' => $this->building_total_quantity,
                                    'uom' => $this->building_uom,
                                    'estimated_budget' => $intEstimatedBudget,
                                    'remarks' => $this->building_remarks,
                                ]
                            );
                            break;
                        }
                    }
                }
            } else {
                $this->buildings[] = [
                    'budget_category_id' => $this->building_category_attr->categoryItems->budget_category_id,
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
                if ($this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->first()->fundDrafts()->exists()) {
                    $draft_items = FundDraftItem::create(
                        [
                            'fund_draft_id' => $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $this->wfp_param
                            )->where(
                                'supplemental_quarter_id',
                                $this->supplementalQuarterId
                            )->first()->fundDrafts->first()->id,
                            'budget_category_id' => $this->building_category_attr->categoryItems->budget_category_id,
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
                            'quantity' => json_encode($this->building_quantity),
                            'total_quantity' => $this->building_total_quantity,
                            'uom' => $this->building_uom,
                            'estimated_budget' => $intEstimatedBudget,
                            'remarks' => $this->building_remarks,
                        ]
                    );
                } else {
                    $draft = FundDraft::create([
                        'fund_allocation_id' => $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->where(
                            'supplemental_quarter_id',
                            $this->supplementalQuarterId
                        )->first()->id,
                    ]);

                    $draft_items = FundDraftItem::create(
                        [
                            'fund_draft_id' => $draft->id,
                            'budget_category_id' => $this->building_category_attr->categoryItems->budget_category_id,
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
                            'quantity' => json_encode($this->building_quantity),
                            'total_quantity' => $this->building_total_quantity,
                            'uom' => $this->building_uom,
                            'estimated_budget' => $intEstimatedBudget,
                            'remarks' => $this->building_remarks,
                        ]
                    );
                }
            }

            if ($this->wfp_fund->id === 2 || $this->wfp_fund->id === 4 || $this->wfp_fund->id === 5 || $this->wfp_fund->id === 6 || $this->wfp_fund->id === 7) {
                $categoryGroupId = $this->building_category_attr->categoryGroups->id;
                $found = false;

                foreach ($this->current_balance as $key => $balance) {
                    if ($balance && $balance['category_group_id'] == $categoryGroupId) {
                        // $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                        $found = true;
                        //if(!$this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)->where('supplemental_quarter_id',$this->supplementalQuarterId)->first()->fundDrafts()->exists())
                        // {
                        if ($this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->where(
                            'supplemental_quarter_id',
                            $this->supplementalQuarterId
                        )->first()->fundDrafts()->exists()) {
                            $draft_id = $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $this->wfp_param
                            )->where(
                                'supplemental_quarter_id',
                                $this->supplementalQuarterId
                            )->first()->fundDrafts()->first()->id;
                            $draft_amounts = FundDraftAmount::where(
                                'fund_draft_id',
                                $draft_id
                            )->where('category_group_id', $categoryGroupId)->first();
                            $draft_amounts->current_total = $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                            $draft_amounts->balance = $this->current_balance[$key]['balance'];
                            $draft_amounts->save();
                        } else {
                            $draft_amounts = FundDraftAmount::where('category_group_id', $categoryGroupId)->first();
                            $draft_amounts->current_total = $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                            $draft_amounts->balance = $this->current_balance[$key]['balance'];
                            $draft_amounts->save();
                        }
                        //}

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
                    //if(!$this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)->where('supplemental_quarter_id',$this->supplementalQuarterId)->first()->fundDrafts()->first()->draft_amounts()->exists())
                    //{
                    FundDraftAmount::create([
                        'fund_draft_id' => $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->where(
                            'supplemental_quarter_id',
                            $this->supplementalQuarterId
                        )->first()->fundDrafts->first()->id,
                        'category_group_id' => $categoryGroupId,
                        'category_group' => $this->building_category_attr->categoryGroups->name,
                        'initial_amount' => 0,
                        'current_total' => $intEstimatedBudget,
                        'balance' => $intEstimatedBudget,
                    ]);
                    //}
                }
            } else {
                //add current_total to current balance from estimated budget
                foreach ($this->current_balance as $key => $balance) {
                    if ($balance && $balance['category_group_id'] == $this->building_category_attr->categoryGroups->id) {
                        $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                        $this->current_balance[$key]['balance'] -= $intEstimatedBudget;
                    }
                }


                if (!$this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->first()->fundDrafts()->first()->draft_amounts()->exists()) {
                    foreach ($this->current_balance as $item) {
                        $draft_amounts = FundDraftAmount::create([
                            'fund_draft_id' => $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $this->wfp_param
                            )->where(
                                'supplemental_quarter_id',
                                $this->supplementalQuarterId
                            )->first()->fundDrafts->first()->id,
                            'category_group_id' => $item['category_group_id'],
                            'category_group' => $item['category_group'],
                            'initial_amount' => $item['initial_amount'],
                            'current_total' => $item['current_total'],
                            'balance' => $item['initial_amount'],
                        ]);
                    }
                } else {
                    foreach ($this->current_balance as $item) {
                        if ($this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->where(
                            'supplemental_quarter_id',
                            $this->supplementalQuarterId
                        )->first()->fundDrafts->first()->draft_amounts()->exists()) {
                            $draft_amounts = FundDraftAmount::create([
                                'fund_draft_id' => $this->record->fundAllocations->where(
                                    'wpf_type_id',
                                    $this->wfp_param
                                )->where(
                                    'supplemental_quarter_id',
                                    $this->supplementalQuarterId
                                )->first()->fundDrafts->first()->id,
                                'category_group_id' => $item['category_group_id'],
                                'category_group' => $item['category_group'],
                                'initial_amount' => $item['initial_amount'],
                                'current_total' => $item['current_total'],
                                'balance' => $item['initial_amount'],
                            ]);
                        } else {
                            $draft_amounts = $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $this->wfp_param
                            )->where(
                                'supplemental_quarter_id',
                                $this->supplementalQuarterId
                            )->first()->fundDrafts->first()->draft_amounts->where(
                                'category_group_id',
                                $item['category_group_id']
                            )->first();
                            $draft_amounts->current_total = $item['current_total'] ?? 0;
                            $draft_amounts->balance = $item['balance'] ?? 0;
                            $draft_amounts->save();
                        }
                    }
                }
            }
        } else {
            $is_valid_category_group = $this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)
                ->pluck('category_group_id')
                ->contains($this->building_category_attr->categoryGroups->id);

            if ($is_valid_category_group) {
                $fund_allocation = $this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)
                    ->where('category_group_id', $this->building_category_attr->categoryGroups->id)
                    ->first();

                if ($fund_allocation && $fund_allocation->initial_amount == 0) {
                    $this->dialog()->error(
                        $title = 'Operation Failed',
                        $description = 'You don\'t have fund allocation for this title group.',
                    );
                    return;
                }
            }

            $intEstimatedBudget = (float) str_replace(',', '', $this->building_estimated_budget);
            //$draft_id = $this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)->first()->fundDrafts()->first()->id;
            if ($this->buildings != null) {
                foreach ($this->buildings as $key => $building) {
                    if ($building['particular_id'] == $this->building_particular_id && $building['uom'] == $this->building_uom && $building['remarks'] == $this->building_remarks) {
                        $this->buildings[$key]['quantity'] = $this->buildings[$key]['quantity'] += $this->building_quantity;
                        $this->buildings[$key]['total_quantity'] = $this->buildings[$key]['total_quantity'] += $this->building_total_quantity;
                        $this->buildings[$key]['estimated_budget'] = $this->buildings[$key]['estimated_budget'] += $intEstimatedBudget;
                        $this->buildings[$key]['quantity'] = array_map(function ($a, $b) {
                            return $a + $b;
                        }, $this->buildings[$key]['quantity'], $this->building_quantity);

                        $draft_items = $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->first()->fundDrafts->first()->draft_items->where(
                            'particular_id',
                            $this->building_particular_id
                        )->where('uom', $this->building_uom)->where(
                            'remarks',
                            $this->building_remarks
                        )->first();
                        $draft_items->quantity = json_encode($this->buildings[$key]['quantity']);
                        $draft_items->total_quantity = $this->buildings[$key]['total_quantity'];
                        $draft_items->estimated_budget = $this->buildings[$key]['estimated_budget'];
                        $draft_items->save();
                        break;
                    } else {
                        $existingDraftItem = $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->first()->fundDrafts->first()->draft_items->where(
                            'particular_id',
                            $this->building_particular_id
                        )->where('uom', $this->building_uom)->where(
                            'remarks',
                            $this->building_remarks
                        )->first();
                        if (!$existingDraftItem) {
                            $this->buildings[] = [
                                'budget_category_id' => $this->building_category_attr->categoryItems->budget_category_id,
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

                            FundDraftItem::create(
                                [
                                    'fund_draft_id' => $this->record->fundAllocations->where(
                                        'wpf_type_id',
                                        $this->wfp_param
                                    )->first()->fundDrafts->first()->id,
                                    'budget_category_id' => $this->building_category_attr->categoryItems->budget_category_id,
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
                                    'quantity' => json_encode($this->building_quantity),
                                    'total_quantity' => $this->building_total_quantity,
                                    'uom' => $this->building_uom,
                                    'estimated_budget' => $intEstimatedBudget,
                                    'remarks' => $this->building_remarks,
                                ]
                            );
                            break;
                        }
                    }
                }
            } else {
                $this->buildings[] = [
                    'budget_category_id' => $this->building_category_attr->categoryItems->budget_category_id,
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
                if ($this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->first()->fundDrafts()->exists()) {
                    $draft_items = FundDraftItem::create(
                        [
                            'fund_draft_id' => $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $this->wfp_param
                            )->first()->fundDrafts->first()->id,
                            'budget_category_id' => $this->building_category_attr->categoryItems->budget_category_id,
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
                            'quantity' => json_encode($this->building_quantity),
                            'total_quantity' => $this->building_total_quantity,
                            'uom' => $this->building_uom,
                            'estimated_budget' => $intEstimatedBudget,
                            'remarks' => $this->building_remarks,
                        ]
                    );
                } else {
                    $draft = FundDraft::create([
                        'fund_allocation_id' => $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->first()->id,
                    ]);

                    $draft_items = FundDraftItem::create(
                        [
                            'fund_draft_id' => $draft->id,
                            'budget_category_id' => $this->building_category_attr->categoryItems->budget_category_id,
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
                            'quantity' => json_encode($this->building_quantity),
                            'total_quantity' => $this->building_total_quantity,
                            'uom' => $this->building_uom,
                            'estimated_budget' => $intEstimatedBudget,
                            'remarks' => $this->building_remarks,
                        ]
                    );
                }
            }

            if ($this->wfp_fund->id === 2 || $this->wfp_fund->id === 4 || $this->wfp_fund->id === 5 || $this->wfp_fund->id === 6 || $this->wfp_fund->id === 7) {
                $categoryGroupId = $this->building_category_attr->categoryGroups->id;
                $found = false;

                foreach ($this->current_balance as $key => $balance) {
                    if ($balance && $balance['category_group_id'] == $categoryGroupId) {
                        // $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                        $found = true;
                        //if(!$this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)->first()->fundDrafts()->exists())
                        //{
                        $draft_amounts = FundDraftAmount::where('category_group_id', $categoryGroupId)->first();

                        $draft_amounts->current_total = $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                        $draft_amounts->balance = $this->current_balance[$key]['balance'];
                        $draft_amounts->save();
                        //}

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
                    //if(!$this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)->first()->fundDrafts()->first()->draft_amounts()->exists())
                    //{
                    FundDraftAmount::create([
                        'fund_draft_id' => $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->first()->fundDrafts->first()->id,
                        'category_group_id' => $categoryGroupId,
                        'category_group' => $this->building_category_attr->categoryGroups->name,
                        'initial_amount' => 0,
                        'current_total' => $intEstimatedBudget,
                        'balance' => $intEstimatedBudget,
                    ]);
                    //}
                }
            } else {
                //add current_total to current balance from estimated budget
                foreach ($this->current_balance as $key => $balance) {
                    if ($balance && $balance['category_group_id'] == $this->building_category_attr->categoryGroups->id) {
                        $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                        $this->current_balance[$key]['balance'] -= $intEstimatedBudget;
                    }
                }


                if (!$this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->first()->fundDrafts()->first()->draft_amounts()->exists()) {
                    foreach ($this->current_balance as $item) {
                        $draft_amounts = FundDraftAmount::create([
                            'fund_draft_id' => $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $this->wfp_param
                            )->first()->fundDrafts->first()->id,
                            'category_group_id' => $item['category_group_id'],
                            'category_group' => $item['category_group'],
                            'initial_amount' => $item['initial_amount'],
                            'current_total' => $item['current_total'],
                            'balance' => $item['initial_amount'],
                        ]);
                    }
                } else {
                    foreach ($this->current_balance as $item) {
                        if ($this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->first()->fundDrafts->first()->draft_amounts()->exists()) {
                            $draft_amounts = FundDraftAmount::create([
                                'fund_draft_id' => $this->record->fundAllocations->where(
                                    'wpf_type_id',
                                    $this->wfp_param
                                )->first()->fundDrafts->first()->id,
                                'category_group_id' => $item['category_group_id'],
                                'category_group' => $item['category_group'],
                                'initial_amount' => $item['initial_amount'],
                                'current_total' => $item['current_total'],
                                'balance' => $item['initial_amount'],
                            ]);
                        } else {
                            $draft_amounts = $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $this->wfp_param
                            )->first()->fundDrafts->first()->draft_amounts->where(
                                'category_group_id',
                                $item['category_group_id']
                            )->first();
                            $draft_amounts->current_total = $item['current_total'];
                            $draft_amounts->balance = $item['balance'];
                            $draft_amounts->save();
                        }
                    }
                }
            }
        }

        $this->addDraft();
        switch ($this->global_index) {
            case 2:
                $this->clearSupplies();
                break;
            case 3:
                $this->clearMooe();
                break;
            case 4:
                $this->clearTrainings();
                break;
            case 5:
                $this->clearMachine();
                break;
            case 6:
                $this->clearBuilding();
                break;
            case 7:
                $this->clearPs();
                break;
        }
    }


    public function showBuildingDetails()
    {
        $this->buildingDetailModal = true;
    }

    public function clearBuilding()
    {
        $this->supplies_particular = null;
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

    public function updatedPsParticularId()
    {
        if ($this->ps_particular_id != null) {
            $this->ps_category_attr = Supply::find($this->ps_particular_id);
            $this->ps_specs = $this->ps_category_attr->specifications;
            $this->ps_code = $this->ps_category_attr->supply_code;
            $this->ps_uacs = $this->ps_category_attr->categoryItems->uacs_code;
            $this->ps_title_group = $this->ps_category_attr->categoryGroups->name;
            $this->ps_account_title = $this->ps_category_attr->categoryItems->name;
            $this->ps_ppmp = $this->ps_category_attr->is_ppmp;
            $this->ps_cost_per_unit = $this->ps_category_attr->unit_cost;
            $this->ps_quantity = array_fill(0, 12, 0);
            $this->calculatePsTotalQuantity();
        } else {
            $this->ps_category_attr = null;
            $this->ps_specs = null;
            $this->ps_code = null;
            $this->ps_uacs = null;
            $this->ps_title_group = null;
            $this->ps_account_title = null;
            $this->ps_ppmp = false;
            $this->ps_cost_per_unit = 0;
            $this->ps_total_quantity = 0;
            $this->ps_estimated_budget = 0;
            $this->ps_uom = null;
            $this->ps_quantity = array_fill(0, 12, 0);
        }
    }

    public function updatedPsQuantity()
    {
        $ps = $this->ps_category_attr;
       $budget_category_id = $this->budgetCategoryTabIds[$this->global_index];
        switch ($budget_category_id) {
            case 1:
                $this->calculateSuppliesTotalQuantity();
                $cost_per_unit = $this->ps_cost_per_unit == null ? 0 : $this->ps_cost_per_unit;
                $this->supplies_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->ps_quantity ?? [0]));
                $this->supplies_estimated_budget = number_format(
                    $this->supplies_total_quantity * $cost_per_unit,
                    2
                );
                break;
            case 2:
                $this->calculateMooeTotalQuantity();
                $cost_per_unit = $this->ps_cost_per_unit == null ? 0 : $this->ps_cost_per_unit;
                $this->mooe_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->ps_quantity ?? [0]));
                $this->mooe_estimated_budget = number_format($this->mooe_total_quantity * $cost_per_unit, 2);
                break;
            case 3:
                $this->calculateTrainingTotalQuantity();
                $cost_per_unit = $this->ps_cost_per_unit == null ? 0 : $this->ps_cost_per_unit;
                $this->training_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->ps_quantity ?? [0]));
                $this->training_estimated_budget = number_format(
                    $this->training_total_quantity * $cost_per_unit,
                    2
                );
                break;
            case 4:
                $this->calculateMachineTotalQuantity();
                $cost_per_unit = $this->ps_cost_per_unit == null ? 0 : $this->ps_cost_per_unit;
                $this->machine_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->ps_quantity ?? [0]));
                $this->machine_estimated_budget = number_format($this->machine_total_quantity * $cost_per_unit, 2);
                break;
            case 5:
                $this->calculateBuildingTotalQuantity();
                $cost_per_unit = $this->building_cost_per_unit == null ? 0 : $this->building_cost_per_unit;
                $this->building_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->building_quantity ?? [0]));
                $this->building_estimated_budget = number_format(
                    $this->building_total_quantity * $cost_per_unit,
                    2
                );
                break;
            case 6:
                $this->calculatePsTotalQuantity();
                break;
        }
    }

    public function updatedPsCostPerUnit()
    {
        $ps = $this->ps_category_attr;
       $budget_category_id = $this->budgetCategoryTabIds[$this->global_index];
        switch ($budget_category_id) {
            case 1:
                $this->calculateSuppliesTotalQuantity();
                $cost_per_unit = $this->ps_cost_per_unit == null ? 0 : $this->ps_cost_per_unit;
                $this->supplies_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->ps_quantity ?? [0]));
                $this->supplies_estimated_budget = number_format(
                    $this->supplies_total_quantity * $cost_per_unit,
                    2
                );
                break;
            case 2:
                $this->calculateMooeTotalQuantity();
                $cost_per_unit = $this->ps_cost_per_unit == null ? 0 : $this->ps_cost_per_unit;
                $this->mooe_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->ps_quantity ?? [0]));
                $this->mooe_estimated_budget = number_format($this->mooe_total_quantity * $cost_per_unit, 2);
                break;
            case 3:
                $this->calculateTrainingTotalQuantity();
                $cost_per_unit = $this->ps_cost_per_unit == null ? 0 : $this->ps_cost_per_unit;
                $this->training_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->ps_quantity ?? [0]));
                $this->training_estimated_budget = number_format(
                    $this->training_total_quantity * $cost_per_unit,
                    2
                );
                break;
            case 4:
                $this->calculateMachineTotalQuantity();
                $cost_per_unit = $this->ps_cost_per_unit == null ? 0 : $this->ps_cost_per_unit;
                $this->machine_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->ps_quantity ?? [0]));
                $this->machine_estimated_budget = number_format($this->machine_total_quantity * $cost_per_unit, 2);
                break;
            case 5:
                $this->calculateBuildingTotalQuantity();
                $cost_per_unit = $this->building_cost_per_unit == null ? 0 : $this->building_cost_per_unit;
                $this->building_total_quantity = array_sum(array_map(function ($quantity) {
                    return is_numeric($quantity) ? (int) $quantity : 0;
                }, $this->building_quantity ?? [0]));
                $this->building_estimated_budget = number_format(
                    $this->building_total_quantity * $cost_per_unit,
                    2
                );
                break;
            case 6:
                $this->calculatePsTotalQuantity();
                break;
        }
    }

    public function calculatePsTotalQuantity()
    {
        $cost_per_unit = $this->ps_cost_per_unit == null ? 0 : $this->ps_cost_per_unit;
        $this->ps_total_quantity = array_sum(array_map(function ($quantity) {
            return is_numeric($quantity) ? (int) $quantity : 0;
        }, $this->ps_quantity ?? [0]));
        $this->ps_estimated_budget = number_format($this->ps_total_quantity * $cost_per_unit, 2);
    }

    public function updatedPsIsRemarks()
    {
        if ($this->ps_is_remarks === false) {
            $this->ps_remarks = null;
        }
    }

    public function addPs()
    {
        //validate all step 2
        $this->validate(
            [
                'ps_particular_id' => 'required',
                'ps_uom' => 'required',
                // 'ps_cost_per_unit' => 'required|gt:0',
                'ps_total_quantity' => 'gt:0',
            ],
            [
                'ps_particular_id.required' => 'Particulars is required',
                'ps_uom.required' => 'UOM is required',
                'ps_cost_per_unit.required' => 'Cost per unit is required',
                // 'ps_cost_per_unit.gt' => 'Cost per unit must be greater than 0',
                'ps_total_quantity.gt' => 'Total quantity must be greater than 0',
            ]
        );


        if ($this->is_supplemental) {
            $is_valid_category_group = $this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)
                ->where('supplemental_quarter_id', $this->supplementalQuarterId)
                ->pluck('category_group_id')
                ->contains($this->ps_category_attr->categoryGroups->id);

            if ($is_valid_category_group) {
                $fund_allocation = $this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)
                    ->where('supplemental_quarter_id', $this->supplementalQuarterId)
                    ->where('category_group_id', $this->ps_category_attr->categoryGroups->id)
                    ->first();

                if ($fund_allocation && $fund_allocation->initial_amount == 0) {
                    $this->dialog()->error(
                        $title = 'Operation Failed',
                        $description = 'You don\'t have fund allocation for this title group.',
                    );
                    return;
                }
            }

            $intEstimatedBudget = (float) str_replace(',', '', $this->ps_estimated_budget);
            if ($this->record->fundAllocations->where(
                'wpf_type_id',
                $this->wfp_param
            )->where(
                'supplemental_quarter_id',
                $this->supplementalQuarterId
            )->first()->fundDrafts()->exists()) {
                $draft_id = $this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->first()->fundDrafts()->first()->id;
            }
            //$draft_id = $this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)->first()->fundDrafts()->first()->id;
            if ($this->ps != null) {
                foreach ($this->ps as $key => $ps) {
                    if ($ps['particular_id'] == $this->ps_particular_id && $ps['uom'] == $this->ps_uom && $ps['remarks'] == $this->ps_remarks) {
                        $this->ps[$key]['quantity'] = $this->ps[$key]['quantity'] += $this->ps_quantity;
                        $this->ps[$key]['total_quantity'] = $this->ps[$key]['total_quantity'] += $this->ps_total_quantity;
                        $this->ps[$key]['estimated_budget'] = $this->ps[$key]['estimated_budget'] += $intEstimatedBudget;
                        $this->ps[$key]['quantity'] = array_map(function ($a, $b) {
                            return $a + $b;
                        }, $this->ps[$key]['quantity'], $this->ps_quantity);

                        $draft_items = $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->where(
                            'supplemental_quarter_id',
                            $this->supplementalQuarterId
                        )->first()->fundDrafts->first()->draft_items->where(
                            'particular_id',
                            $this->ps_particular_id
                        )->where('uom', $this->ps_uom)->where(
                            'remarks',
                            $this->ps_remarks
                        )->first();
                        $draft_items->quantity = json_encode($this->ps[$key]['quantity']);
                        $draft_items->total_quantity = $this->ps[$key]['total_quantity'];
                        $draft_items->estimated_budget = $this->ps[$key]['estimated_budget'];
                        $draft_items->save();
                        break;
                    } else {
                        $existingDraftItem = $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->where(
                            'supplemental_quarter_id',
                            $this->supplementalQuarterId
                        )->first()->fundDrafts->first()->draft_items->where(
                            'particular_id',
                            $this->ps_particular_id
                        )->where('uom', $this->ps_uom)->where(
                            'remarks',
                            $this->ps_remarks
                        )->first();
                        if (!$existingDraftItem) {
                            $this->ps[] = [
                                'budget_category_id' => $this->ps_category_attr->categoryItems->budget_category_id,
                                'budget_category' => 'Professional Services',
                                'particular_id' => $this->ps_particular_id,
                                'particular' => $this->ps_category_attr->particulars,
                                'supply_code' => $this->ps_category_attr->supply_code,
                                'specifications' => $this->ps_category_attr->specifications,
                                'uacs' => $this->ps_uacs,
                                'title_group' => $this->ps_category_attr->categoryGroups->id,
                                'account_title_id' => $this->ps_category_attr->categoryItems->id,
                                'account_title' => $this->ps_category_attr->categoryItems->name,
                                'ppmp' => $this->ps_ppmp,
                                'cost_per_unit' => $this->ps_cost_per_unit,
                                'quantity' => $this->ps_quantity,
                                'total_quantity' => $this->ps_total_quantity,
                                'uom' => $this->ps_uom,
                                'estimated_budget' => $intEstimatedBudget,
                                'remarks' => $this->ps_remarks,
                            ];

                            FundDraftItem::create(
                                [
                                    'fund_draft_id' => $this->record->fundAllocations->where(
                                        'wpf_type_id',
                                        $this->wfp_param
                                    )->where(
                                        'supplemental_quarter_id',
                                        $this->supplementalQuarterId
                                    )->first()->fundDrafts->first()->id,
                                    'budget_category_id' => $this->ps_category_attr->categoryItems->budget_category_id,
                                    'budget_category' => 'Professional Services',
                                    'particular_id' => $this->ps_particular_id,
                                    'particular' => $this->ps_category_attr->particulars,
                                    'supply_code' => $this->ps_category_attr->supply_code,
                                    'specifications' => $this->ps_category_attr->specifications,
                                    'uacs' => $this->ps_uacs,
                                    'title_group' => $this->ps_category_attr->categoryGroups->id,
                                    'account_title_id' => $this->ps_category_attr->categoryItems->id,
                                    'account_title' => $this->ps_category_attr->categoryItems->name,
                                    'ppmp' => $this->ps_ppmp,
                                    'cost_per_unit' => $this->ps_cost_per_unit,
                                    'quantity' => json_encode($this->ps_quantity),
                                    'total_quantity' => $this->ps_total_quantity,
                                    'uom' => $this->ps_uom,
                                    'estimated_budget' => $intEstimatedBudget,
                                    'remarks' => $this->ps_remarks,
                                ]
                            );
                            break;
                        }
                    }
                }
            } else {
                $this->ps[] = [
                    'budget_category_id' => $this->ps_category_attr->categoryItems->budget_category_id,
                    'budget_category' => 'Professional Services',
                    'particular_id' => $this->ps_particular_id,
                    'particular' => $this->ps_category_attr->particulars,
                    'supply_code' => $this->ps_category_attr->supply_code,
                    'specifications' => $this->ps_category_attr->specifications,
                    'uacs' => $this->ps_uacs,
                    'title_group' => $this->ps_category_attr->categoryGroups->id,
                    'account_title_id' => $this->ps_category_attr->categoryItems->id,
                    'account_title' => $this->ps_category_attr->categoryItems->name,
                    'ppmp' => $this->ps_ppmp,
                    'cost_per_unit' => $this->ps_cost_per_unit,
                    'quantity' => $this->ps_quantity,
                    'total_quantity' => $this->ps_total_quantity,
                    'uom' => $this->ps_uom,
                    'estimated_budget' => $intEstimatedBudget,
                    'remarks' => $this->ps_remarks,
                ];
                if ($this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->first()->fundDrafts()->exists()) {
                    $draft_items = FundDraftItem::create(
                        [
                            'fund_draft_id' => $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $this->wfp_param
                            )->where(
                                'supplemental_quarter_id',
                                $this->supplementalQuarterId
                            )->first()->fundDrafts->first()->id,
                            'budget_category_id' => $this->ps_category_attr->categoryItems->budget_category_id,
                            'budget_category' => 'Professional Services',
                            'particular_id' => $this->ps_particular_id,
                            'particular' => $this->ps_category_attr->particulars,
                            'supply_code' => $this->ps_category_attr->supply_code,
                            'specifications' => $this->ps_category_attr->specifications,
                            'uacs' => $this->ps_uacs,
                            'title_group' => $this->ps_category_attr->categoryGroups->id,
                            'account_title_id' => $this->ps_category_attr->categoryItems->id,
                            'account_title' => $this->ps_category_attr->categoryItems->name,
                            'ppmp' => $this->ps_ppmp,
                            'cost_per_unit' => $this->ps_cost_per_unit,
                            'quantity' => json_encode($this->ps_quantity),
                            'total_quantity' => $this->ps_total_quantity,
                            'uom' => $this->ps_uom,
                            'estimated_budget' => $intEstimatedBudget,
                            'remarks' => $this->ps_remarks,
                        ]
                    );
                } else {
                    $draft = FundDraft::create([
                        'fund_allocation_id' => $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->where(
                            'supplemental_quarter_id',
                            $this->supplementalQuarterId
                        )->first()->id,
                    ]);

                    $draft_items = FundDraftItem::create(
                        [
                            'fund_draft_id' => $draft->id,
                            'budget_category_id' => $this->ps_category_attr->categoryItems->budget_category_id,
                            'budget_category' => 'Professional Services',
                            'particular_id' => $this->ps_particular_id,
                            'particular' => $this->ps_category_attr->particulars,
                            'supply_code' => $this->ps_category_attr->supply_code,
                            'specifications' => $this->ps_category_attr->specifications,
                            'uacs' => $this->ps_uacs,
                            'title_group' => $this->ps_category_attr->categoryGroups->id,
                            'account_title_id' => $this->ps_category_attr->categoryItems->id,
                            'account_title' => $this->ps_category_attr->categoryItems->name,
                            'ppmp' => $this->ps_ppmp,
                            'cost_per_unit' => $this->ps_cost_per_unit,
                            'quantity' => json_encode($this->ps_quantity),
                            'total_quantity' => $this->ps_total_quantity,
                            'uom' => $this->ps_uom,
                            'estimated_budget' => $intEstimatedBudget,
                            'remarks' => $this->ps_remarks,
                        ]
                    );
                }
            }

            if ($this->wfp_fund->id === 2 || $this->wfp_fund->id === 4 || $this->wfp_fund->id === 5 || $this->wfp_fund->id === 6 || $this->wfp_fund->id === 7) {
                $categoryGroupId = $this->ps_category_attr->categoryGroups->id;
                $found = false;

                foreach ($this->current_balance as $key => $balance) {
                    if ($balance && $balance['category_group_id'] == $categoryGroupId) {
                        // $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                        $found = true;
                        //if(!$this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)->where('supplemental_quarter_id',$this->supplementalQuarterId)->first()->fundDrafts()->exists())
                        //{
                        if ($this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->where(
                            'supplemental_quarter_id',
                            $this->supplementalQuarterId
                        )->first()->fundDrafts()->exists()) {
                            $draft_id = $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $this->wfp_param
                            )->where(
                                'supplemental_quarter_id',
                                $this->supplementalQuarterId
                            )->first()->fundDrafts()->first()->id;
                            $draft_amounts = FundDraftAmount::where(
                                'fund_draft_id',
                                $draft_id
                            )->where('category_group_id', $categoryGroupId)->first();
                            $draft_amounts->current_total = $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                            $draft_amounts->balance = $this->current_balance[$key]['balance'];
                            $draft_amounts->save();
                        } else {
                            $draft_amounts = FundDraftAmount::where('category_group_id', $categoryGroupId)->first();
                            $draft_amounts->current_total = $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                            $draft_amounts->balance = $this->current_balance[$key]['balance'];
                            $draft_amounts->save();
                        }
                        //}

                        break;
                    }
                }

                if (!$found) {
                    $this->current_balance[] = [
                        'category_group_id' => $categoryGroupId,
                        'category_group' => $this->ps_category_attr->categoryGroups->name,
                        'initial_amount' => 0,
                        'current_total' => $intEstimatedBudget,
                        'balance' => $intEstimatedBudget,
                    ];
                    //if(!$this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)->where('supplemental_quarter_id',$this->supplementalQuarterId)->first()->fundDrafts->first()->draft_amounts()->exists())
                    //{
                    FundDraftAmount::create([
                        'fund_draft_id' => $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->where(
                            'supplemental_quarter_id',
                            $this->supplementalQuarterId
                        )->first()->fundDrafts->first()->id,
                        'category_group_id' => $categoryGroupId,
                        'category_group' => $this->ps_category_attr->categoryGroups->name,
                        'initial_amount' => 0,
                        'current_total' => $intEstimatedBudget,
                        'balance' => $intEstimatedBudget,
                    ]);
                    //}
                }
            } else {
                //add current_total to current balance from estimated budget
                foreach ($this->current_balance as $key => $balance) {
                    if ($balance && $balance['category_group_id'] == $this->ps_category_attr->categoryGroups->id) {
                        $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                        $this->current_balance[$key]['balance'] -= $intEstimatedBudget;
                    }
                }

                if (!$this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->first()->fundDrafts()->first()->draft_amounts()->exists()) {
                    foreach ($this->current_balance as $item) {
                        $draft_amounts = FundDraftAmount::create([
                            'fund_draft_id' => $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $this->wfp_param
                            )->where(
                                'supplemental_quarter_id',
                                $this->supplementalQuarterId
                            )->first()->fundDrafts->first()->id,
                            'category_group_id' => $item['category_group_id'],
                            'category_group' => $item['category_group'],
                            'initial_amount' => $item['initial_amount'],
                            'current_total' => $item['current_total'],
                            'balance' => $item['initial_amount'],
                        ]);
                    }
                } else {
                    foreach ($this->current_balance as $item) {
                        if ($this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->where(
                            'supplemental_quarter_id',
                            $this->supplementalQuarterId
                        )->first()->fundDrafts->first()->draft_amounts()->exists()) {
                            $draft_amounts = FundDraftAmount::create([
                                'fund_draft_id' => $this->record->fundAllocations->where(
                                    'wpf_type_id',
                                    $this->wfp_param
                                )->where(
                                    'supplemental_quarter_id',
                                    $this->supplementalQuarterId
                                )->first()->fundDrafts->first()->id,
                                'category_group_id' => $item['category_group_id'],
                                'category_group' => $item['category_group'],
                                'initial_amount' => $item['initial_amount'],
                                'current_total' => $item['current_total'],
                                'balance' => $item['initial_amount'],
                            ]);
                        } else {
                            $draft_amounts = $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $this->wfp_param
                            )->where(
                                'supplemental_quarter_id',
                                $this->supplementalQuarterId
                            )->first()->fundDrafts->first()->draft_amounts->where(
                                'category_group_id',
                                $item['category_group_id']
                            )->first();
                            $draft_amounts->current_total = $item['current_total'];
                            $draft_amounts->balance = $item['balance'];
                            $draft_amounts->save();
                        }
                    }
                }
            }
        } else {
            $is_valid_category_group = $this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)
                ->pluck('category_group_id')
                ->contains($this->ps_category_attr->categoryGroups->id);

            if ($is_valid_category_group) {
                $fund_allocation = $this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)
                    ->where('category_group_id', $this->ps_category_attr->categoryGroups->id)
                    ->first();

                if ($fund_allocation && $fund_allocation->initial_amount == 0) {
                    $this->dialog()->error(
                        $title = 'Operation Failed',
                        $description = 'You don\'t have fund allocation for this title group.',
                    );
                    return;
                }
            }

            $intEstimatedBudget = (float) str_replace(',', '', $this->ps_estimated_budget);
            //$draft_id = $this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)->first()->fundDrafts()->first()->id;
            if ($this->ps != null) {
                foreach ($this->ps as $key => $ps) {
                    if ($ps['particular_id'] == $this->ps_particular_id && $ps['uom'] == $this->ps_uom && $ps['remarks'] == $this->ps_remarks) {
                        $this->ps[$key]['quantity'] = $this->ps[$key]['quantity'] += $this->ps_quantity;
                        $this->ps[$key]['total_quantity'] = $this->ps[$key]['total_quantity'] += $this->ps_total_quantity;
                        $this->ps[$key]['estimated_budget'] = $this->ps[$key]['estimated_budget'] += $intEstimatedBudget;
                        $this->ps[$key]['quantity'] = array_map(function ($a, $b) {
                            return $a + $b;
                        }, $this->ps[$key]['quantity'], $this->ps_quantity);

                        $draft_items = $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->first()->fundDrafts->first()->draft_items->where(
                            'particular_id',
                            $this->ps_particular_id
                        )->where('uom', $this->ps_uom)->where(
                            'remarks',
                            $this->ps_remarks
                        )->first();
                        $draft_items->quantity = json_encode($this->ps[$key]['quantity']);
                        $draft_items->total_quantity = $this->ps[$key]['total_quantity'];
                        $draft_items->estimated_budget = $this->ps[$key]['estimated_budget'];
                        $draft_items->save();
                        break;
                    } else {
                        $existingDraftItem = $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->first()->fundDrafts->first()->draft_items->where(
                            'particular_id',
                            $this->ps_particular_id
                        )->where('uom', $this->ps_uom)->where(
                            'remarks',
                            $this->ps_remarks
                        )->first();
                        if (!$existingDraftItem) {
                            $this->ps[] = [
                                'budget_category_id' => $this->ps_category_attr->categoryItems->budget_category_id,
                                'budget_category' => 'Professional Services',
                                'particular_id' => $this->ps_particular_id,
                                'particular' => $this->ps_category_attr->particulars,
                                'supply_code' => $this->ps_category_attr->supply_code,
                                'specifications' => $this->ps_category_attr->specifications,
                                'uacs' => $this->ps_uacs,
                                'title_group' => $this->ps_category_attr->categoryGroups->id,
                                'account_title_id' => $this->ps_category_attr->categoryItems->id,
                                'account_title' => $this->ps_category_attr->categoryItems->name,
                                'ppmp' => $this->ps_ppmp,
                                'cost_per_unit' => $this->ps_cost_per_unit,
                                'quantity' => $this->ps_quantity,
                                'total_quantity' => $this->ps_total_quantity,
                                'uom' => $this->ps_uom,
                                'estimated_budget' => $intEstimatedBudget,
                                'remarks' => $this->ps_remarks,
                            ];

                            FundDraftItem::create(
                                [
                                    'fund_draft_id' => $this->record->fundAllocations->where(
                                        'wpf_type_id',
                                        $this->wfp_param
                                    )->first()->fundDrafts->first()->id,
                                    'budget_category_id' => $this->ps_category_attr->categoryItems->budget_category_id,
                                    'budget_category' => 'Professional Services',
                                    'particular_id' => $this->ps_particular_id,
                                    'particular' => $this->ps_category_attr->particulars,
                                    'supply_code' => $this->ps_category_attr->supply_code,
                                    'specifications' => $this->ps_category_attr->specifications,
                                    'uacs' => $this->ps_uacs,
                                    'title_group' => $this->ps_category_attr->categoryGroups->id,
                                    'account_title_id' => $this->ps_category_attr->categoryItems->id,
                                    'account_title' => $this->ps_category_attr->categoryItems->name,
                                    'ppmp' => $this->ps_ppmp,
                                    'cost_per_unit' => $this->ps_cost_per_unit,
                                    'quantity' => json_encode($this->ps_quantity),
                                    'total_quantity' => $this->ps_total_quantity,
                                    'uom' => $this->ps_uom,
                                    'estimated_budget' => $intEstimatedBudget,
                                    'remarks' => $this->ps_remarks,
                                ]
                            );
                            break;
                        }
                    }
                }
            } else {
                $this->ps[] = [
                    'budget_category_id' => $this->ps_category_attr->categoryItems->budget_category_id,
                    'budget_category' => 'Professional Services',
                    'particular_id' => $this->ps_particular_id,
                    'particular' => $this->ps_category_attr->particulars,
                    'supply_code' => $this->ps_category_attr->supply_code,
                    'specifications' => $this->ps_category_attr->specifications,
                    'uacs' => $this->ps_uacs,
                    'title_group' => $this->ps_category_attr->categoryGroups->id,
                    'account_title_id' => $this->ps_category_attr->categoryItems->id,
                    'account_title' => $this->ps_category_attr->categoryItems->name,
                    'ppmp' => $this->ps_ppmp,
                    'cost_per_unit' => $this->ps_cost_per_unit,
                    'quantity' => $this->ps_quantity,
                    'total_quantity' => $this->ps_total_quantity,
                    'uom' => $this->ps_uom,
                    'estimated_budget' => $intEstimatedBudget,
                    'remarks' => $this->ps_remarks,
                ];
                if ($this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->first()->fundDrafts()->exists()) {
                    $draft_items = FundDraftItem::create(
                        [
                            'fund_draft_id' => $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $this->wfp_param
                            )->first()->fundDrafts->first()->id,
                            'budget_category_id' => $this->ps_category_attr->categoryItems->budget_category_id,
                            'budget_category' => 'Professional Services',
                            'particular_id' => $this->ps_particular_id,
                            'particular' => $this->ps_category_attr->particulars,
                            'supply_code' => $this->ps_category_attr->supply_code,
                            'specifications' => $this->ps_category_attr->specifications,
                            'uacs' => $this->ps_uacs,
                            'title_group' => $this->ps_category_attr->categoryGroups->id,
                            'account_title_id' => $this->ps_category_attr->categoryItems->id,
                            'account_title' => $this->ps_category_attr->categoryItems->name,
                            'ppmp' => $this->ps_ppmp,
                            'cost_per_unit' => $this->ps_cost_per_unit,
                            'quantity' => json_encode($this->ps_quantity),
                            'total_quantity' => $this->ps_total_quantity,
                            'uom' => $this->ps_uom,
                            'estimated_budget' => $intEstimatedBudget,
                            'remarks' => $this->ps_remarks,
                        ]
                    );
                } else {
                    $draft = FundDraft::create([
                        'fund_allocation_id' => $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->first()->id,
                    ]);

                    $draft_items = FundDraftItem::create(
                        [
                            'fund_draft_id' => $draft->id,
                            'budget_category_id' => $this->ps_category_attr->categoryItems->budget_category_id,
                            'budget_category' => 'Professional Services',
                            'particular_id' => $this->ps_particular_id,
                            'particular' => $this->ps_category_attr->particulars,
                            'supply_code' => $this->ps_category_attr->supply_code,
                            'specifications' => $this->ps_category_attr->specifications,
                            'uacs' => $this->ps_uacs,
                            'title_group' => $this->ps_category_attr->categoryGroups->id,
                            'account_title_id' => $this->ps_category_attr->categoryItems->id,
                            'account_title' => $this->ps_category_attr->categoryItems->name,
                            'ppmp' => $this->ps_ppmp,
                            'cost_per_unit' => $this->ps_cost_per_unit,
                            'quantity' => json_encode($this->ps_quantity),
                            'total_quantity' => $this->ps_total_quantity,
                            'uom' => $this->ps_uom,
                            'estimated_budget' => $intEstimatedBudget,
                            'remarks' => $this->ps_remarks,
                        ]
                    );
                }
            }

            if ($this->wfp_fund->id === 2 || $this->wfp_fund->id === 4 || $this->wfp_fund->id === 5 || $this->wfp_fund->id === 6 || $this->wfp_fund->id === 7) {
                $categoryGroupId = $this->ps_category_attr->categoryGroups->id;
                $found = false;

                foreach ($this->current_balance as $key => $balance) {
                    if ($balance && $balance['category_group_id'] == $categoryGroupId) {
                        // $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                        $found = true;
                        //if(!$this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)->first()->fundDrafts()->exists())
                        //{
                        $draft_amounts = FundDraftAmount::where('category_group_id', $categoryGroupId)->first();

                        $draft_amounts->current_total = $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                        $draft_amounts->balance = $this->current_balance[$key]['balance'];
                        $draft_amounts->save();
                        //}

                        break;
                    }
                }

                if (!$found) {
                    $this->current_balance[] = [
                        'category_group_id' => $categoryGroupId,
                        'category_group' => $this->ps_category_attr->categoryGroups->name,
                        'initial_amount' => 0,
                        'current_total' => $intEstimatedBudget,
                        'balance' => $intEstimatedBudget,
                    ];
                    //if(!$this->record->fundAllocations->where('wpf_type_id', $this->wfp_param)->first()->fundDrafts->first()->draft_amounts()->exists())
                    //{
                    FundDraftAmount::create([
                        'fund_draft_id' => $this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->first()->fundDrafts->first()->id,
                        'category_group_id' => $categoryGroupId,
                        'category_group' => $this->ps_category_attr->categoryGroups->name,
                        'initial_amount' => 0,
                        'current_total' => $intEstimatedBudget,
                        'balance' => $intEstimatedBudget,
                    ]);
                    //}
                }
            } else {
                //add current_total to current balance from estimated budget
                foreach ($this->current_balance as $key => $balance) {
                    if ($balance && $balance['category_group_id'] == $this->ps_category_attr->categoryGroups->id) {
                        $this->current_balance[$key]['current_total'] += $intEstimatedBudget;
                        $this->current_balance[$key]['balance'] -= $intEstimatedBudget;
                    }
                }

                if (!$this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->first()->fundDrafts()->first()->draft_amounts()->exists()) {
                    foreach ($this->current_balance as $item) {
                        $draft_amounts = FundDraftAmount::create([
                            'fund_draft_id' => $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $this->wfp_param
                            )->first()->fundDrafts->first()->id,
                            'category_group_id' => $item['category_group_id'],
                            'category_group' => $item['category_group'],
                            'initial_amount' => $item['initial_amount'],
                            'current_total' => $item['current_total'],
                            'balance' => $item['initial_amount'],
                        ]);
                    }
                } else {
                    foreach ($this->current_balance as $item) {
                        if ($this->record->fundAllocations->where(
                            'wpf_type_id',
                            $this->wfp_param
                        )->first()->fundDrafts->first()->draft_amounts()->exists()) {
                            $draft_amounts = FundDraftAmount::create([
                                'fund_draft_id' => $this->record->fundAllocations->where(
                                    'wpf_type_id',
                                    $this->wfp_param
                                )->first()->fundDrafts->first()->id,
                                'category_group_id' => $item['category_group_id'],
                                'category_group' => $item['category_group'],
                                'initial_amount' => $item['initial_amount'],
                                'current_total' => $item['current_total'],
                                'balance' => $item['initial_amount'],
                            ]);
                        } else {
                            $draft_amounts = $this->record->fundAllocations->where(
                                'wpf_type_id',
                                $this->wfp_param
                            )->first()->fundDrafts->first()->draft_amounts->where(
                                'category_group_id',
                                $item['category_group_id']
                            )->first();
                            $draft_amounts->current_total = $item['current_total'];
                            $draft_amounts->balance = $item['balance'];
                            $draft_amounts->save();
                        }
                    }
                }
            }
        }


        $this->addDraft();
        switch ($this->global_index) {
            case 2:
                $this->clearSupplies();
                break;
            case 3:
                $this->clearMooe();
                break;
            case 4:
                $this->clearTrainings();
                break;
            case 5:
                $this->clearMachine();
                break;
            case 6:
                $this->clearBuilding();
                break;
            case 7:
                $this->clearPs();
                break;
        }
    }

    public function showPsDetails()
    {
        $this->psDetailModal = true;
    }

    public function clearPs()
    {
        $this->supplies_particular = null;
        $this->ps_particular_id = null;
        $this->ps_specs = null;
        $this->ps_code = null;
        $this->ps_category_attr = null;
        $this->ps_uacs = null;
        $this->ps_title_group = null;
        $this->ps_account_title = null;
        $this->ps_ppmp = false;
        $this->ps_cost_per_unit = 0;
        $this->ps_total_quantity = 0;
        $this->ps_estimated_budget = 0;
        $this->ps_uom = null;
        $this->ps_quantity = array_fill(0, 12, 0);
        $this->ps_is_remarks = false;
    }

    public function decreaseStep()
    {
        $this->emit('refreshComponent');
        $this->supplies_particular = null;
        //$this->form->fill();
        $this->global_index--;
    }

    public function increaseStep()
    {
        $this->emit('refreshComponent');
        //$this->form->fill();
        $this->supplies_particular = null;
        $this->global_index++;
    }


    public function submit()
    {
        if ($this->is_supplemental) {
            $is_not_valid = false;
            if ($this->fund_description === null || $this->specify_fund_source === null) {
                $this->dialog()->error(
                    $title = 'Operation Failed',
                    $description = 'Please fill up initial information',
                );
                $this->suppliesDetailModal = false;
                $this->global_index = 1;
            }
            if (in_array($this->wfp_fund->id, [1, 3, 9])) {
                foreach ($this->current_balance as $balance) {
                    if ($balance && $balance['initial_amount'] < $balance['current_total']) {
                        $is_not_valid = true;
                        break;
                    }
                }
            } else {
                $with_balance = $this->wfp_balance;
                if (array_sum(array_column($this->current_balance, 'current_total')) > $with_balance) {
                    $is_not_valid = true;
                }
            }
            //save data to database
            DB::beginTransaction();
            if (!$is_not_valid) {
                $sumAllocated = $this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->sum('initial_amount');
                // $sumTotal = $this->record->fundAllocations
                if ($this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->first()->fundDrafts()->first()->draft_items()->exists()) {
                    $sumTotal = $this->record->fundAllocations->where(
                        'wpf_type_id',
                        $this->wfp_param
                    )->where(
                        'supplemental_quarter_id',
                        $this->supplementalQuarterId
                    )->first()->fundDrafts->first()->draft_items->sum('estimated_budget');
                } else {
                    $sumTotal = array_sum(array_column($this->current_balance, 'current_total'));
                }
                $sumBalance = $sumAllocated - $sumTotal;
                //if wfp already exist
                if ($this->record->wfp()->where(
                    'wpf_type_id',
                    $this->wfp_type->id
                )->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->where('fund_cluster_id', $this->wfp_fund->id)->exists()) {
                    $wfp = $this->record->wfp()->where(
                        'wpf_type_id',
                        $this->wfp_type->id
                    )->where(
                        'supplemental_quarter_id',
                        $this->supplementalQuarterId
                    )->where('fund_cluster_id', $this->wfp_fund->id)->first();
                    $wfp->update([
                        'cost_center_id' => $this->record->id,
                        'wpf_type_id' => $this->wfp_type->id,
                        'fund_cluster_id' => $this->wfp_fund->id,
                        'user_id' => auth()->user()->id,
                        'fund_description' => $this->fund_description,
                        'source_fund' => $this->source_fund,
                        'confirm_fund_source' => $this->confirm_fund_source ?? null,
                        'specify_fund_source' => $this->specify_fund_source,
                        'balance' => $sumBalance,
                        'program_allocated' => $sumTotal,
                        'total_allocated_fund' => $sumAllocated,
                    ]);

                    $wfp->wfpDetails()->delete();

                    foreach ($this->supplies as $item) {
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

                    foreach ($this->mooe as $item) {
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

                    foreach ($this->trainings as $item) {
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

                    foreach ($this->machines as $item) {
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

                    foreach ($this->buildings as $item) {
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

                    foreach ($this->ps as $item) {
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

                    $this->dialog()->success(
                        $title = 'Operation Successful',
                        $description = 'WFP has been successfully updated',
                    );
                } else {
                    $wfp = Wfp::create([
                        'cost_center_id' => $this->record->id,
                        'wpf_type_id' => $this->wfp_type->id,
                        'fund_cluster_id' => $this->wfp_fund->id,
                        'user_id' => auth()->user()->id,
                        'fund_description' => $this->fund_description,
                        'source_fund' => $this->source_fund,
                        'confirm_fund_source' => $this->confirm_fund_source ?? null,
                        'specify_fund_source' => $this->specify_fund_source,
                        'balance' => $sumBalance,
                        'program_allocated' => $sumTotal,
                        'total_allocated_fund' => $sumAllocated,
                        'is_supplemental' => $this->is_supplemental,
                        'supplemental_quarter_id' => $this->supplementalQuarterId,
                    ]);

                    foreach ($this->supplies as $item) {
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

                    foreach ($this->mooe as $item) {
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

                    foreach ($this->trainings as $item) {
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

                    foreach ($this->machines as $item) {
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

                    foreach ($this->buildings as $item) {
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

                    foreach ($this->ps as $item) {
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

                    $this->dialog()->success(
                        $title = 'Operation Successful',
                        $description = 'WFP has been successfully created',
                    );
                }

                $wfp = $this->record->wfp()->where(
                    'wpf_type_id',
                    $this->wfp_type->id
                )->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->where('fund_cluster_id', $this->wfp_fund->id)->first();
                $wfp->is_approved = 0;
                $wfp->save();
                DB::commit();


                return redirect()->route('wfp.wfp-history');
            } else {
                $this->dialog()->error(
                    $title = 'Operation Failed',
                    $description = 'Your grand total has exceeded the budget allocation',
                );
                $this->suppliesDetailModal = false;
            }
        } else {
            $is_not_valid = false;
            if ($this->fund_description === null || $this->specify_fund_source === null) {
                $this->dialog()->error(
                    $title = 'Operation Failed',
                    $description = 'Please fill up initial information',
                );
                $this->suppliesDetailModal = false;
                $this->global_index = 1;
            }

            if ($this->wfp_fund->id === 1 || $this->wfp_fund->id === 3) {

                foreach ($this->current_balance as $balance) {
                    if ($balance['initial_amount'] < $balance['current_total']) {
                        $is_not_valid = true;
                        break;
                    }
                }
            } else {
                if (array_sum(array_column(
                    $this->current_balance,
                    'current_total'
                )) > $this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->sum('initial_amount')) {
                    $is_not_valid = true;
                }
            }

            //save data to database
            DB::beginTransaction();
            if (!$is_not_valid) {
                $sumAllocated = $this->record->fundAllocations->sum('initial_amount');
                // $sumTotal = $this->record->fundAllocations
                if ($this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->first()->fundDrafts()->first()->draft_items()->exists()) {
                    $sumTotal = $this->record->fundAllocations->where(
                        'wpf_type_id',
                        $this->wfp_param
                    )->first()->fundDrafts->first()->draft_items->sum('estimated_budget');
                } else {
                    $sumTotal = array_sum(array_column($this->current_balance, 'current_total'));
                }
                $sumBalance = $sumAllocated - $sumTotal;

                //if wfp already exist
                if ($this->record->wfp()->where('wpf_type_id', $this->wfp_type->id)->where(
                    'fund_cluster_id',
                    $this->wfp_fund->id
                )->exists()) {
                    $wfp = $this->record->wfp()->where('wpf_type_id', $this->wfp_type->id)->where(
                        'fund_cluster_id',
                        $this->wfp_fund->id
                    )->first();
                    $wfp->update([
                        'cost_center_id' => $this->record->id,
                        'wpf_type_id' => $this->wfp_type->id,
                        'fund_cluster_id' => $this->wfp_fund->id,
                        'user_id' => auth()->user()->id,
                        'fund_description' => $this->fund_description,
                        'source_fund' => $this->source_fund,
                        'confirm_fund_source' => $this->confirm_fund_source ?? null,
                        'specify_fund_source' => $this->specify_fund_source,
                        'balance' => $sumBalance,
                        'program_allocated' => $sumTotal,
                        'total_allocated_fund' => $sumAllocated,
                    ]);

                    $wfp->wfpDetails()->delete();

                    foreach ($this->supplies as $item) {
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

                    foreach ($this->mooe as $item) {
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

                    foreach ($this->trainings as $item) {
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

                    foreach ($this->machines as $item) {
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

                    foreach ($this->buildings as $item) {
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

                    foreach ($this->ps as $item) {
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

                    $this->dialog()->success(
                        $title = 'Operation Successful',
                        $description = 'WFP has been successfully updated',
                    );
                } else {
                    $wfp = Wfp::create([
                        'cost_center_id' => $this->record->id,
                        'wpf_type_id' => $this->wfp_type->id,
                        'fund_cluster_id' => $this->wfp_fund->id,
                        'user_id' => auth()->user()->id,
                        'fund_description' => $this->fund_description,
                        'source_fund' => $this->source_fund,
                        'confirm_fund_source' => $this->confirm_fund_source ?? null,
                        'specify_fund_source' => $this->specify_fund_source,
                        'balance' => $sumBalance,
                        'program_allocated' => $sumTotal,
                        'total_allocated_fund' => $sumAllocated,
                        'is_supplemental' => $this->is_supplemental,
                        'supplemental_quarter_id' => $this->supplementalQuarterId,
                    ]);

                    foreach ($this->supplies as $item) {
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

                    foreach ($this->mooe as $item) {
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

                    foreach ($this->trainings as $item) {
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

                    foreach ($this->machines as $item) {
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

                    foreach ($this->buildings as $item) {
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

                    foreach ($this->ps as $item) {
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

                    $this->dialog()->success(
                        $title = 'Operation Successful',
                        $description = 'WFP has been successfully created',
                    );
                }

                $wfp = $this->record->wfp()->where('wpf_type_id', $this->wfp_type->id)->where(
                    'fund_cluster_id',
                    $this->wfp_fund->id
                )->first();
                $wfp->is_approved = 0;
                $wfp->save();

                DB::commit();


                return redirect()->route('wfp.wfp-history');
            } else {
                $this->dialog()->error(
                    $title = 'Operation Failed',
                    $description = 'Your grand total has exceeded the budget allocation',
                );
                $this->suppliesDetailModal = false;
            }
        }
    }

    public function viewRemarks($index, $type)
    {
        $this->remarksModal = true;

        switch ($type) {
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
            case 6:
                $this->ps_remarks_details = $this->ps[$index]['remarks'];
                $this->remarks_modal_title = 'PS';
                break;
            default:
                $this->remarks_modal_title = 'Remarks';
        }
    }

    public function deleteSupply($index)
    {
        if ($this->is_supplemental) {
            if (isset($this->supplies[$index])) {
                $budget = $this->supplies[$index]['estimated_budget'];
                $title_group = $this->supplies[$index]['title_group'];
                $particular_id = $this->supplies[$index]['particular_id'];
                $uom = $this->supplies[$index]['uom'];
                $remarks = $this->supplies[$index]['remarks'];
                $supply_code = $this->supplies[$index]['supply_code'];
                $fund_draft = $this->fund_allocations->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->first()->fundDrafts->first();
                $draft = $this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->first()->fundDrafts()->first();
                $draft_amount = FundDraftAmount::where('fund_draft_id', $draft->id)->where(
                    'category_group_id',
                    $title_group
                )->first();
                $draft_item = $fund_draft->draft_items->where('title_group', $title_group)->where(
                    'particular_id',
                    $particular_id
                )->where('uom', $uom)
                    ->where('remarks', $remarks)->where('supply_code', $supply_code)->first();
                // $draft_item = FundDraftItem::whereHas('fundDraft')->where('particular_id', $particular_id)->where('uom', $uom)
                // ->where('remarks', $remarks)->where('supply_code', $supply_code)->first();
                //$draft_amount = $fund_draft->draft_amounts->where('category_group_id', $title_group)->where('fund_draft_id', $fund_draft->id)->first();
                foreach ($this->current_balance as $key => $item) {
                    if ($item && $item['category_group_id'] === $title_group) {
                        if (isset($this->current_balance[$key]['current_total']) && is_numeric($this->current_balance[$key]['current_total'])) {


                            $this->current_balance[$key]['current_total'] -= $budget;
                            $this->current_balance[$key]['balance'] += $budget;
                            if ($draft_amount) {
                                $draft_amount->current_total -= $budget;
                                $draft_amount->balance += $budget;
                                $draft_amount->save();

                                if ($draft_amount->current_total <= 0) {
                                    $draft_amount->delete();
                                }
                            }
                            break;
                        }
                    }
                }

                $draft_item->delete();


                // Remove the supply at the given index
                unset($this->supplies[$index]);
                // unset($this->current_balance[$key]);
                // Reset the array indices to avoid undefined index issues
                $this->supplies = array_values($this->supplies);
            }
        } else {
            if (isset($this->supplies[$index])) {
                $budget = $this->supplies[$index]['estimated_budget'];
                $title_group = $this->supplies[$index]['title_group'];
                $particular_id = $this->supplies[$index]['particular_id'];
                $uom = $this->supplies[$index]['uom'];
                $remarks = $this->supplies[$index]['remarks'];
                $supply_code = $this->supplies[$index]['supply_code'];
                $fund_draft = $this->fund_allocations->where('is_supplemental', 0)->first()->fundDrafts->first();
                $draft_item = $fund_draft->draft_items->where('title_group', $title_group)->where(
                    'particular_id',
                    $particular_id
                )->where('uom', $uom)
                    ->where('remarks', $remarks)->where('supply_code', $supply_code)->first();
                // $draft_item = FundDraftItem::whereHas('fundDraft')->where('particular_id', $particular_id)->where('uom', $uom)
                // ->where('remarks', $remarks)->where('supply_code', $supply_code)->first();
                $draft_amount = $fund_draft->draft_amounts->where(
                    'category_group_id',
                    $title_group
                )->where('fund_draft_id', $fund_draft->id)->first();
                foreach ($this->current_balance as $key => $item) {
                    if ($item['category_group_id'] === $title_group) {
                        if (isset($this->current_balance[$key]['current_total']) && is_numeric($this->current_balance[$key]['current_total'])) {

                            $this->current_balance[$key]['current_total'] -= $budget;
                            $this->current_balance[$key]['balance'] += $budget;
                            if ($draft_amount) {
                                $draft_amount->current_total -= $budget;
                                $draft_amount->balance += $budget;
                                $draft_amount->save();

                                if ($draft_amount->current_total <= 0) {
                                    $draft_amount->delete();
                                }
                            }
                            break;
                        }
                    }
                }

                $draft_item->delete();


                // Remove the supply at the given index
                unset($this->supplies[$index]);
                // unset($this->current_balance[$key]);
                // Reset the array indices to avoid undefined index issues
                $this->supplies = array_values($this->supplies);
            }
        }
    }

    public function deleteMooe($index)
    {
        if ($this->is_supplemental) {
            if (isset($this->mooe[$index])) {
                $budget = $this->mooe[$index]['estimated_budget'];
                $title_group = $this->mooe[$index]['title_group'];
                $particular_id = $this->mooe[$index]['particular_id'];
                $uom = $this->mooe[$index]['uom'];
                $remarks = $this->mooe[$index]['remarks'];
                $supply_code = $this->mooe[$index]['supply_code'];
                $fund_draft = $this->fund_allocations->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->first()->fundDrafts->first();
                $draft = $this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->first()->fundDrafts()->first();
                $draft_amount = FundDraftAmount::where('fund_draft_id', $draft->id)->where(
                    'category_group_id',
                    $title_group
                )->first();
                $draft_item = $fund_draft->draft_items->where('title_group', $title_group)->where(
                    'particular_id',
                    $particular_id
                )->where('uom', $uom)
                    ->where('remarks', $remarks)->where('supply_code', $supply_code)->first();
                // $draft_item = FundDraftItem::where('particular_id', $particular_id)->where('uom', $uom)
                // ->where('remarks', $remarks)->first();
                //$wfp_draft_id = $draft_item->fund_draft_id;
                //$draft_amount = $fund_draft->draft_amounts->where('category_group_id', $title_group)->where('fund_draft_id', $fund_draft->id)->first();
                // $draft_amount = FundDraftAmount::where('category_group_id', $title_group)->where('fund_draft_id', $wfp_draft_id)->first();

                foreach ($this->current_balance as $key => $item) {
                    if ($item && $item['category_group_id'] === $title_group) {
                        if (isset($this->current_balance[$key]['current_total']) && is_numeric($this->current_balance[$key]['current_total'])) {

                            $this->current_balance[$key]['current_total'] -= $budget;
                            $this->current_balance[$key]['balance'] += $budget;

                            if ($draft_amount) {
                                $draft_amount->current_total -= $budget;
                                $draft_amount->balance += $budget;
                                $draft_amount->save();

                                if ($draft_amount->current_total <= 0) {
                                    $draft_amount->delete();
                                }
                            }

                            break;
                        }
                    }
                }

                $draft_item->delete();
                // Remove the supply at the given index
                unset($this->mooe[$index]);
                // unset($this->current_balance[$index]);
                // Reset the array indices to avoid undefined index issues
                $this->mooe = array_values($this->mooe);
            }
        } else {
            if (isset($this->mooe[$index])) {
                $budget = $this->mooe[$index]['estimated_budget'];
                $title_group = $this->mooe[$index]['title_group'];
                $particular_id = $this->mooe[$index]['particular_id'];
                $uom = $this->mooe[$index]['uom'];
                $remarks = $this->mooe[$index]['remarks'];
                $supply_code = $this->mooe[$index]['supply_code'];
                $fund_draft = $this->fund_allocations->where('is_supplemental', 0)->first()->fundDrafts->first();
                $draft_item = $fund_draft->draft_items->where('title_group', $title_group)->where(
                    'particular_id',
                    $particular_id
                )->where('uom', $uom)
                    ->where('remarks', $remarks)->where('supply_code', $supply_code)->first();
                // $draft_item = FundDraftItem::where('particular_id', $particular_id)->where('uom', $uom)
                // ->where('remarks', $remarks)->first();
                //$wfp_draft_id = $draft_item->fund_draft_id;
                $draft_amount = $fund_draft->draft_amounts->where(
                    'category_group_id',
                    $title_group
                )->where('fund_draft_id', $fund_draft->id)->first();
                // $draft_amount = FundDraftAmount::where('category_group_id', $title_group)->where('fund_draft_id', $wfp_draft_id)->first();

                foreach ($this->current_balance as $key => $item) {
                    if ($item['category_group_id'] === $title_group) {
                        if (isset($this->current_balance[$key]['current_total']) && is_numeric($this->current_balance[$key]['current_total'])) {

                            $this->current_balance[$key]['current_total'] -= $budget;
                            $this->current_balance[$key]['balance'] += $budget;

                            if ($draft_amount) {
                                $draft_amount->current_total -= $budget;
                                $draft_amount->balance += $budget;
                                $draft_amount->save();

                                if ($draft_amount->current_total <= 0) {
                                    $draft_amount->delete();
                                }
                            }

                            break;
                        }
                    }
                }

                $draft_item->delete();
                // Remove the supply at the given index
                unset($this->mooe[$index]);
                // unset($this->current_balance[$index]);
                // Reset the array indices to avoid undefined index issues
                $this->mooe = array_values($this->mooe);
            }
        }
    }

    public function deleteTraining($index)
    {
        if ($this->is_supplemental) {
            if (isset($this->trainings[$index])) {
                $budget = $this->trainings[$index]['estimated_budget'];
                $title_group = $this->trainings[$index]['title_group'];
                $particular_id = $this->trainings[$index]['particular_id'];
                $uom = $this->trainings[$index]['uom'];
                $remarks = $this->trainings[$index]['remarks'];
                $supply_code = $this->trainings[$index]['supply_code'];
                $fund_draft = $this->fund_allocations->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->first()->fundDrafts->first();
                $draft = $this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->first()->fundDrafts()->first();
                $draft_amount = FundDraftAmount::where('fund_draft_id', $draft->id)->where(
                    'category_group_id',
                    $title_group
                )->first();
                $draft_item = $fund_draft->draft_items->where('title_group', $title_group)->where(
                    'particular_id',
                    $particular_id
                )->where('uom', $uom)
                    ->where('remarks', $remarks)->where('supply_code', $supply_code)->first();
                // $draft_item = FundDraftItem::where('particular_id', $particular_id)->where('uom', $uom)
                // ->where('remarks', $remarks)->first();
                // $wfp_draft_id = $draft_item->fund_draft_id;
                //$draft_amount = $fund_draft->draft_amounts->where('category_group_id', $title_group)->where('fund_draft_id', $fund_draft->id)->first();
                // $draft_amount = FundDraftAmount::where('category_group_id', $title_group)->where('fund_draft_id', $wfp_draft_id)->first();
                foreach ($this->current_balance as $key => $item) {
                    if ($item && $item['category_group_id'] === $title_group) {
                        if (isset($this->current_balance[$key]['current_total']) && is_numeric($this->current_balance[$key]['current_total'])) {

                            $this->current_balance[$key]['current_total'] -= $budget;
                            $this->current_balance[$key]['balance'] += $budget;

                            if ($draft_amount) {
                                $draft_amount->current_total -= $budget;
                                $draft_amount->balance += $budget;
                                $draft_amount->save();

                                if ($draft_amount->current_total <= 0) {
                                    $draft_amount->delete();
                                }
                            }

                            break;
                        }
                    }
                }

                $draft_item->delete();
                // Remove the supply at the given index
                unset($this->trainings[$index]);
                // unset($this->current_balance[$index]);
                // Reset the array indices to avoid undefined index issues
                $this->trainings = array_values($this->trainings);
            }
        } else {
            if (isset($this->trainings[$index])) {
                $budget = $this->trainings[$index]['estimated_budget'];
                $title_group = $this->trainings[$index]['title_group'];
                $particular_id = $this->trainings[$index]['particular_id'];
                $uom = $this->trainings[$index]['uom'];
                $remarks = $this->trainings[$index]['remarks'];
                $supply_code = $this->trainings[$index]['supply_code'];
                $fund_draft = $this->fund_allocations->first()->fundDrafts->first();
                $draft = $this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->where('is_supplemental', 0)->first()->fundDrafts()->first();
                $draft_amount = FundDraftAmount::where('fund_draft_id', $draft->id)->where(
                    'category_group_id',
                    $title_group
                )->first();
                $draft_item = $fund_draft->draft_items->where('title_group', $title_group)->where(
                    'particular_id',
                    $particular_id
                )->where('uom', $uom)
                    ->where('remarks', $remarks)->where('supply_code', $supply_code)->first();
                // $draft_item = FundDraftItem::where('particular_id', $particular_id)->where('uom', $uom)
                // ->where('remarks', $remarks)->first();
                // $wfp_draft_id = $draft_item->fund_draft_id;
                //$draft_amount = $fund_draft->draft_amounts->where('category_group_id', $title_group)->where('fund_draft_id', $fund_draft->id)->first();
                // $draft_amount = FundDraftAmount::where('category_group_id', $title_group)->where('fund_draft_id', $wfp_draft_id)->first();
                foreach ($this->current_balance as $key => $item) {
                    if ($item['category_group_id'] === $title_group) {
                        if (isset($this->current_balance[$key]['current_total']) && is_numeric($this->current_balance[$key]['current_total'])) {

                            $this->current_balance[$key]['current_total'] -= $budget;
                            $this->current_balance[$key]['balance'] += $budget;

                            if ($draft_amount) {
                                $draft_amount->current_total -= $budget;
                                $draft_amount->balance += $budget;
                                $draft_amount->save();

                                if ($draft_amount->current_total <= 0) {
                                    $draft_amount->delete();
                                }
                            }

                            break;
                        }
                    }
                }

                $draft_item->delete();
                // Remove the supply at the given index
                unset($this->trainings[$index]);
                // unset($this->current_balance[$index]);
                // Reset the array indices to avoid undefined index issues
                $this->trainings = array_values($this->trainings);
            }
        }
    }

    public function deleteMachine($index)
    {
        if ($this->is_supplemental) {
            if (isset($this->machines[$index])) {
                $budget = $this->machines[$index]['estimated_budget'];
                $title_group = $this->machines[$index]['title_group'];
                $particular_id = $this->machines[$index]['particular_id'];
                $uom = $this->machines[$index]['uom'];
                $remarks = $this->machines[$index]['remarks'];
                $supply_code = $this->machines[$index]['supply_code'];
                $fund_draft = $this->fund_allocations->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->first()->fundDrafts->first();
                $draft = $this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->first()->fundDrafts()->first();
                $draft_amount = FundDraftAmount::where('fund_draft_id', $draft->id)->where(
                    'category_group_id',
                    $title_group
                )->first();
                $draft_item = $fund_draft->draft_items->where('title_group', $title_group)->where(
                    'particular_id',
                    $particular_id
                )->where('uom', $uom)
                    ->where('remarks', $remarks)->where('supply_code', $supply_code)->first();
                // $draft_item = FundDraftItem::where('particular_id', $particular_id)->where('uom', $uom)
                // ->where('remarks', $remarks)->first();
                // $wfp_draft_id = $draft_item->fund_draft_id;
                //$draft_amount = $fund_draft->draft_amounts->where('category_group_id', $title_group)->where('fund_draft_id', $fund_draft->id)->first();
                // $draft_amount = FundDraftAmount::where('category_group_id', $title_group)->where('fund_draft_id', $wfp_draft_id)->first();


                foreach ($this->current_balance as $key => $item) {
                    if ($item && $item['category_group_id'] === $title_group) {
                        if (isset($this->current_balance[$key]['current_total']) && is_numeric($this->current_balance[$key]['current_total'])) {
                            $this->current_balance[$key]['current_total'] -= $budget;
                            $this->current_balance[$key]['balance'] += $budget;

                            if ($draft_amount) {
                                $draft_amount->current_total -= $budget;
                                $draft_amount->balance += $budget;
                                $draft_amount->save();

                                if ($draft_amount->current_total <= 0) {
                                    $draft_amount->delete();
                                }
                            }

                            break;
                        }
                    }
                }

                $draft_item->delete();
                // Remove the supply at the given index
                unset($this->machines[$index]);
                // unset($this->current_balance[$index]);
                // Reset the array indices to avoid undefined index issues
                $this->machines = array_values($this->machines);
            }
        } else {
            if (isset($this->machines[$index])) {
                $budget = $this->machines[$index]['estimated_budget'];
                $title_group = $this->machines[$index]['title_group'];
                $particular_id = $this->machines[$index]['particular_id'];
                $uom = $this->machines[$index]['uom'];
                $remarks = $this->machines[$index]['remarks'];
                $supply_code = $this->machines[$index]['supply_code'];
                $fund_draft = $this->fund_allocations->where('is_supplemental', 0)->first()->fundDrafts->first();
                $draft_item = $fund_draft->draft_items->where('title_group', $title_group)->where(
                    'particular_id',
                    $particular_id
                )->where('uom', $uom)
                    ->where('remarks', $remarks)->where('supply_code', $supply_code)->first();
                // $draft_item = FundDraftItem::where('particular_id', $particular_id)->where('uom', $uom)
                // ->where('remarks', $remarks)->first();
                // $wfp_draft_id = $draft_item->fund_draft_id;
                $draft_amount = $fund_draft->draft_amounts->where(
                    'category_group_id',
                    $title_group
                )->where('fund_draft_id', $fund_draft->id)->first();
                // $draft_amount = FundDraftAmount::where('category_group_id', $title_group)->where('fund_draft_id', $wfp_draft_id)->first();


                foreach ($this->current_balance as $key => $item) {
                    if ($item['category_group_id'] === $title_group) {
                        if (isset($this->current_balance[$key]['current_total']) && is_numeric($this->current_balance[$key]['current_total'])) {
                            $this->current_balance[$key]['current_total'] -= $budget;
                            $this->current_balance[$key]['balance'] += $budget;

                            if ($draft_amount) {
                                $draft_amount->current_total -= $budget;
                                $draft_amount->balance += $budget;
                                $draft_amount->save();

                                if ($draft_amount->current_total <= 0) {
                                    $draft_amount->delete();
                                }
                            }

                            break;
                        }
                    }
                }

                $draft_item->delete();
                // Remove the supply at the given index
                unset($this->machines[$index]);
                // unset($this->current_balance[$index]);
                // Reset the array indices to avoid undefined index issues
                $this->machines = array_values($this->machines);
            }
        }
    }

    public function deleteBuilding($index)
    {
        if ($this->is_supplemental) {
            if (isset($this->buildings[$index])) {
                $budget = $this->buildings[$index]['estimated_budget'];
                $title_group = $this->buildings[$index]['title_group'];
                $particular_id = $this->buildings[$index]['particular_id'];
                $uom = $this->buildings[$index]['uom'];
                $remarks = $this->buildings[$index]['remarks'];
                $supply_code = $this->buildings[$index]['supply_code'];
                $fund_draft = $this->fund_allocations->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->first()->fundDrafts->first();
                $draft = $this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->first()->fundDrafts()->first();
                $draft_amount = FundDraftAmount::where('fund_draft_id', $draft->id)->where(
                    'category_group_id',
                    $title_group
                )->first();
                $draft_item = $fund_draft->draft_items->where('title_group', $title_group)->where(
                    'particular_id',
                    $particular_id
                )->where('uom', $uom)
                    ->where('remarks', $remarks)->where('supply_code', $supply_code)->first();
                // $draft_item = FundDraftItem::where('particular_id', $particular_id)->where('uom', $uom)
                // ->where('remarks', $remarks)->first();
                // $wfp_draft_id = $draft_item->fund_draft_id;
                //$draft_amount = $fund_draft->draft_amounts->where('category_group_id', $title_group)->where('fund_draft_id', $fund_draft->id)->first();
                // $draft_amount = FundDraftAmount::where('category_group_id', $title_group)->where('fund_draft_id', $wfp_draft_id)->first();

                foreach ($this->current_balance as $key => $item) {
                    if ($item && $item['category_group_id'] === $title_group) {
                        if (isset($this->current_balance[$key]['current_total']) && is_numeric($this->current_balance[$key]['current_total'])) {
                            $this->current_balance[$key]['current_total'] -= $budget;
                            $this->current_balance[$key]['balance'] += $budget;

                            if ($draft_amount) {
                                $draft_amount->current_total -= $budget;
                                $draft_amount->balance += $budget;
                                $draft_amount->save();

                                if ($draft_amount->current_total <= 0) {
                                    $draft_amount->delete();
                                }
                            }

                            break;
                        }
                    }
                }

                $draft_item->delete();
                // Remove the supply at the given index
                unset($this->buildings[$index]);
                // unset($this->current_balance[$index]);
                // Reset the array indices to avoid undefined index issues
                $this->buildings = array_values($this->buildings);
            }
        } else {
            if (isset($this->buildings[$index])) {
                $budget = $this->buildings[$index]['estimated_budget'];
                $title_group = $this->buildings[$index]['title_group'];
                $particular_id = $this->buildings[$index]['particular_id'];
                $uom = $this->buildings[$index]['uom'];
                $remarks = $this->buildings[$index]['remarks'];
                $supply_code = $this->buildings[$index]['supply_code'];
                $fund_draft = $this->fund_allocations->where('is_supplemental', 0)->first()->fundDrafts->first();
                $draft_item = $fund_draft->draft_items->where('title_group', $title_group)->where(
                    'particular_id',
                    $particular_id
                )->where('uom', $uom)
                    ->where('remarks', $remarks)->where('supply_code', $supply_code)->first();
                // $draft_item = FundDraftItem::where('particular_id', $particular_id)->where('uom', $uom)
                // ->where('remarks', $remarks)->first();
                // $wfp_draft_id = $draft_item->fund_draft_id;
                $draft_amount = $fund_draft->draft_amounts->where(
                    'category_group_id',
                    $title_group
                )->where('fund_draft_id', $fund_draft->id)->first();
                // $draft_amount = FundDraftAmount::where('category_group_id', $title_group)->where('fund_draft_id', $wfp_draft_id)->first();

                foreach ($this->current_balance as $key => $item) {
                    if ($item['category_group_id'] === $title_group) {
                        if (isset($this->current_balance[$key]['current_total']) && is_numeric($this->current_balance[$key]['current_total'])) {
                            $this->current_balance[$key]['current_total'] -= $budget;
                            $this->current_balance[$key]['balance'] += $budget;

                            if ($draft_amount) {
                                $draft_amount->current_total -= $budget;
                                $draft_amount->balance += $budget;
                                $draft_amount->save();

                                if ($draft_amount->current_total <= 0) {
                                    $draft_amount->delete();
                                }
                            }

                            break;
                        }
                    }
                }

                $draft_item->delete();
                // Remove the supply at the given index
                unset($this->buildings[$index]);
                // unset($this->current_balance[$index]);
                // Reset the array indices to avoid undefined index issues
                $this->buildings = array_values($this->buildings);
            }
        }
    }

    public function deletePs($index)
    {
        if ($this->is_supplemental) {
            if (isset($this->ps[$index])) {
                $budget = $this->ps[$index]['estimated_budget'];
                $title_group = $this->ps[$index]['title_group'];
                $particular_id = $this->ps[$index]['particular_id'];
                $uom = $this->ps[$index]['uom'];
                $remarks = $this->ps[$index]['remarks'];
                $supply_code = $this->ps[$index]['supply_code'];
                $fund_draft = $this->fund_allocations->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->first()->fundDrafts->first();
                $draft = $this->record->fundAllocations->where(
                    'wpf_type_id',
                    $this->wfp_param
                )->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                )->first()->fundDrafts()->first();
                $draft_amount = FundDraftAmount::where('fund_draft_id', $draft->id)->where(
                    'category_group_id',
                    $title_group
                )->first();
                $draft_item = $fund_draft->draft_items->where('title_group', $title_group)->where(
                    'particular_id',
                    $particular_id
                )->where('uom', $uom)
                    ->where('remarks', $remarks)->where('supply_code', $supply_code)->first();
                // $draft_item = FundDraftItem::where('particular_id', $particular_id)->where('uom', $uom)
                // ->where('remarks', $remarks)->first();
                // $wfp_draft_id = $draft_item->fund_draft_id;
                //$draft_amount = $fund_draft->draft_amounts->where('category_group_id', $title_group)->where('fund_draft_id', $fund_draft->id)->first();
                // $draft_amount = FundDraftAmount::where('category_group_id', $title_group)->where('fund_draft_id', $wfp_draft_id)->first();

                foreach ($this->current_balance as $key => $item) {
                    if ($item && $item['category_group_id'] === $title_group) {
                        if (isset($this->current_balance[$key]['current_total']) && is_numeric($this->current_balance[$key]['current_total'])) {
                            $this->current_balance[$key]['current_total'] -= $budget;
                            $this->current_balance[$key]['balance'] += $budget;

                            if ($draft_amount) {
                                $draft_amount->current_total -= $budget;
                                $draft_amount->balance += $budget;
                                $draft_amount->save();

                                if ($draft_amount->current_total <= 0) {
                                    $draft_amount->delete();
                                }
                            }

                            break;
                        }
                    }
                }

                $draft_item->delete();
                // Remove the supply at the given index
                unset($this->ps);
                // Reset the array indices to avoid undefined index issues
                $this->ps = array_values($this->ps);
            }
        } else {
            if (isset($this->ps[$index])) {
                $budget = $this->ps[$index]['estimated_budget'];
                $title_group = $this->ps[$index]['title_group'];
                $particular_id = $this->ps[$index]['particular_id'];
                $uom = $this->ps[$index]['uom'];
                $remarks = $this->ps[$index]['remarks'];
                $supply_code = $this->ps[$index]['supply_code'];
                $fund_draft = $this->fund_allocations->where('is_supplemental', 0)->first()->fundDrafts->first();
                $draft_item = $fund_draft->draft_items->where('title_group', $title_group)->where(
                    'particular_id',
                    $particular_id
                )->where('uom', $uom)
                    ->where('remarks', $remarks)->where('supply_code', $supply_code)->first();
                // $draft_item = FundDraftItem::where('particular_id', $particular_id)->where('uom', $uom)
                // ->where('remarks', $remarks)->first();
                // $wfp_draft_id = $draft_item->fund_draft_id;
                $draft_amount = $fund_draft->draft_amounts->where(
                    'category_group_id',
                    $title_group
                )->where('fund_draft_id', $fund_draft->id)->first();
                // $draft_amount = FundDraftAmount::where('category_group_id', $title_group)->where('fund_draft_id', $wfp_draft_id)->first();

                foreach ($this->current_balance as $key => $item) {
                    if ($item['category_group_id'] === $title_group) {
                        if (isset($this->current_balance[$key]['current_total']) && is_numeric($this->current_balance[$key]['current_total'])) {
                            $this->current_balance[$key]['current_total'] -= $budget;
                            $this->current_balance[$key]['balance'] += $budget;

                            if ($draft_amount) {
                                $draft_amount->current_total -= $budget;
                                $draft_amount->balance += $budget;
                                $draft_amount->save();

                                if ($draft_amount->current_total <= 0) {
                                    $draft_amount->delete();
                                }
                            }

                            break;
                        }
                    }
                }

                $draft_item->delete();
                // Remove the supply at the given index
                unset($this->ps);
                // Reset the array indices to avoid undefined index issues
                $this->ps = array_values($this->ps);
            }
        }
    }

    public function refreshComponent()
    {
        $this->refresh();
    }

    public function setStep($step)
    {
        $this->emit('refreshComponent');

        // dd($this->supplies_particular);
        $this->supplies_particular = null;

        $this->global_index = $step;
    }


    public function addDetail($value)
    {
        switch ($value) {
            case 1:
                $this->addSupplies();
                break;
            case 2:
                $this->addMooe();
                break;
            case 3:
                $this->addTraining();
                break;
            case 4:
                $this->addMachine();
                break;
            case 5:
                $this->addBuilding();
                break;
            case 6:
                $this->addPs();
                break;
        }
    }

    public function render(): View
    {
        $this->costCenter = CostCenter::where('id', $this->record->id)->first();
        return view('livewire.w-f-p.create-w-f-p', [
            'costCenter' => $this->costCenter,
        ]);
    }
}
