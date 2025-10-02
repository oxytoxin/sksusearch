<?php

    namespace App\Http\Livewire\WFP;

    use DB;
    use App\Models\Wfp;
    use App\Models\MfoFee;
    use App\Models\WpfType;
    use Livewire\Component;
    use App\Models\WfpDetail;
    use App\Exports\PreExport;
    use App\Exports\PreExport164T;
    use App\Models\FundAllocation;

    class GeneratePpmp extends Component
    {
        public $activeButton = 'none';
        public $showPre = false;
        public $wfp_type;
        public $ppmp_details;
        public $is_active = false;
        public $title;
        public $total;
        public $wfp_types;
        public $selectedType;
        public $fund_allocation;
        public $total_allocated;
        public $total_programmed;
        public $balance;

        public $is_q1 = false;

        public $mfoId = null;
        public $fundClusterWfpId = null;
        public $supplementalQuarterId = null;
        public $campusId = null;

        protected $queryString = ['fundClusterWfpId', 'supplementalQuarterId', 'mfoId', 'title', 'campusId','selectedType'];


        public function mount()
        {
            $this->is_active= true;
            $this->wfp_types = WpfType::all();

            if(is_null($this->selectedType)) $this->selectedType = 1;

            $this->fund_allocation = FundAllocation::selectRaw(
                'fund_allocations.wpf_type_id, category_groups.id as category_group_id,
            category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
            )
                ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
                ->when(!is_null($this->mfoId), function ($query) {
                    $query->join('m_f_o_s', 'cost_centers.m_f_o_s_id', '=', 'm_f_o_s.id')
                        ->where('m_f_o_s.id', $this->mfoId);
                })
                ->where('fund_allocations.fund_cluster_id', $this->fundClusterWfpId)
                ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
                ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
                ->when(is_null($this->supplementalQuarterId), function ($query) {
                    $query->where('fund_allocations.is_supplemental', 0);
                })
                ->when(!is_null($this->supplementalQuarterId), function ($query) {
                    $query->where('fund_allocations.supplemental_quarter_id', $this->supplementalQuarterId);
                })
                ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name')
                ->get();

            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)
                ->where('is_approved', 1)
                ->where('fund_cluster_id', $this->fundClusterWfpId)
                ->when(!is_null($this->supplementalQuarterId), function ($query) {
                    $query->where('supplemental_quarter_id', $this->supplementalQuarterId);
                })
                ->when(is_null($this->supplementalQuarterId), function ($query) {
                    $query->where('is_supplemental', 0);
                })
                ->when(!is_null($this->mfoId) || !is_null($this->campusId), function ($query) {
                    $query->whereHas('costCenter', function ($query) {
                        $query->when($this->mfoId, function ($query) {
                            $query->where('m_f_o_s_id', $this->mfoId);
                        })
                        ->when($this->campusId, function ($query) {
                                $query->whereHas('office', function ($query) {
                                    $query->where('campus_id', $this->campusId);
                                });
                        });
                    });
                });
        })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->select(
                    'wfp_details.category_group_id as category_group_id',
                    'category_items.uacs_code as uacs',
                    'category_items.name as item_name',
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs')
                )
                ->groupBy('category_group_id', 'uacs', 'item_name', 'budget_uacs', 'budget_name')
                ->get();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)
                    ->where('fund_cluster_id', $this->fundClusterWfpId)
                     ->when(!is_null($this->supplementalQuarterId), function ($query) {
                    $query->where('supplemental_quarter_id', $this->supplementalQuarterId);
                    })
                    ->when(is_null($this->supplementalQuarterId), function ($query) {
                        $query->where('is_supplemental', 0);
                    })
                     ->when($this->mfoId || $this->campusId, function ($query) {
                    $query->whereHas('costCenter', function ($query) {
                        $query->when($this->mfoId, function ($query) {
                            $query->where('m_f_o_s_id', $this->mfoId);
                        })
                        ->when($this->campusId, function ($query) {
                                $query->whereHas('office', function ($query) {
                                    $query->where('campus_id', $this->campusId);
                                });
                        });
                    });
                })
                    ->where('is_approved', 1);
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;
        }

        //101
        public function sksuPpmp()
        {
            $this->is_active = false;
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Sultan Kudarat State University';

            $this->fund_allocation = FundAllocation::selectRaw(
                'fund_allocations.wpf_type_id, category_groups.id as category_group_id,
            category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
            )
                ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
                ->where('fund_allocations.fund_cluster_id', 1)
                ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
                ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
                ->where('fund_allocations.is_supplemental', 0)
                ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name')
                ->get();


            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 1)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->select(
                    'wfp_details.category_group_id as category_group_id',
                    'category_items.uacs_code as uacs',
                    'category_items.name as item_name',
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs')
                )
                ->groupBy('category_group_id', 'uacs', 'item_name', 'budget_uacs', 'budget_name')
                ->get();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 1)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;


            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 1)->where('is_approved', 1);
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 1)->where('is_approved', 1);
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function export101()
        {
            switch ($this->title) {
                case 'Sultan Kudarat State University':
                    $this->sksuPpmp();
                    return \Excel::download(new PreExport($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '101-'.$this->title.'.xlsx');
                    break;
                case 'General Admission and Support Services':
                    $this->gasPpmp();
                    return \Excel::download(new PreExport($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '101-'.$this->title.'.xlsx');
                    break;
                case 'Higher Education Services':
                    $this->hesPpmp();
                    return \Excel::download(new PreExport($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '101-'.$this->title.'.xlsx');
                    break;
                case 'Advanced Education Services':
                    $this->aesPpmp();
                    return \Excel::download(new PreExport($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '101-'.$this->title.'.xlsx');
                    break;
                case 'Research and Development':
                    $this->rdPpmp();
                    return \Excel::download(new PreExport($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '101-'.$this->title.'.xlsx');
                    break;
                case 'Extension Services':
                    $this->extensionPpmp();
                    return \Excel::download(new PreExport($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '101-'.$this->title.'.xlsx');
                    break;
                case 'Local Fund Projects':
                    $this->lfPpmp();
                    return \Excel::download(new PreExport($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '101-'.$this->title.'.xlsx');
                    break;
            }
        }

        public function gasPpmp()
        {
            $this->is_active = false;
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'General Admission and Support Services';

            $this->fund_allocation = FundAllocation::selectRaw(
                'fund_allocations.wpf_type_id, category_groups.id as category_group_id,
            category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
            )
                ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('m_f_o_s', 'cost_centers.m_f_o_s_id', '=', 'm_f_o_s.id')
                ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
                ->where('fund_allocations.fund_cluster_id', 1)
                ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
                ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
                ->where('fund_allocations.is_supplemental', 0)
                ->where('m_f_o_s.id', 1)
                ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name')
                ->get();


            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 1)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    'wfp_details.category_group_id as category_group_id',
                    'category_items.uacs_code as uacs',
                    'category_items.name as item_name',
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs')
                )
                ->where('cost_centers.m_f_o_s_id', 1)
                ->groupBy('category_group_id', 'uacs', 'item_name', 'budget_uacs', 'budget_name')
                ->get();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 1)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->where('m_f_o_s_id', 1);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 1)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 1);
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 1)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 1);
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();

        }

        public function hesPpmp()
        {
            $this->is_active = false;
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Higher Education Services';

            $this->fund_allocation = FundAllocation::selectRaw(
                'fund_allocations.wpf_type_id, category_groups.id as category_group_id,
            category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
            )
                ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('m_f_o_s', 'cost_centers.m_f_o_s_id', '=', 'm_f_o_s.id')
                ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
                ->where('fund_allocations.fund_cluster_id', 1)
                ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
                ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
                ->where('fund_allocations.is_supplemental', 0)
                ->where('m_f_o_s.id', 2)
                ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name')
                ->get();


            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('fund_cluster_id', 1)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    'wfp_details.category_group_id as category_group_id',
                    'category_items.uacs_code as uacs',
                    'category_items.name as item_name',
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs')
                )
                ->where('cost_centers.m_f_o_s_id', 2)
                ->groupBy('category_group_id', 'uacs', 'item_name', 'budget_uacs', 'budget_name')
                ->get();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('fund_cluster_id', 1)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->where('m_f_o_s_id', 2);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 1)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 2);
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 1)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 2);
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function aesPpmp()
        {
            $this->is_active = false;
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Advanced Education Services';

            $this->fund_allocation = FundAllocation::selectRaw(
                'fund_allocations.wpf_type_id, category_groups.id as category_group_id,
            category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
            )
                ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('m_f_o_s', 'cost_centers.m_f_o_s_id', '=', 'm_f_o_s.id')
                ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
                ->where('fund_allocations.fund_cluster_id', 1)
                ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
                ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
                ->where('fund_allocations.is_supplemental', 0)
                ->where('m_f_o_s.id', 3)
                ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name')
                ->get();


            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('fund_cluster_id', 1)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    'wfp_details.category_group_id as category_group_id',
                    'category_items.uacs_code as uacs',
                    'category_items.name as item_name',
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs')
                )
                ->where('cost_centers.m_f_o_s_id', 3)
                ->groupBy('category_group_id', 'uacs', 'item_name', 'budget_uacs', 'budget_name')
                ->get();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('fund_cluster_id', 1)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->where('m_f_o_s_id', 3);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 1)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 3);
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 1)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 3);
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function rdPpmp()
        {
            $this->is_active = false;
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Research and Development';

            $this->fund_allocation = FundAllocation::selectRaw(
                'fund_allocations.wpf_type_id, category_groups.id as category_group_id,
            category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
            )
                ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('m_f_o_s', 'cost_centers.m_f_o_s_id', '=', 'm_f_o_s.id')
                ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
                ->where('fund_allocations.fund_cluster_id', 1)
                ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
                ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
                ->where('fund_allocations.is_supplemental', 0)
                ->where('m_f_o_s.id', 4)
                ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name')
                ->get();


            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('fund_cluster_id', 1)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    'wfp_details.category_group_id as category_group_id',
                    'category_items.uacs_code as uacs',
                    'category_items.name as item_name',
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs')
                )
                ->where('cost_centers.m_f_o_s_id', 4)
                ->groupBy('category_group_id', 'uacs', 'item_name', 'budget_uacs', 'budget_name')
                ->get();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('fund_cluster_id', 1)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->where('m_f_o_s_id', 4);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 1)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 4);
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 1)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 4);
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function extensionPpmp()
        {
            $this->is_active = false;
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Extension Services';

            $this->fund_allocation = FundAllocation::selectRaw(
                'fund_allocations.wpf_type_id, category_groups.id as category_group_id,
            category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
            )
                ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('m_f_o_s', 'cost_centers.m_f_o_s_id', '=', 'm_f_o_s.id')
                ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
                ->where('fund_allocations.fund_cluster_id', 1)
                ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
                ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
                ->where('fund_allocations.is_supplemental', 0)
                ->where('m_f_o_s.id', 5)
                ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name')
                ->get();


            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('fund_cluster_id', 1)
                    ->where('is_supplemental', 0);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    'wfp_details.category_group_id as category_group_id',
                    'category_items.uacs_code as uacs',
                    'category_items.name as item_name',
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs')
                )
                ->where('cost_centers.m_f_o_s_id', 5)
                ->groupBy('category_group_id', 'uacs', 'item_name', 'budget_uacs', 'budget_name')
                ->get();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('fund_cluster_id', 1)
                    ->where('is_supplemental', 0)
                    ->whereHas('costCenter', function ($query) {
                        $query->where('m_f_o_s_id', 5);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 1)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 5);
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 1)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 5);
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function lfPpmp()
        {
            $this->is_active = false;
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Local Fund Projects';

            $this->fund_allocation = FundAllocation::selectRaw(
                'fund_allocations.wpf_type_id, category_groups.id as category_group_id,
            category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
            )
                ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('m_f_o_s', 'cost_centers.m_f_o_s_id', '=', 'm_f_o_s.id')
                ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
                ->where('fund_allocations.fund_cluster_id', 1)
                ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
                ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
                ->where('fund_allocations.is_supplemental', 0)
                ->whereIn('m_f_o_s.id', [6, 7])
                ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name')
                ->get();


            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('fund_cluster_id', 1)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    'wfp_details.category_group_id as category_group_id',
                    'category_items.uacs_code as uacs',
                    'category_items.name as item_name',
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs')
                )
                ->whereIn('cost_centers.m_f_o_s_id', [6, 7])
                ->groupBy('category_group_id', 'uacs', 'item_name', 'budget_uacs', 'budget_name')
                ->get();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('fund_cluster_id', 1)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->whereIn('m_f_o_s_id', [6, 7]);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 1)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 6);
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 1)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 6);
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        //161
        public function sksuPpmp161()
        {
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Sultan Kudarat State University';

            $this->fund_allocation = FundAllocation::selectRaw(
                'fund_allocations.wpf_type_id, category_groups.id as category_group_id,
            category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
            )
                ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
                ->where('fund_allocations.fund_cluster_id', 3)
                ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
                ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
                ->where('fund_allocations.is_supplemental', 0)
                ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name')
                ->get();


            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('fund_cluster_id', 3)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->select(
                    'wfp_details.category_group_id as category_group_id',
                    'category_items.uacs_code as uacs',
                    'category_items.name as item_name',
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs')
                )
                ->groupBy('category_group_id', 'uacs', 'item_name', 'budget_uacs', 'budget_name')
                ->get();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('fund_cluster_id', 3)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 2)->where('is_approved', 1);
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 2)->where('is_approved', 1);
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function export161()
        {
            switch ($this->title) {
                case 'Sultan Kudarat State University':
                    $this->sksuPpmp161();
                    return \Excel::download(new PreExport($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '161-'.$this->title.'.xlsx');
                    break;
                case 'General Admission and Support Services':
                    $this->gasPpmp161();
                    return \Excel::download(new PreExport($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '161-'.$this->title.'.xlsx');
                    break;
                case 'Higher Education Services':
                    $this->hesPpmp161();
                    return \Excel::download(new PreExport($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '161-'.$this->title.'.xlsx');
                    break;
                case 'Advanced Education Services':
                    $this->aesPpmp161();
                    return \Excel::download(new PreExport($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '161-'.$this->title.'.xlsx');
                    break;
                case 'Research and Development':
                    $this->rdPpmp161();
                    return \Excel::download(new PreExport($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '161-'.$this->title.'.xlsx');
                    break;
                case 'Extension Services':
                    $this->extensionPpmp161();
                    return \Excel::download(new PreExport($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '161-'.$this->title.'.xlsx');
                    break;
                case 'Local Fund Projects':
                    $this->lfPpmp161();
                    return \Excel::download(new PreExport($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '161-'.$this->title.'.xlsx');
                    break;
            }
        }

        public function gasPpmp161()
        {
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'General Admission and Support Services';

            $this->fund_allocation = FundAllocation::selectRaw(
                'fund_allocations.wpf_type_id, category_groups.id as category_group_id,
            category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
            )
                ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('m_f_o_s', 'cost_centers.m_f_o_s_id', '=', 'm_f_o_s.id')
                ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
                ->where('fund_allocations.fund_cluster_id', 3)
                ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
                ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
                ->where('fund_allocations.is_supplemental', 0)
                ->where('m_f_o_s.id', 1)
                ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name')
                ->get();


            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('fund_cluster_id', 3)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    'wfp_details.category_group_id as category_group_id',
                    'category_items.uacs_code as uacs',
                    'category_items.name as item_name',
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs')
                )
                ->where('cost_centers.m_f_o_s_id', 1)
                ->groupBy('category_group_id', 'uacs', 'item_name', 'budget_uacs', 'budget_name')
                ->get();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('fund_cluster_id', 3)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->where('m_f_o_s_id', 1);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 2)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 1);
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 2)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 1);
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function hesPpmp161()
        {
            // $this->is_active = false;
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Higher Education Services';

            $this->fund_allocation = FundAllocation::selectRaw(
                'fund_allocations.wpf_type_id, category_groups.id as category_group_id,
            category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
            )
                ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('m_f_o_s', 'cost_centers.m_f_o_s_id', '=', 'm_f_o_s.id')
                ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
                ->where('fund_allocations.fund_cluster_id', 3)
                ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
                ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
                ->where('fund_allocations.is_supplemental', 0)
                ->where('m_f_o_s.id', 2)
                ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name')
                ->get();


            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('fund_cluster_id', 3)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    'wfp_details.category_group_id as category_group_id',
                    'category_items.uacs_code as uacs',
                    'category_items.name as item_name',
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs')
                )
                ->where('cost_centers.m_f_o_s_id', 2)
                ->groupBy('category_group_id', 'uacs', 'item_name', 'budget_uacs', 'budget_name')
                ->get();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('fund_cluster_id', 3)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->where('m_f_o_s_id', 2);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 2)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 2);
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 2)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 2);
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function aesPpmp161()
        {
            // $this->is_active = false;
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Advanced Education Services';

            $this->fund_allocation = FundAllocation::selectRaw(
                'fund_allocations.wpf_type_id, category_groups.id as category_group_id,
            category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
            )
                ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('m_f_o_s', 'cost_centers.m_f_o_s_id', '=', 'm_f_o_s.id')
                ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
                ->where('fund_allocations.fund_cluster_id', 3)
                ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
                ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
                ->where('fund_allocations.is_supplemental', 0)
                ->where('m_f_o_s.id', 3)
                ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name')
                ->get();


            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('fund_cluster_id', 3)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    'wfp_details.category_group_id as category_group_id',
                    'category_items.uacs_code as uacs',
                    'category_items.name as item_name',
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs')
                )
                ->where('cost_centers.m_f_o_s_id', 3)
                ->groupBy('category_group_id', 'uacs', 'item_name', 'budget_uacs', 'budget_name')
                ->get();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('fund_cluster_id', 3)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->where('m_f_o_s_id', 3);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 2)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 3);
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 2)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 3);
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function rdPpmp161()
        {
            $this->is_active = false;
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Research and Development';

            $this->fund_allocation = FundAllocation::selectRaw(
                'fund_allocations.wpf_type_id, category_groups.id as category_group_id,
            category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
            )
                ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('m_f_o_s', 'cost_centers.m_f_o_s_id', '=', 'm_f_o_s.id')
                ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
                ->where('fund_allocations.fund_cluster_id', 3)
                ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
                ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
                ->where('fund_allocations.is_supplemental', 0)
                ->where('m_f_o_s.id', 4)
                ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name')
                ->get();


            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('fund_cluster_id', 3)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    'wfp_details.category_group_id as category_group_id',
                    'category_items.uacs_code as uacs',
                    'category_items.name as item_name',
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs')
                )
                ->where('cost_centers.m_f_o_s_id', 4)
                ->groupBy('category_group_id', 'uacs', 'item_name', 'budget_uacs', 'budget_name')
                ->get();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('fund_cluster_id', 3)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->where('m_f_o_s_id', 4);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 2)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 4);
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 2)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 4);
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function extensionPpmp161()
        {
            $this->is_active = false;
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Extension Services';

            $this->fund_allocation = FundAllocation::selectRaw(
                'fund_allocations.wpf_type_id, category_groups.id as category_group_id,
            category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
            )
                ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('m_f_o_s', 'cost_centers.m_f_o_s_id', '=', 'm_f_o_s.id')
                ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
                ->where('fund_allocations.fund_cluster_id', 3)
                ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
                ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
                ->where('fund_allocations.is_supplemental', 0)
                ->where('m_f_o_s.id', 5)
                ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name')
                ->get();


            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('fund_cluster_id', 3)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    'wfp_details.category_group_id as category_group_id',
                    'category_items.uacs_code as uacs',
                    'category_items.name as item_name',
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs')
                )
                ->where('cost_centers.m_f_o_s_id', 5)
                ->groupBy('category_group_id', 'uacs', 'item_name', 'budget_uacs', 'budget_name')
                ->get();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('fund_cluster_id', 3)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->where('m_f_o_s_id', 5);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 2)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 5);
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 2)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 5);
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function lfPpmp161()
        {
            $this->is_active = false;
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Local Fund Projects';

            $this->fund_allocation = FundAllocation::selectRaw(
                'fund_allocations.wpf_type_id, category_groups.id as category_group_id,
            category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
            )
                ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('m_f_o_s', 'cost_centers.m_f_o_s_id', '=', 'm_f_o_s.id')
                ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
                ->where('fund_allocations.fund_cluster_id', 3)
                ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
                ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
                ->where('fund_allocations.is_supplemental', 0)
                ->where('m_f_o_s.id', 6)
                ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name')
                ->get();


            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('fund_cluster_id', 3)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    'wfp_details.category_group_id as category_group_id',
                    'category_items.uacs_code as uacs',
                    'category_items.name as item_name',
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs')
                )
                ->where('cost_centers.m_f_o_s_id', 6)
                ->groupBy('category_group_id', 'uacs', 'item_name', 'budget_uacs', 'budget_name')
                ->get();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('fund_cluster_id', 3)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->where('m_f_o_s_id', 6);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 2)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 6);
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 2)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 6);
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        //163
        public function sksuPpmp163()
        {
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Sultan Kudarat State University';

            $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
                ->where('mfo_fees.fund_cluster_id', 2)
                ->where('is_supplemental', 0)
                ->where('wpf_type_id', $this->selectedType)
                ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
                ->whereHas('costCenter.wfp')
                ->get();


            $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 2)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;


            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 2)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
                )
                ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
                ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
                ->get();

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 3);
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 3);
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }


        public function export163()
        {
            switch ($this->title) {
                case 'Sultan Kudarat State University':
                    $this->sksuPpmp163();
                    return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '163-'.$this->title.'.xlsx');
                    break;
                case 'ACCESS Campus':
                    $this->accessPpmp163();
                    return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '163-'.$this->title.'.xlsx');
                    break;
                case 'Tacurong Campus':
                    $this->tacurongPpmp163();
                    return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '163-'.$this->title.'.xlsx');
                    break;
                case 'Isulan Campus':
                    $this->isulanPpmp163();
                    return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '163-'.$this->title.'.xlsx');
                    break;
                case 'Kalamansig Campus':
                    $this->kalamansigPpmp163();
                    return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '163-'.$this->title.'.xlsx');
                    break;
                case 'Lutayan Campus':
                    $this->lutayanPpmp163();
                    return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '163-'.$this->title.'.xlsx');
                    break;
                case 'Bagumbayan Campus':
                    $this->bagumbayanPpmp163();
                    return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '163-'.$this->title.'.xlsx');
                    break;
            }
        }

        public function accessPpmp163()
        {
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'ACCESS Campus';


            $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('offices', 'cost_centers.office_id', '=', 'offices.id') // Join offices table
                ->join('campuses', 'offices.campus_id', '=', 'campuses.id') // Join campuses table
                ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
                ->where('mfo_fees.fund_cluster_id', 2)
                ->where('is_supplemental', 0)
                ->where('wpf_type_id', $this->selectedType)
                ->where('campuses.id', 1) // Filter by campus_id
                ->whereHas('costCenter.wfp')
                ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
                ->get();


            $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)
                    ->where('fund_cluster_id', 2)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->whereHas('office', function ($query) {
                            $query->whereHas('campus', function ($query) {
                                $query->where('id', 1); // Filter by campus_id
                            });
                        });
                    });
            })
                ->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
                ->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;


            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)
                    ->where('fund_cluster_id', 2)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
                })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->join('offices', 'cost_centers.office_id', '=', 'offices.id') // Join with offices
                ->join('campuses', 'offices.campus_id', '=', 'campuses.id') // Join with campuses
                ->select(
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
                )
                ->where('campuses.id', 1) // Filter by campus_id
                ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
                ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
                ->get();


            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 3)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
            //         $query->where('campus_id', 1);
            //     });
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 3)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
            //         $query->where('campus_id', 1);
            //     });
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function tacurongPpmp163()
        {
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Tacurong Campus';

            $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('offices', 'cost_centers.office_id', '=', 'offices.id') // Join offices table
                ->join('campuses', 'offices.campus_id', '=', 'campuses.id') // Join campuses table
                ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
                ->where('mfo_fees.fund_cluster_id', 2)
                ->where('wpf_type_id', $this->selectedType)
                ->where('is_supplemental', 0)
                ->where('campuses.id', 2) // Filter by campus_id
                ->whereHas('costCenter.wfp')
                ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
                ->get();


            $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)
                    ->where('fund_cluster_id', 2)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->whereHas('office', function ($query) {
                            $query->whereHas('campus', function ($query) {
                                $query->where('id', 2); // Filter by campus_id
                            });
                        });
                    });
            })
                ->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
                ->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;


            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)
                    ->where('fund_cluster_id', 2)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->join('offices', 'cost_centers.office_id', '=', 'offices.id') // Join with offices
                ->join('campuses', 'offices.campus_id', '=', 'campuses.id') // Join with campuses
                ->select(
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
                )
                ->where('campuses.id', 2) // Filter by campus_id
                ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
                ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
                ->get();

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 3)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
            //         $query->where('campus_id', 2);
            //     });
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 3)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
            //         $query->where('campus_id', 2);
            //     });
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function isulanPpmp163()
        {
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Isulan Campus';

            $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('offices', 'cost_centers.office_id', '=', 'offices.id') // Join offices table
                ->join('campuses', 'offices.campus_id', '=', 'campuses.id') // Join campuses table
                ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
                ->where('mfo_fees.fund_cluster_id', 2)
                ->where('wpf_type_id', $this->selectedType)
                ->where('is_supplemental', 0)
                ->where('campuses.id', 3) // Filter by campus_id
                ->whereHas('costCenter.wfp')
                ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
                ->get();


            $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)
                    ->where('fund_cluster_id', 2)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->whereHas('office', function ($query) {
                            $query->whereHas('campus', function ($query) {
                                $query->where('id', 3); // Filter by campus_id
                            });
                        });
                    });
            })
                ->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
                ->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;


            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)
                    ->where('fund_cluster_id', 2)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->join('offices', 'cost_centers.office_id', '=', 'offices.id') // Join with offices
                ->join('campuses', 'offices.campus_id', '=', 'campuses.id') // Join with campuses
                ->select(
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
                )
                ->where('campuses.id', 3) // Filter by campus_id
                ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
                ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
                ->get();

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 3)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
            //         $query->where('campus_id', 3);
            //     });
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 3)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
            //         $query->where('campus_id', 3);
            //     });
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function kalamansigPpmp163()
        {
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Kalamansig Campus';

            $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('offices', 'cost_centers.office_id', '=', 'offices.id') // Join offices table
                ->join('campuses', 'offices.campus_id', '=', 'campuses.id') // Join campuses table
                ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
                ->where('mfo_fees.fund_cluster_id', 2)
                ->where('is_supplemental', 0)
                ->where('wpf_type_id', $this->selectedType)
                ->where('campuses.id', 4) // Filter by campus_id
                ->whereHas('costCenter.wfp')
                ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
                ->get();


            $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)
                    ->where('fund_cluster_id', 2)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->whereHas('office', function ($query) {
                            $query->whereHas('campus', function ($query) {
                                $query->where('id', 4); // Filter by campus_id
                            });
                        });
                    });
            })
                ->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
                ->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;


            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)
                    ->where('fund_cluster_id', 2)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->join('offices', 'cost_centers.office_id', '=', 'offices.id') // Join with offices
                ->join('campuses', 'offices.campus_id', '=', 'campuses.id') // Join with campuses
                ->select(
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
                )
                ->where('campuses.id', 4) // Filter by campus_id
                ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
                ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
                ->get();

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 3)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
            //         $query->where('campus_id', 4);
            //     });
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 3)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
            //         $query->where('campus_id', 4);
            //     });
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function lutayanPpmp163()
        {
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Lutayan Campus';

            $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('offices', 'cost_centers.office_id', '=', 'offices.id') // Join offices table
                ->join('campuses', 'offices.campus_id', '=', 'campuses.id') // Join campuses table
                ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
                ->where('mfo_fees.fund_cluster_id', 2)
                ->where('is_supplemental', 0)
                ->where('wpf_type_id', $this->selectedType)
                ->where('campuses.id', 5) // Filter by campus_id
                ->whereHas('costCenter.wfp')
                ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
                ->get();


            $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)
                    ->where('fund_cluster_id', 2)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->whereHas('office', function ($query) {
                            $query->whereHas('campus', function ($query) {
                                $query->where('id', 5); // Filter by campus_id
                            });
                        });
                    });
            })
                ->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
                ->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;


            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)
                    ->where('fund_cluster_id', 2)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->join('offices', 'cost_centers.office_id', '=', 'offices.id') // Join with offices
                ->join('campuses', 'offices.campus_id', '=', 'campuses.id') // Join with campuses
                ->select(
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
                )
                ->where('campuses.id', 5) // Filter by campus_id
                ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
                ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
                ->get();

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 3)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
            //         $query->where('campus_id', 5);
            //     });
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 3)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
            //         $query->where('campus_id', 5);
            //     });
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function palimbangPpmp163()
        {
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Palimbang Campus';

            $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('offices', 'cost_centers.office_id', '=', 'offices.id') // Join offices table
                ->join('campuses', 'offices.campus_id', '=', 'campuses.id') // Join campuses table
                ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
                ->where('mfo_fees.fund_cluster_id', 2)
                ->where('wpf_type_id', $this->selectedType)
                ->where('campuses.id', 6) // Filter by campus_id
                ->where('is_supplemental', 0)
                ->whereHas('costCenter.wfp')
                ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
                ->get();


            $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)
                    ->where('fund_cluster_id', 2)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->whereHas('office', function ($query) {
                            $query->whereHas('campus', function ($query) {
                                $query->where('id', 6); // Filter by campus_id
                            });
                        });
                    });
            })
                ->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
                ->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;


            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)
                    ->where('fund_cluster_id', 2)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->join('offices', 'cost_centers.office_id', '=', 'offices.id') // Join with offices
                ->join('campuses', 'offices.campus_id', '=', 'campuses.id') // Join with campuses
                ->select(
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
                )
                ->where('campuses.id', 6) // Filter by campus_id
                ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
                ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
                ->get();

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 3)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
            //         $query->where('campus_id', 6);
            //     });
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 3)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
            //         $query->where('campus_id', 6);
            //     });
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function bagumbayanPpmp163()
        {
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Bagumbayan Campus';

            $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('offices', 'cost_centers.office_id', '=', 'offices.id') // Join offices table
                ->join('campuses', 'offices.campus_id', '=', 'campuses.id') // Join campuses table
                ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
                ->where('mfo_fees.fund_cluster_id', 2)
                ->where('wpf_type_id', $this->selectedType)
                ->where('is_supplemental', 0)
                ->where('campuses.id', 7) // Filter by campus_id
                ->whereHas('costCenter.wfp')
                ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
                ->get();


            $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)
                    ->where('fund_cluster_id', 2)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->whereHas('office', function ($query) {
                            $query->whereHas('campus', function ($query) {
                                $query->where('id', 7); // Filter by campus_id
                            });
                        });
                    });
            })
                ->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
                ->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;


            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)
                    ->where('fund_cluster_id', 2)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->join('offices', 'cost_centers.office_id', '=', 'offices.id') // Join with offices
                ->join('campuses', 'offices.campus_id', '=', 'campuses.id') // Join with campuses
                ->select(
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
                )
                ->where('campuses.id', 7) // Filter by campus_id
                ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
                ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
                ->get();

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 3)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
            //         $query->where('campus_id', 7);
            //     });
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 3)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
            //         $query->where('campus_id', 7);
            //     });
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        //164T
        public function sksuPpmp164T()
        {

            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Sultan Kudarat State University';
            $this->mfoId = null;

            $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
                ->where('mfo_fees.fund_cluster_id', 4)
                ->where('wpf_type_id', $this->selectedType)
                ->where('is_supplemental', 0)
                ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
                ->get();

            $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 4)
                    ->where('is_approved', 1)
                    ->where('is_supplemental', 0);
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;


            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 4)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
                )
                ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
                ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
                ->get();

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 4)->where('is_approved', 1);
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 4)->where('is_approved', 1);
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function export164()
        {
            switch ($this->title) {
                case 'Sultan Kudarat State University':
                    $this->sksuPpmp164T();
                    return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '164T-'.$this->title.'.xlsx');
                    break;
                case 'General Admission and Support Services':
                    $this->gasPpmp164T();
                    return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '164T-'.$this->title.'.xlsx');
                    break;
                case 'Higher Education Services':
                    $this->hesPpmp164T();
                    return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '164T-'.$this->title.'.xlsx');
                    break;
                case 'Advanced Education Services':
                    $this->aesPpmp164T();
                    return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '164T-'.$this->title.'.xlsx');
                    break;
                case 'Research and Development':
                    $this->rdPpmp164T();
                    return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '164T-'.$this->title.'.xlsx');
                    break;
                case 'Extension Services':
                    $this->extensionPpmp164T();
                    return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '164T-'.$this->title.'.xlsx');
                    break;
                case 'Local Fund Projects':
                    $this->lfPpmp164T();
                    return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '164T-'.$this->title.'.xlsx');
                    break;
            }
        }

        public function gasPpmp164T()
        {
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'General Admission and Support Services';
            $this->mfoId = 1;

            $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
                ->where('mfo_fees.fund_cluster_id', 4)
                ->where('wpf_type_id', $this->selectedType)
                ->where('is_supplemental', 0)
                ->where('cost_centers.m_f_o_s_id', 1)
                ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
                ->get();

            $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 4)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->where('m_f_o_s_id', 1);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 4)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
                )
                ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
                ->where('cost_centers.m_f_o_s_id', 1)
                ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
                ->get();


            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 4)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 1);
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 4)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 1);
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();

        }

        public function hesPpmp164T()
        {
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Higher Education Services';
            $this->mfoId = 2;

            $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
                ->where('mfo_fees.fund_cluster_id', 4)
                ->where('is_supplemental', 0)
                ->where('wpf_type_id', $this->selectedType)
                ->where('cost_centers.m_f_o_s_id', 2)
                ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
                ->get();

            $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 4)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->where('m_f_o_s_id', 2);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 4)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
                )
                ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
                ->where('cost_centers.m_f_o_s_id', 2)
                ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
                ->get();

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 4)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 2);
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 4)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 2);
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function aesPpmp164T()
        {
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Advanced Education Services';
            $this->mfoId = 3;

            $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
                ->where('mfo_fees.fund_cluster_id', 4)
                ->where('wpf_type_id', $this->selectedType)
                ->where('is_supplemental', 0)
                ->where('cost_centers.m_f_o_s_id', 3)
                ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
                ->get();

            $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 4)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->where('m_f_o_s_id', 3);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 4)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
                )
                ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
                ->where('cost_centers.m_f_o_s_id', 3)
                ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
                ->get();

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 4)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 3);
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 4)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 3);
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function rdPpmp164T()
        {
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Research and Development';
            $this->mfoId = 4;

            $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
                ->where('mfo_fees.fund_cluster_id', 4)
                ->where('wpf_type_id', $this->selectedType)
                ->where('is_supplemental', 0)
                ->where('cost_centers.m_f_o_s_id', 4)
                ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
                ->get();

            $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 4)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->where('m_f_o_s_id', 4);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 4)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
                )
                ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
                ->where('cost_centers.m_f_o_s_id', 4)
                ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
                ->get();

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 4)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 4);
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 4)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 4);
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function extensionPpmp164T()
        {
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Extension Services';
            $this->mfoId = 5;

            $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
                ->where('mfo_fees.fund_cluster_id', 4)
                ->where('wpf_type_id', $this->selectedType)
                ->where('is_supplemental', 0)
                ->where('cost_centers.m_f_o_s_id', 5)
                ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
                ->get();

            $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 4)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->where('m_f_o_s_id', 5);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 4)
                    ->where('is_supplemental', 0);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
                )
                ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
                ->where('cost_centers.m_f_o_s_id', 5)
                ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
                ->get();

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 4)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 5);
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 4)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 5);
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function lfPpmp164T()
        {
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Local Fund Projects';
            $this->mfoId = 6;

            $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
                ->where('mfo_fees.fund_cluster_id', 4)
                ->where('wpf_type_id', $this->selectedType)
                ->where('is_supplemental', 0)
                ->where('cost_centers.m_f_o_s_id', 6)
                ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
                ->get();

            $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 4)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->where('m_f_o_s_id', 6);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 4)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
                )
                ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
                ->where('cost_centers.m_f_o_s_id', 6)
                ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
                ->get();

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 4)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 6);
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 4)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 6);
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        //164T-NonFHE
        public function sksuPpmp164TN()
        {
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Sultan Kudarat State University';
            $this->mfoId = null;


            $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
                ->where('mfo_fees.fund_cluster_id', 7)
                ->where('wpf_type_id', $this->selectedType)
                ->where('is_supplemental', 0)
                ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
                ->get();

            $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 7)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 7)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
                )
                ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
                ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
                ->get();

            //  $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //      $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 7)->where('is_approved', 1);
            //  })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            //  ->groupBy('category_item_id')
            //  ->get();
            //  $this->total = WfpDetail::whereHas('wfp', function($query) {
            //      $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 7)->where('is_approved', 1);
            //  })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function export164NF()
        {
            switch ($this->title) {
                case 'Sultan Kudarat State University':
                    $this->sksuPpmp164TN();
                    return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '164NFHE-'.$this->title.'.xlsx');
                    break;
                case 'General Admission and Support Services':
                    $this->gasPpmp164TN();
                    return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '164NFHE-'.$this->title.'.xlsx');
                    break;
                case 'Higher Education Services':
                    $this->hesPpmp164TN();
                    return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '164NFHE-'.$this->title.'.xlsx');
                    break;
                case 'Advanced Education Services':
                    $this->aesPpmp164TN();
                    return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '164NFHE-'.$this->title.'.xlsx');
                    break;
                case 'Research and Development':
                    $this->rdPpmp164TN();
                    return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '164NFHE-'.$this->title.'.xlsx');
                    break;
                case 'Extension Services':
                    $this->extensionPpmp164TN();
                    return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '164NFHE-'.$this->title.'.xlsx');
                    break;
                case 'Local Fund Projects':
                    $this->lfPpmp164TN();
                    return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '164NFHE-'.$this->title.'.xlsx');
                    break;
            }
        }

        public function gasPpmp164TN()
        {
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'General Admission and Support Services';
            $this->mfoId = 1;

            $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
                ->where('mfo_fees.fund_cluster_id', 7)
                ->where('wpf_type_id', $this->selectedType)
                ->where('is_supplemental', 0)
                ->where('cost_centers.m_f_o_s_id', 1)
                ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
                ->get();

            $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 7)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->where('m_f_o_s_id', 1);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 7)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
                )
                ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
                ->where('cost_centers.m_f_o_s_id', 1)
                ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
                ->get();

            //  $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //      $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 7)->whereHas('costCenter', function($query) {
            //      $query->where('m_f_o_s_id', 1);
            //      });
            //  })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            //  ->groupBy('category_item_id')
            //  ->get();
            //  $this->total = WfpDetail::whereHas('wfp', function($query) {
            //      $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 7)->whereHas('costCenter', function($query) {
            //      $query->where('m_f_o_s_id', 1);
            //      });
            //  })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function hesPpmp164TN()
        {
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Higher Education Services';
            $this->mfoId = 2;

            $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
                ->where('mfo_fees.fund_cluster_id', 7)
                ->where('wpf_type_id', $this->selectedType)
                ->where('is_supplemental', 0)
                ->where('cost_centers.m_f_o_s_id', 2)
                ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
                ->get();

            $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 7)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->where('m_f_o_s_id', 2);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 7)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
                )
                ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
                ->where('cost_centers.m_f_o_s_id', 2)
                ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
                ->get();

            //  $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //      $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 7)->whereHas('costCenter', function($query) {
            //      $query->where('m_f_o_s_id', 2);
            //      });
            //  })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            //  ->groupBy('category_item_id')
            //  ->get();
            //  $this->total = WfpDetail::whereHas('wfp', function($query) {
            //      $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 7)->whereHas('costCenter', function($query) {
            //      $query->where('m_f_o_s_id', 2);
            //      });
            //  })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function aesPpmp164TN()
        {
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Advanced Education Services';
            $this->mfoId = 3;

            $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
                ->where('mfo_fees.fund_cluster_id', 7)
                ->where('wpf_type_id', $this->selectedType)
                ->where('is_supplemental', 0)
                ->where('cost_centers.m_f_o_s_id', 3)
                ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
                ->get();

            $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 7)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->where('m_f_o_s_id', 3);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 7)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
                )
                ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
                ->where('cost_centers.m_f_o_s_id', 3)
                ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
                ->get();

            //  $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //      $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 7)->whereHas('costCenter', function($query) {
            //      $query->where('m_f_o_s_id', 3);
            //      });
            //  })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            //  ->groupBy('category_item_id')
            //  ->get();
            //  $this->total = WfpDetail::whereHas('wfp', function($query) {
            //      $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 7)->whereHas('costCenter', function($query) {
            //      $query->where('m_f_o_s_id', 3);
            //      });
            //  })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function rdPpmp164TN()
        {
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Research and Development';
            $this->mfoId = 4;

            $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
                ->where('mfo_fees.fund_cluster_id', 7)
                ->where('wpf_type_id', $this->selectedType)
                ->where('is_supplemental', 0)
                ->where('cost_centers.m_f_o_s_id', 4)
                ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
                ->get();

            $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 7)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->where('m_f_o_s_id', 4);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 7)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
                )
                ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
                ->where('cost_centers.m_f_o_s_id', 4)
                ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
                ->get();

            //  $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //      $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 7)->whereHas('costCenter', function($query) {
            //      $query->where('m_f_o_s_id', 4);
            //      });
            //  })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            //  ->groupBy('category_item_id')
            //  ->get();
            //  $this->total = WfpDetail::whereHas('wfp', function($query) {
            //      $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 7)->whereHas('costCenter', function($query) {
            //      $query->where('m_f_o_s_id', 4);
            //      });
            //  })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function extensionPpmp164TN()
        {
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Extension Services';
            $this->mfoId = 5;

            $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
                ->where('mfo_fees.fund_cluster_id', 7)
                ->where('wpf_type_id', $this->selectedType)
                ->where('is_supplemental', 0)
                ->where('cost_centers.m_f_o_s_id', 5)
                ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
                ->get();

            $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 7)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->where('m_f_o_s_id', 5);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 7)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
                )
                ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
                ->where('cost_centers.m_f_o_s_id', 5)
                ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
                ->get();

            //  $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //      $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 7)->whereHas('costCenter', function($query) {
            //      $query->where('m_f_o_s_id', 5);
            //      });
            //  })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            //  ->groupBy('category_item_id')
            //  ->get();
            //  $this->total = WfpDetail::whereHas('wfp', function($query) {
            //      $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 7)->whereHas('costCenter', function($query) {
            //      $query->where('m_f_o_s_id', 5);
            //      });
            //  })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function lfPpmp164TN()
        {
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Local Fund Projects';
            $this->mfoId = 6;

            $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
                ->where('mfo_fees.fund_cluster_id', 7)
                ->where('wpf_type_id', $this->selectedType)
                ->where('is_supplemental', 0)
                ->where('cost_centers.m_f_o_s_id', 6)
                ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
                ->get();

            $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 7)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->where('m_f_o_s_id', 6);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 7)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
                )
                ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
                ->where('cost_centers.m_f_o_s_id', 6)
                ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
                ->get();

            //  $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //      $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 7)->whereHas('costCenter', function($query) {
            //      $query->where('m_f_o_s_id', 6);
            //      });
            //  })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            //  ->groupBy('category_item_id')
            //  ->get();
            //  $this->total = WfpDetail::whereHas('wfp', function($query) {
            //      $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 7)->whereHas('costCenter', function($query) {
            //      $query->where('m_f_o_s_id', 6);
            //      });
            //  })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }


        //164OSF
        public function sksuPpmp164OSF()
        {
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Sultan Kudarat State University';
            $this->mfoId = null;

            $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
                ->where('mfo_fees.fund_cluster_id', 5)
                ->where('wpf_type_id', $this->selectedType)
                ->where('is_supplemental', 0)
                ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
                ->get();

            $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 5)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 5)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
                )
                ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
                ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
                ->get();


            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 5)->where('is_approved', 1);
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 5)->where('is_approved', 1);
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function export164OSF()
        {
            switch ($this->title) {
                case 'Sultan Kudarat State University':
                    $this->sksuPpmp164OSF();
                    return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '164NOSF-'.$this->title.'.xlsx');
                    break;
                case 'General Admission and Support Services':
                    $this->gasPpmp164OSF();
                    return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '164NOSF-'.$this->title.'.xlsx');
                    break;
                case 'Higher Education Services':
                    $this->hesPpmp164OSF();
                    return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '164NOSF-'.$this->title.'.xlsx');
                    break;
                case 'Advanced Education Services':
                    $this->aesPpmp164OSF();
                    return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '164NOSF-'.$this->title.'.xlsx');
                    break;
                case 'Research and Development':
                    $this->rdPpmp164OSF();
                    return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '164NOSF-'.$this->title.'.xlsx');
                    break;
                case 'Extension Services':
                    $this->extensionPpmp164OSF();
                    return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '164NOSF-'.$this->title.'.xlsx');
                    break;
                case 'Local Fund Projects':
                    $this->lfPpmp164OSF();
                    return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '164NOSF-'.$this->title.'.xlsx');
                    break;
            }
        }

        public function gasPpmp164OSF()
        {
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'General Admission and Support Services';
            $this->mfoId = 1;

            $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
                ->where('mfo_fees.fund_cluster_id', 5)
                ->where('wpf_type_id', $this->selectedType)
                ->where('is_supplemental', 0)
                ->where('cost_centers.m_f_o_s_id', 1)
                ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
                ->get();

            $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 5)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->where('m_f_o_s_id', 1);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 5)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
                )
                ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
                ->where('cost_centers.m_f_o_s_id', 1)
                ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
                ->get();

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 5)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 1);
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 5)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 1);
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function hesPpmp164OSF()
        {
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Higher Education Services';
            $this->mfoId = 2;

            $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
                ->where('mfo_fees.fund_cluster_id', 5)
                ->where('wpf_type_id', $this->selectedType)
                ->where('is_supplemental', 0)
                ->where('cost_centers.m_f_o_s_id', 2)
                ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
                ->get();

            $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 5)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->where('m_f_o_s_id', 2);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 5)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
                )
                ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
                ->where('cost_centers.m_f_o_s_id', 2)
                ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
                ->get();

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 5)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 2);
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 5)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 2);
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function aesPpmp164OSF()
        {
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Advanced Education Services';
            $this->mfoId = 3;

            $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
                ->where('mfo_fees.fund_cluster_id', 5)
                ->where('wpf_type_id', $this->selectedType)
                ->where('is_supplemental', 0)
                ->where('cost_centers.m_f_o_s_id', 3)
                ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
                ->get();

            $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 5)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->where('m_f_o_s_id', 3);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 5)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
                )
                ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
                ->where('cost_centers.m_f_o_s_id', 3)
                ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
                ->get();

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 5)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 3);
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 5)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 3);
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function rdPpmp164OSF()
        {
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Research and Development';
            $this->mfoId = 4;

            $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
                ->where('mfo_fees.fund_cluster_id', 5)
                ->where('wpf_type_id', $this->selectedType)
                ->where('is_supplemental', 0)
                ->where('cost_centers.m_f_o_s_id', 4)
                ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
                ->get();

            $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 5)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->where('m_f_o_s_id', 4);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 5)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
                )
                ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
                ->where('cost_centers.m_f_o_s_id', 4)
                ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
                ->get();

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 5)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 4);
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 5)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 4);
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function extensionPpmp164OSF()
        {
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Extension Services';
            $this->mfoId = 5;

            $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
                ->where('mfo_fees.fund_cluster_id', 5)
                ->where('wpf_type_id', $this->selectedType)
                ->where('is_supplemental', 0)
                ->where('cost_centers.m_f_o_s_id', 5)
                ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
                ->get();

            $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 5)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->where('m_f_o_s_id', 5);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 5)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
                )
                ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
                ->where('cost_centers.m_f_o_s_id', 5)
                ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
                ->get();

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 5)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 5);
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 5)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 5);
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function lfPpmp164OSF()
        {
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Local Fund Projects';
            $this->mfoId = 6;

            $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
                ->where('mfo_fees.fund_cluster_id', 5)
                ->where('wpf_type_id', $this->selectedType)
                ->where('is_supplemental', 0)
                ->where('cost_centers.m_f_o_s_id', 6)
                ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
                ->get();

            $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 5)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->where('m_f_o_s_id', 6);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 5)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
                )
                ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
                ->where('cost_centers.m_f_o_s_id', 6)
                ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
                ->get();

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 5)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 6);
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 5)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 6);
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        //164MF
        public function sksuPpmp164MF()
        {
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Sultan Kudarat State University';
            $this->mfoId = null;

            $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
                ->where('mfo_fees.fund_cluster_id', 6)
                ->where('wpf_type_id', $this->selectedType)
                ->where('is_supplemental', 0)
                ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
                ->get();

            $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 6)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 6)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
                )
                ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
                ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
                ->get();

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 6)->where('is_approved', 1);
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 6)->where('is_approved', 1);
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function export164MF()
        {
            switch ($this->title) {
                case 'Sultan Kudarat State University':
                    $this->sksuPpmp164MF();
                    return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '164MF-'.$this->title.'.xlsx');
                    break;
                case 'General Admission and Support Services':
                    $this->gasPpmp164MF();
                    return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '164MF-'.$this->title.'.xlsx');
                    break;
                case 'Higher Education Services':
                    $this->hesPpmp164MF();
                    return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '164MF-'.$this->title.'.xlsx');
                    break;
                case 'Advanced Education Services':
                    $this->aesPpmp164MF();
                    return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '164MF-'.$this->title.'.xlsx');
                    break;
                case 'Research and Development':
                    $this->rdPpmp164MF();
                    return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '164MF-'.$this->title.'.xlsx');
                    break;
                case 'Extension Services':
                    $this->extensionPpmp164MF();
                    return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '164MF-'.$this->title.'.xlsx');
                    break;
                case 'Local Fund Projects':
                    $this->lfPpmp164MF();
                    return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation,
                        $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance, [], [], 0,
                        false, 'none'), '164MF-'.$this->title.'.xlsx');
                    break;
            }
        }

        public function gasPpmp164MF()
        {
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'General Admission and Support Services';
            $this->mfoId = 1;
            $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
                ->where('mfo_fees.fund_cluster_id', 6)
                ->where('wpf_type_id', $this->selectedType)
                ->where('is_supplemental', 0)
                ->where('cost_centers.m_f_o_s_id', 1)
                ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
                ->get();

            $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 6)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->where('m_f_o_s_id', 1);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 6)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
                )
                ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
                ->where('cost_centers.m_f_o_s_id', 1)
                ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
                ->get();

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 6)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 1);
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 6)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 1);
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function hesPpmp164MF()
        {
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Higher Education Services';
            $this->mfoId = 2;

            $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
                ->where('mfo_fees.fund_cluster_id', 6)
                ->where('wpf_type_id', $this->selectedType)
                ->where('is_supplemental', 0)
                ->where('cost_centers.m_f_o_s_id', 2)
                ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
                ->get();

            $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 6)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->where('m_f_o_s_id', 2);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 6)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
                )
                ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
                ->where('cost_centers.m_f_o_s_id', 2)
                ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
                ->get();

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 6)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 2);
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 6)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 2);
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function aesPpmp164MF()
        {
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Advanced Education Services';
            $this->mfoId = 3;

            $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
                ->where('mfo_fees.fund_cluster_id', 6)
                ->where('wpf_type_id', $this->selectedType)
                ->where('is_supplemental', 0)
                ->where('cost_centers.m_f_o_s_id', 3)
                ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
                ->get();

            $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 6)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->where('m_f_o_s_id', 3);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 6)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
                )
                ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
                ->where('cost_centers.m_f_o_s_id', 3)
                ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
                ->get();

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 6)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 3);
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 6)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 3);
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function rdPpmp164MF()
        {
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Research and Development';
            $this->mfoId = 4;

            $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
                ->where('mfo_fees.fund_cluster_id', 6)
                ->where('wpf_type_id', $this->selectedType)
                ->where('is_supplemental', 0)
                ->where('cost_centers.m_f_o_s_id', 4)
                ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
                ->get();

            $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 6)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->where('m_f_o_s_id', 4);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 6)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
                )
                ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
                ->where('cost_centers.m_f_o_s_id', 4)
                ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
                ->get();

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 6)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 4);
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 6)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 4);
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function extensionPpmp164MF()
        {
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Extension Services';
            $this->mfoId = 5;

            $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
                ->where('mfo_fees.fund_cluster_id', 6)
                ->where('wpf_type_id', $this->selectedType)
                ->where('is_supplemental', 0)
                ->where('cost_centers.m_f_o_s_id', 5)
                ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
                ->get();

            $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 6)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->where('m_f_o_s_id', 5);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 6)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
                )
                ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
                ->where('cost_centers.m_f_o_s_id', 5)
                ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
                ->get();

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 6)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 5);
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 6)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 5);
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function lfPpmp164MF()
        {
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Local Fund Projects';
            $this->mfoId = 6;

            $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
                ->where('mfo_fees.fund_cluster_id', 6)
                ->where('wpf_type_id', $this->selectedType)
                ->where('is_supplemental', 0)
                ->where('cost_centers.m_f_o_s_id', 6)
                ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
                ->get();

            $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 6)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->where('m_f_o_s_id', 6);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 6)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
                )
                ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
                ->where('cost_centers.m_f_o_s_id', 6)
                ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
                ->get();

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 6)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 6);
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 6)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 6);
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }


        public function resetPrintable()
        {
            $this->is_active = false;
        }

        public function render()
        {
            return view('livewire.w-f-p.generate-ppmp');
        }

        // 101 - Continuing

        //101
        public function sksuPpmp101Con()
        {
            $this->is_active = false;
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Sultan Kudarat State University';


            $this->fund_allocation = FundAllocation::selectRaw(
                'fund_allocations.wpf_type_id, category_groups.id as category_group_id,
                category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
            )
                ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
                ->where('fund_allocations.fund_cluster_id', 9)
                ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
                ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
                ->where('fund_allocations.is_supplemental', 0)
                ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name')
                ->get();


            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('fund_cluster_id', 9)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->select(
                    'wfp_details.category_group_id as category_group_id',
                    'category_items.uacs_code as uacs',
                    'category_items.name as item_name',
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs')
                )
                ->groupBy('category_group_id', 'uacs', 'item_name', 'budget_uacs', 'budget_name')
                ->get();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('fund_cluster_id', 9)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated === 0 ? $this->total_programmed->total_budget : $this->total_allocated - $this->total_programmed->total_budget;


            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 9)->where('is_approved', 1);
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 9)->where('is_approved', 1);
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        //   REMOVED EXPORT

        public function gasPpmp101Con()
        {
            $this->is_active = false;
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'General Admission and Support Services';

            $this->fund_allocation = FundAllocation::selectRaw(
                'fund_allocations.wpf_type_id, category_groups.id as category_group_id,
                category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
            )
                ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('m_f_o_s', 'cost_centers.m_f_o_s_id', '=', 'm_f_o_s.id')
                ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
                ->where('fund_allocations.fund_cluster_id', 9)
                ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
                ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
                ->where('fund_allocations.is_supplemental', 0)
                ->where('m_f_o_s.id', 1)
                ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name')
                ->get();


            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('fund_cluster_id', 9)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    'wfp_details.category_group_id as category_group_id',
                    'category_items.uacs_code as uacs',
                    'category_items.name as item_name',
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs')
                )
                ->where('cost_centers.m_f_o_s_id', 1)
                ->groupBy('category_group_id', 'uacs', 'item_name', 'budget_uacs', 'budget_name')
                ->get();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('fund_cluster_id', 9)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->where('m_f_o_s_id', 1);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 9)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 1);
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 9)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 1);
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();

        }

        public function hesPpmp101Con()
        {
            $this->is_active = false;
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Higher Education Services';

            $this->fund_allocation = FundAllocation::selectRaw(
                'fund_allocations.wpf_type_id, category_groups.id as category_group_id,
                category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
            )
                ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('m_f_o_s', 'cost_centers.m_f_o_s_id', '=', 'm_f_o_s.id')
                ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
                ->where('fund_allocations.fund_cluster_id', 9)
                ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
                ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
                ->where('fund_allocations.is_supplemental', 0)
                ->where('m_f_o_s.id', 2)
                ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name')
                ->get();


            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('fund_cluster_id', 9)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    'wfp_details.category_group_id as category_group_id',
                    'category_items.uacs_code as uacs',
                    'category_items.name as item_name',
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs')
                )
                ->where('cost_centers.m_f_o_s_id', 2)
                ->groupBy('category_group_id', 'uacs', 'item_name', 'budget_uacs', 'budget_name')
                ->get();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('fund_cluster_id', 9)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->where('m_f_o_s_id', 2);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 9)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 2);
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 9)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 2);
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function aesPpmp101Con()
        {
            $this->is_active = false;
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Advanced Education Services';

            $this->fund_allocation = FundAllocation::selectRaw(
                'fund_allocations.wpf_type_id, category_groups.id as category_group_id,
                category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
            )
                ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('m_f_o_s', 'cost_centers.m_f_o_s_id', '=', 'm_f_o_s.id')
                ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
                ->where('fund_allocations.fund_cluster_id', 9)
                ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
                ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
                ->where('fund_allocations.is_supplemental', 0)
                ->where('m_f_o_s.id', 3)
                ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name')
                ->get();


            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('fund_cluster_id', 9)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    'wfp_details.category_group_id as category_group_id',
                    'category_items.uacs_code as uacs',
                    'category_items.name as item_name',
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs')
                )
                ->where('cost_centers.m_f_o_s_id', 3)
                ->groupBy('category_group_id', 'uacs', 'item_name', 'budget_uacs', 'budget_name')
                ->get();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('fund_cluster_id', 9)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->where('m_f_o_s_id', 3);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 9)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 3);
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 9)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 3);
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function rdPpmp101Con()
        {
            $this->is_active = false;
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Research and Development';

            $this->fund_allocation = FundAllocation::selectRaw(
                'fund_allocations.wpf_type_id, category_groups.id as category_group_id,
                category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
            )
                ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('m_f_o_s', 'cost_centers.m_f_o_s_id', '=', 'm_f_o_s.id')
                ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
                ->where('fund_allocations.fund_cluster_id', 9)
                ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
                ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
                ->where('fund_allocations.is_supplemental', 0)
                ->where('m_f_o_s.id', 4)
                ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name')
                ->get();


            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('fund_cluster_id', 9)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    'wfp_details.category_group_id as category_group_id',
                    'category_items.uacs_code as uacs',
                    'category_items.name as item_name',
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs')
                )
                ->where('cost_centers.m_f_o_s_id', 4)
                ->groupBy('category_group_id', 'uacs', 'item_name', 'budget_uacs', 'budget_name')
                ->get();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('fund_cluster_id', 9)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->where('m_f_o_s_id', 4);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 9)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 4);
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 9)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 4);
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function extensionPpmp101Con()
        {
            $this->is_active = false;
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Extension Services';

            $this->fund_allocation = FundAllocation::selectRaw(
                'fund_allocations.wpf_type_id, category_groups.id as category_group_id,
                category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
            )
                ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('m_f_o_s', 'cost_centers.m_f_o_s_id', '=', 'm_f_o_s.id')
                ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
                ->where('fund_allocations.fund_cluster_id', 9)
                ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
                ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
                ->where('fund_allocations.is_supplemental', 0)
                ->where('m_f_o_s.id', 5)
                ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name')
                ->get();


            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('fund_cluster_id', 9)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    'wfp_details.category_group_id as category_group_id',
                    'category_items.uacs_code as uacs',
                    'category_items.name as item_name',
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs')
                )
                ->where('cost_centers.m_f_o_s_id', 5)
                ->groupBy('category_group_id', 'uacs', 'item_name', 'budget_uacs', 'budget_name')
                ->get();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('fund_cluster_id', 9)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->where('m_f_o_s_id', 5);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 9)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 5);
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 9)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 5);
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function lfPpmp101Con()
        {
            $this->is_active = false;
            $this->is_active = true;
            $this->showPre = false;
            $this->title = 'Local Fund Projects';

            $this->fund_allocation = FundAllocation::selectRaw(
                'fund_allocations.wpf_type_id, category_groups.id as category_group_id,
                category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
            )
                ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('m_f_o_s', 'cost_centers.m_f_o_s_id', '=', 'm_f_o_s.id')
                ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
                ->where('fund_allocations.fund_cluster_id', 9)
                ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
                ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
                ->where('fund_allocations.is_supplemental', 0)
                ->where('m_f_o_s.id', 6)
                ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name')
                ->get();


            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('fund_cluster_id', 9)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    'wfp_details.category_group_id as category_group_id',
                    'category_items.uacs_code as uacs',
                    'category_items.name as item_name',
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs')
                )
                ->where('cost_centers.m_f_o_s_id', 6)
                ->groupBy('category_group_id', 'uacs', 'item_name', 'budget_uacs', 'budget_name')
                ->get();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('fund_cluster_id', 9)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1)
                    ->whereHas('costCenter', function ($query) {
                        $query->where('m_f_o_s_id', 6);
                    });
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 9)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 6);
            //     });
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 9)->whereHas('costCenter', function($query) {
            //     $query->where('m_f_o_s_id', 6);
            //     });
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        // PRE

        public function sksuPre($fundCluster)
        {
            $this->is_active = true;
            $this->showPre = true;
            $this->title = 'Sultan Kudarat State University';

            $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
                ->where('mfo_fees.fund_cluster_id', $fundCluster)
                ->where('is_supplemental', 0)
                ->where('wpf_type_id', $this->selectedType)
                ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
                ->whereHas('costCenter.wfp')
                ->get();
            // dd($this->fund_allocation);


            $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) use ($fundCluster) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', $fundCluster)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;


            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) use ($fundCluster) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', $fundCluster)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
                )
                ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
                ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
                ->get();
            // dd($this->ppmp_details);

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 3);
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 3);
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function sksuPre164T()
        {
            $this->is_active = true;
            $this->showPre = true;
            $this->title = 'Sultan Kudarat State University';

            $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
                ->where('mfo_fees.fund_cluster_id', 2)
                ->where('is_supplemental', 0)
                ->where('wpf_type_id', $this->selectedType)
                ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
                ->whereHas('costCenter.wfp')
                ->get();
            // dd($this->fund_allocation);


            $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

            $this->total_allocated = $this->fund_allocation->sum('total_allocated');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 2)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;


            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
                $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_id', 2)
                    ->where('is_supplemental', 0)
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
                )
                ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
                ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
                ->get();
            // dd($this->ppmp_details);

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 3);
            // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            // ->groupBy('category_item_id')
            // ->get();
            // $this->total = WfpDetail::whereHas('wfp', function($query) {
            //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_id', 3);
            // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }
    }
