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

class GeneratePpmpQ1 extends Component
{
    public $showPre = false;

    public $preId = null;
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

    public $non_supplemental_total_programmed;

    public $balance;

    public $is_q1 = true;

    public  $forwarded_ppmp_details = [];
    public  $non_supplemental_fund_allocation = [];

    public $activeButton = "none";




    public function mount()
    {
        $this->wfp_types = WpfType::all();
        $this->selectedType = 1;
    }

    //101
    public function sksuPpmp()
    {
        $this->is_active = false;
        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'sksuPpmp';
        $this->title = 'Sultan Kudarat State University';

        $this->forwarded_ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 1)
                ->where('is_supplemental', 0)->where('is_approved',1);
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

        $tem_fund_allocation = FundAllocation::selectRaw(
            'fund_allocations.wpf_type_id, fund_allocations.is_supplemental, category_groups.id as category_group_id,
            category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
        )
            ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
            ->where('fund_allocations.fund_cluster_w_f_p_s_id', 1)
            ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
            ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
            // ->where('fund_allocations.is_supplemental', 1 // Explicit table name)
            ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name', 'fund_allocations.is_supplemental')
            ->get();


        $this->non_supplemental_fund_allocation = $tem_fund_allocation->where('is_supplemental', 0);
        $supplemental_fund_allocation = $tem_fund_allocation->where('is_supplemental', 1)->pluck('category_group_id')->toArray();

        $this->fund_allocation = $tem_fund_allocation->filter(function ($allocation) use ($supplemental_fund_allocation) {
            return $allocation->is_supplemental || (!in_array($allocation->category_group_id, $supplemental_fund_allocation) && $allocation->is_supplemental == 0);
        });

        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 1)
                ->where('is_supplemental', 1)->where('is_approved',1);
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

        $this->total_allocated = $this->fund_allocation->where('is_supplemental', 1)->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 1)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;


        // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 1)->where('is_approved', 1);
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 1)->where('is_approved', 1);
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function export101()
    {
        switch ($this->title) {
            case 'Sultan Kudarat State University':
                $this->sksuPpmp();
                return \Excel::download(new PreExport($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '101-' . $this->title . '.xlsx');
                break;
            case 'General Admission and Support Services':
                $this->gasPpmp();
                return \Excel::download(new PreExport($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '101-' . $this->title . '.xlsx');
                break;
            case 'Higher Education Services':
                $this->hesPpmp();
                return \Excel::download(new PreExport($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '101-' . $this->title . '.xlsx');
                break;
            case 'Advanced Education Services':
                $this->aesPpmp();
                return \Excel::download(new PreExport($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '101-' . $this->title . '.xlsx');
                break;
            case 'Research and Development':
                $this->rdPpmp();
                return \Excel::download(new PreExport($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '101-' . $this->title . '.xlsx');
                break;
            case 'Extension Services':
                $this->extensionPpmp();
                return \Excel::download(new PreExport($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '101-' . $this->title . '.xlsx');
                break;
            case 'Local Fund Projects':
                $this->lfPpmp();
                return \Excel::download(new PreExport($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '101-' . $this->title . '.xlsx');
                break;
        }
    }

      public function export101Con()
    {
        switch ($this->title) {
            case 'Sultan Kudarat State University':
                $this->sksuPpmp101Con();
                return \Excel::download(new PreExport($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '101-' . $this->title . '.xlsx');
                break;
            case 'General Admission and Support Services':
                $this->gasPpmp101Con();
                return \Excel::download(new PreExport($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '101-' . $this->title . '.xlsx');
                break;
            case 'Higher Education Services':
                $this->hesPpmp101Con();
                return \Excel::download(new PreExport($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '101-' . $this->title . '.xlsx');
                break;
            case 'Advanced Education Services':
                $this->aesPpmp101Con();
                return \Excel::download(new PreExport($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '101-' . $this->title . '.xlsx');
                break;
            case 'Research and Development':
                $this->rdPpmp101Con();
                return \Excel::download(new PreExport($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '101-' . $this->title . '.xlsx');
                break;
            case 'Extension Services':
                $this->extensionPpmp101Con();
                return \Excel::download(new PreExport($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '101-' . $this->title . '.xlsx');
                break;
            case 'Local Fund Projects':
                $this->lfPpmp101Con();
                return \Excel::download(new PreExport($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '101-' . $this->title . '.xlsx');
                break;
        }
    }

    public function gasPpmp()
    {
        $this->is_active = false;
        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'gasPpmp';
        $this->title = 'General Admission and Support Services';

        $temp_fund_allocation = FundAllocation::selectRaw(
            'fund_allocations.wpf_type_id, fund_allocations.is_supplemental, category_groups.id as category_group_id,
            category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
        )
            ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('m_f_o_s', 'cost_centers.m_f_o_s_id', '=', 'm_f_o_s.id')
            ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
            ->where('fund_allocations.fund_cluster_w_f_p_s_id', 1)
            ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
            ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
            // ->where('fund_allocations.is_supplemental', 1)
            ->where('m_f_o_s.id', 1)
            ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name', 'fund_allocations.is_supplemental')
            ->get();

        $this->forwarded_ppmp_details =  $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 1)
                ->where('is_supplemental', 0)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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

        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 1)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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

        $this->non_supplemental_fund_allocation = $temp_fund_allocation->where('is_supplemental', 0);
        $supplemental_fund_allocation = $temp_fund_allocation->where('is_supplemental', 1)->pluck('category_group_id')->toArray();

        $this->fund_allocation = $temp_fund_allocation->filter(function ($allocation) use ($supplemental_fund_allocation) {
            return $allocation->is_supplemental || (!in_array($allocation->category_group_id, $supplemental_fund_allocation) && $allocation->is_supplemental == 0);
        });



        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 1)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 1);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 1)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 1);
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 1)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 1);
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();

    }

    public function hesPpmp()
    {
        $this->is_active = false;
        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'hesPpmp';
        $this->title = 'Higher Education Services';

        $temp_fund_allocation = FundAllocation::selectRaw(
            'fund_allocations.wpf_type_id,fund_allocations.is_supplemental, category_groups.id as category_group_id,
            category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
        )
            ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('m_f_o_s', 'cost_centers.m_f_o_s_id', '=', 'm_f_o_s.id')
            ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
            ->where('fund_allocations.fund_cluster_w_f_p_s_id', 1)
            ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
            ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
            // ->where('fund_allocations.is_supplemental', 1)
            ->where('m_f_o_s.id', 2)
            ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name', 'fund_allocations.is_supplemental')
            ->get();

        $this->forwarded_ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 1)
                ->where('is_supplemental', 0)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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


        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 1)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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


        $this->non_supplemental_fund_allocation = $temp_fund_allocation->where('is_supplemental', 0);
        $supplemental_fund_allocation = $temp_fund_allocation->where('is_supplemental', 1)->pluck('category_group_id')->toArray();

        $this->fund_allocation = $temp_fund_allocation->filter(function ($allocation) use ($supplemental_fund_allocation) {
            return $allocation->is_supplemental || (!in_array($allocation->category_group_id, $supplemental_fund_allocation) && $allocation->is_supplemental == 0);
        });


        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 1)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 2);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 1)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 2);
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 1)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 2);
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function aesPpmp()
    {
        $this->is_active = false;
        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'aesPpmp';
        $this->title = 'Advanced Education Services';

        $temp_fund_allocation = FundAllocation::selectRaw(
            'fund_allocations.wpf_type_id,fund_allocations.is_supplemental, category_groups.id as category_group_id,
            category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
        )
            ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('m_f_o_s', 'cost_centers.m_f_o_s_id', '=', 'm_f_o_s.id')
            ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
            ->where('fund_allocations.fund_cluster_w_f_p_s_id', 1)
            ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
            ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
            // ->where('fund_allocations.is_supplemental', 1)
            ->where('m_f_o_s.id', 3)
            ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name', 'fund_allocations.is_supplemental')
            ->get();

        $this->forwarded_ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 1)
                ->where('is_supplemental', 0)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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

        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 1)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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

        $this->non_supplemental_fund_allocation = $temp_fund_allocation->where('is_supplemental', 0);
        $supplemental_fund_allocation = $temp_fund_allocation->where('is_supplemental', 1)->pluck('category_group_id')->toArray();

        $this->fund_allocation = $temp_fund_allocation->filter(function ($allocation) use ($supplemental_fund_allocation) {
            return $allocation->is_supplemental || (!in_array($allocation->category_group_id, $supplemental_fund_allocation) && $allocation->is_supplemental == 0);
        });

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 1)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 3);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 1)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 3);
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 1)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 3);
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function rdPpmp()
    {
        $this->is_active = false;
        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'rdPpmp';
        $this->title = 'Research and Development';

        $temp_fund_allocation = FundAllocation::selectRaw(
            'fund_allocations.wpf_type_id,fund_allocations.is_supplemental, category_groups.id as category_group_id,
            category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
        )
            ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('m_f_o_s', 'cost_centers.m_f_o_s_id', '=', 'm_f_o_s.id')
            ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
            ->where('fund_allocations.fund_cluster_w_f_p_s_id', 1)
            ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
            ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
            // ->where('fund_allocations.is_supplemental', 1)
            ->where('m_f_o_s.id', 4)
            ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name', 'fund_allocations.is_supplemental')
            ->get();

        $this->forwarded_ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 1)
                ->where('is_supplemental', 0)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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


        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 1)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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

        $this->non_supplemental_fund_allocation = $temp_fund_allocation->where('is_supplemental', 0);
        $supplemental_fund_allocation = $temp_fund_allocation->where('is_supplemental', 1)->pluck('category_group_id')->toArray();

        $this->fund_allocation = $temp_fund_allocation->filter(function ($allocation) use ($supplemental_fund_allocation) {
            return $allocation->is_supplemental || (!in_array($allocation->category_group_id, $supplemental_fund_allocation) && $allocation->is_supplemental == 0);
        });
        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 1)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 4);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 1)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 4);
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 1)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 4);
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function extensionPpmp()
    {
        $this->is_active = false;
        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'extensionPpmp';
        $this->title = 'Extension Services';

        $temp_fund_allocation = FundAllocation::selectRaw(
            'fund_allocations.wpf_type_id,fund_allocations.is_supplemental, category_groups.id as category_group_id,
            category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
        )
            ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('m_f_o_s', 'cost_centers.m_f_o_s_id', '=', 'm_f_o_s.id')
            ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
            ->where('fund_allocations.fund_cluster_w_f_p_s_id', 1)
            ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
            ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
            // ->where('fund_allocations.is_supplemental', 1)
            ->where('m_f_o_s.id', 5)
            ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name', 'fund_allocations.is_supplemental')
            ->get();

        $this->forwarded_ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 1)
                ->where('is_supplemental', 0)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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

        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 1)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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

        $this->non_supplemental_fund_allocation = $temp_fund_allocation->where('is_supplemental', 0);
        $supplemental_fund_allocation = $temp_fund_allocation->where('is_supplemental', 1)->pluck('category_group_id')->toArray();

        $this->fund_allocation = $temp_fund_allocation->filter(function ($allocation) use ($supplemental_fund_allocation) {
            return $allocation->is_supplemental || (!in_array($allocation->category_group_id, $supplemental_fund_allocation) && $allocation->is_supplemental == 0);
        });

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 1)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 5);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 1)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 5);
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 1)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 5);
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function lfPpmp()
    {
        $this->is_active = false;
        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'lfPpmp';
        $this->title = 'Local Fund Projects';

        $temp_fund_allocation = FundAllocation::selectRaw(
            'fund_allocations.wpf_type_id,fund_allocations.is_supplemental, category_groups.id as category_group_id,
            category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
        )
            ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('m_f_o_s', 'cost_centers.m_f_o_s_id', '=', 'm_f_o_s.id')
            ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
            ->where('fund_allocations.fund_cluster_w_f_p_s_id', 1)
            ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
            ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
            // ->where('fund_allocations.is_supplemental', 1)
            ->where('m_f_o_s.id', 6)
            ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name', 'fund_allocations.is_supplemental')
            ->get();

        $this->forwarded_ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 1)
                ->where('is_supplemental', 0)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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



        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 1)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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

        $this->non_supplemental_fund_allocation = $temp_fund_allocation->where('is_supplemental', 0);
        $supplemental_fund_allocation = $temp_fund_allocation->where('is_supplemental', 1)->pluck('category_group_id')->toArray();

        $this->fund_allocation = $temp_fund_allocation->filter(function ($allocation) use ($supplemental_fund_allocation) {
            return $allocation->is_supplemental || (!in_array($allocation->category_group_id, $supplemental_fund_allocation) && $allocation->is_supplemental == 0);
        });


        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 1)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 6);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 1)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 6);
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 1)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 6);
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    //161
    public function sksuPpmp161()
    {
        $this->is_active = true;
        $this->showPre = true;
        $this->activeButton = 'sksuPpmp161';
        $this->title = 'Sultan Kudarat State University';

        $temp_fund_allocation = FundAllocation::selectRaw(
            'fund_allocations.wpf_type_id,fund_allocations.is_supplemental, category_groups.id as category_group_id,
            category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
        )
            ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
            ->where('fund_allocations.fund_cluster_w_f_p_s_id', 3)
            ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
            ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
            // ->where('fund_allocations.is_supplemental', 1)
            ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name', 'fund_allocations.is_supplemental')
            ->get();

        $this->forwarded_ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 3)
                ->where('is_supplemental', 0)->where('is_approved',1);
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


        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 3)
                ->where('is_supplemental', 1)->where('is_approved',1);
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


        $this->non_supplemental_fund_allocation = $temp_fund_allocation->where('is_supplemental', 0);
        $supplemental_fund_allocation = $temp_fund_allocation->where('is_supplemental', 1)->pluck('category_group_id')->toArray();

        $this->fund_allocation = $temp_fund_allocation->filter(function ($allocation) use ($supplemental_fund_allocation) {
            return $allocation->is_supplemental || (!in_array($allocation->category_group_id, $supplemental_fund_allocation) && $allocation->is_supplemental == 0);
        });


        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 3)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 2)->where('is_approved', 1);
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 2)->where('is_approved', 1);
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function export161()
    {
        switch ($this->title) {
            case 'Sultan Kudarat State University':
                $this->sksuPpmp161();
                return \Excel::download(new PreExport($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '161-' . $this->title . '.xlsx');
                break;
            case 'General Admission and Support Services':
                $this->gasPpmp161();
                return \Excel::download(new PreExport($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '161-' . $this->title . '.xlsx');
                break;
            case 'Higher Education Services':
                $this->hesPpmp161();
                return \Excel::download(new PreExport($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '161-' . $this->title . '.xlsx');
                break;
            case 'Advanced Education Services':
                $this->aesPpmp161();
                return \Excel::download(new PreExport($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '161-' . $this->title . '.xlsx');
                break;
            case 'Research and Development':
                $this->rdPpmp161();
                return \Excel::download(new PreExport($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '161-' . $this->title . '.xlsx');
                break;
            case 'Extension Services':
                $this->extensionPpmp161();
                return \Excel::download(new PreExport($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '161-' . $this->title . '.xlsx');
                break;
            case 'Local Fund Projects':
                $this->lfPpmp161();
                return \Excel::download(new PreExport($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '161-' . $this->title . '.xlsx');
                break;
        }
    }

    public function gasPpmp161()
    {
        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'gasPpmp161';
        $this->title = 'General Admission and Support Services';

        $temp_fund_allocation = FundAllocation::selectRaw(
            'fund_allocations.wpf_type_id,fund_allocations.is_supplemental, category_groups.id as category_group_id,
            category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
        )
            ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('m_f_o_s', 'cost_centers.m_f_o_s_id', '=', 'm_f_o_s.id')
            ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
            ->where('fund_allocations.fund_cluster_w_f_p_s_id', 3)
            ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
            ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
            // ->where('fund_allocations.is_supplemental', 1)
            ->where('m_f_o_s.id', 1)
            ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name', 'fund_allocations.is_supplemental')
            ->get();

        $this->forwarded_ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 3)
                ->where('is_supplemental', 0)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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


        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 3)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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

        $this->non_supplemental_fund_allocation = $temp_fund_allocation->where('is_supplemental', 0);
        $supplemental_fund_allocation = $temp_fund_allocation->where('is_supplemental', 1)->pluck('category_group_id')->toArray();

        $this->fund_allocation = $temp_fund_allocation->filter(function ($allocation) use ($supplemental_fund_allocation) {
            return $allocation->is_supplemental || (!in_array($allocation->category_group_id, $supplemental_fund_allocation) && $allocation->is_supplemental == 0);
        });

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 3)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 1);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 2)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 1);
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 2)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 1);
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function hesPpmp161()
    {
        // $this->is_active = false;
        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'hesPpmp161';
        $this->title = 'Higher Education Services';

        $temp_fund_allocation = FundAllocation::selectRaw(
            'fund_allocations.wpf_type_id,fund_allocations.is_supplemental, category_groups.id as category_group_id,
            category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
        )
            ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('m_f_o_s', 'cost_centers.m_f_o_s_id', '=', 'm_f_o_s.id')
            ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
            ->where('fund_allocations.fund_cluster_w_f_p_s_id', 3)
            ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
            ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
            // ->where('fund_allocations.is_supplemental', 1)
            ->where('m_f_o_s.id', 2)
            ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name', 'fund_allocations.is_supplemental')
            ->get();

        $this->forwarded_ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 3)
                ->where('is_supplemental', 0)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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


        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 3)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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


        $this->non_supplemental_fund_allocation = $temp_fund_allocation->where('is_supplemental', 0);
        $supplemental_fund_allocation = $temp_fund_allocation->where('is_supplemental', 1)->pluck('category_group_id')->toArray();

        $this->fund_allocation = $temp_fund_allocation->filter(function ($allocation) use ($supplemental_fund_allocation) {
            return $allocation->is_supplemental || (!in_array($allocation->category_group_id, $supplemental_fund_allocation) && $allocation->is_supplemental == 0);
        });

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 3)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 2);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 2)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 2);
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 2)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 2);
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function aesPpmp161()
    {
        // $this->is_active = false;
        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'aesPpmp161';
        $this->title = 'Advanced Education Services';

        $temp_fund_allocation = FundAllocation::selectRaw(
            'fund_allocations.wpf_type_id,fund_allocations.is_supplemental, category_groups.id as category_group_id,
            category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
        )
            ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('m_f_o_s', 'cost_centers.m_f_o_s_id', '=', 'm_f_o_s.id')
            ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
            ->where('fund_allocations.fund_cluster_w_f_p_s_id', 3)
            ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
            ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
            // ->where('fund_allocations.is_supplemental', 1)
            ->where('m_f_o_s.id', 3)
            ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name', 'fund_allocations.is_supplemental')
            ->get();

        $this->forwarded_ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 3)
                ->where('is_supplemental', 0)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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


        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 3)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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

        $this->non_supplemental_fund_allocation = $temp_fund_allocation->where('is_supplemental', 0);
        $supplemental_fund_allocation = $temp_fund_allocation->where('is_supplemental', 1)->pluck('category_group_id')->toArray();

        $this->fund_allocation = $temp_fund_allocation->filter(function ($allocation) use ($supplemental_fund_allocation) {
            return $allocation->is_supplemental || (!in_array($allocation->category_group_id, $supplemental_fund_allocation) && $allocation->is_supplemental == 0);
        });

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 3)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 3);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 2)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 3);
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 2)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 3);
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function rdPpmp161()
    {
        $this->is_active = false;
        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'rdPpmp161';
        $this->title = 'Research and Development';

        $temp_fund_allocation = FundAllocation::selectRaw(
            'fund_allocations.wpf_type_id,fund_allocations.is_supplemental, category_groups.id as category_group_id,
            category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
        )
            ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('m_f_o_s', 'cost_centers.m_f_o_s_id', '=', 'm_f_o_s.id')
            ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
            ->where('fund_allocations.fund_cluster_w_f_p_s_id', 3)
            ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
            ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
            // ->where('fund_allocations.is_supplemental', 1)
            ->where('m_f_o_s.id', 4)
            ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name', 'fund_allocations.is_supplemental')
            ->get();

        $this->forwarded_ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 3)
                ->where('is_supplemental', 0)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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


        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 3)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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


        $this->non_supplemental_fund_allocation = $temp_fund_allocation->where('is_supplemental', 0);
        $supplemental_fund_allocation = $temp_fund_allocation->where('is_supplemental', 1)->pluck('category_group_id')->toArray();

        $this->fund_allocation = $temp_fund_allocation->filter(function ($allocation) use ($supplemental_fund_allocation) {
            return $allocation->is_supplemental || (!in_array($allocation->category_group_id, $supplemental_fund_allocation) && $allocation->is_supplemental == 0);
        });

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 3)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 4);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 2)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 4);
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 2)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 4);
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function extensionPpmp161()
    {
        $this->is_active = false;
        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'extensionPpmp161';
        $this->title = 'Extension Services';

        $temp_fund_allocation = FundAllocation::selectRaw(
            'fund_allocations.wpf_type_id,fund_allocations.is_supplemental, category_groups.id as category_group_id,
            category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
        )
            ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('m_f_o_s', 'cost_centers.m_f_o_s_id', '=', 'm_f_o_s.id')
            ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
            ->where('fund_allocations.fund_cluster_w_f_p_s_id', 3)
            ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
            ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
            // ->where('fund_allocations.is_supplemental', 1)
            ->where('m_f_o_s.id', 5)
            ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name', 'fund_allocations.is_supplemental')
            ->get();

        $this->forwarded_ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 3)
                ->where('is_supplemental', 0)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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


        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 3)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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


        $this->non_supplemental_fund_allocation = $temp_fund_allocation->where('is_supplemental', 0);
        $supplemental_fund_allocation = $temp_fund_allocation->where('is_supplemental', 1)->pluck('category_group_id')->toArray();

        $this->fund_allocation = $temp_fund_allocation->filter(function ($allocation) use ($supplemental_fund_allocation) {
            return $allocation->is_supplemental || (!in_array($allocation->category_group_id, $supplemental_fund_allocation) && $allocation->is_supplemental == 0);
        });

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 3)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 5);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 2)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 5);
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 2)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 5);
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function lfPpmp161()
    {
        $this->is_active = false;
        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'lfPpmp161';
        $this->title = 'Local Fund Projects';

        $temp_fund_allocation = FundAllocation::selectRaw(
            'fund_allocations.wpf_type_id,fund_allocations.is_supplemental, category_groups.id as category_group_id,
            category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
        )
            ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('m_f_o_s', 'cost_centers.m_f_o_s_id', '=', 'm_f_o_s.id')
            ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
            ->where('fund_allocations.fund_cluster_w_f_p_s_id', 3)
            ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
            ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
            // ->where('fund_allocations.is_supplemental', 1)
            ->where('m_f_o_s.id', 6)
            ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name', 'fund_allocations.is_supplemental')
            ->get();

        $this->forwarded_ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 3)
                ->where('is_supplemental', 0)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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

        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 3)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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

        $this->non_supplemental_fund_allocation = $temp_fund_allocation->where('is_supplemental', 0);
        $supplemental_fund_allocation = $temp_fund_allocation->where('is_supplemental', 1)->pluck('category_group_id')->toArray();

        $this->fund_allocation = $temp_fund_allocation->filter(function ($allocation) use ($supplemental_fund_allocation) {
            return $allocation->is_supplemental || (!in_array($allocation->category_group_id, $supplemental_fund_allocation) && $allocation->is_supplemental == 0);
        });


        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 3)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 6);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 2)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 6);
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 2)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 6);
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    // GENERATE SKSU PPMP
    public function generateSksuppmp($fcwpsId, $title)
    {
        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'generateSksuppmp';
        $this->title = 'Sultan Kudarat State University';

        $temp_fund_allocation = FundAllocation::selectRaw('wpf_type_id,is_supplemental, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
            ->where('mfo_fees.fund_cluster_w_f_p_s_id', $fcwpsId)
            ->where('wpf_type_id', $this->selectedType)
            ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name', 'is_supplemental')
            ->whereHas('costCenter.wfp')
            ->get();

        $this->non_supplemental_fund_allocation = $temp_fund_allocation->where('is_supplemental', 0);
        $supplemental_fund_allocation = $temp_fund_allocation->where('is_supplemental', 1)->pluck('mfo_fee_id')->toArray();

        $this->fund_allocation = $temp_fund_allocation->filter(function ($allocation) use ($supplemental_fund_allocation) {
            return $allocation->is_supplemental || (!in_array($allocation->mfo_fee_id, $supplemental_fund_allocation) && $allocation->is_supplemental == 0);
        });
        $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

        $this->total_allocated = $this->fund_allocation->where('is_supplemental', 1)->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) use ($fcwpsId) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', $fcwpsId)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        $this->non_supplemental_total_programmed = WfpDetail::whereHas('wfp', function ($query)  use ($fcwpsId) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', $fcwpsId)
                ->where('is_supplemental', 0)->where('is_approved',1);
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();

        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query)  use ($fcwpsId) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', $fcwpsId)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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

        //
        $this->forwarded_ppmp_details = WfpDetail::whereHas('wfp', function ($query)  use ($fcwpsId) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', $fcwpsId)
                ->where('is_supplemental', 0)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
    }
    // -----------------

    // GENERATE SKSU PPMP PER COST CENTER MFO
    public function generateSksuppmpPerCostCenterMfo($fcwpsId, $ccMfoId, $title)
    {
        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'generateSksuppmpPerCostCenterMfo';
        $this->title = $title;

        $temp_fund_allocation = FundAllocation::selectRaw('wpf_type_id,is_supplemental, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
            ->where('mfo_fees.fund_cluster_w_f_p_s_id', $fcwpsId)
            ->where('wpf_type_id', $this->selectedType)
            // ->where('is_supplemental', 1)
            ->where('cost_centers.m_f_o_s_id', $ccMfoId)
            ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name', 'is_supplemental')
            ->get();

        $this->non_supplemental_fund_allocation = $temp_fund_allocation->where('is_supplemental', 0);
        $supplemental_fund_allocation = $temp_fund_allocation->where('is_supplemental', 1)->pluck('mfo_fee_id')->toArray();

        $this->fund_allocation = $temp_fund_allocation->filter(function ($allocation) use ($supplemental_fund_allocation) {
            return $allocation->is_supplemental || (!in_array($allocation->mfo_fee_id, $supplemental_fund_allocation) && $allocation->is_supplemental == 0);
        });


        $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();



        $this->total_allocated = $this->fund_allocation->where('is_supplemental', 1)->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) use ($fcwpsId, $ccMfoId) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', $fcwpsId)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query)  use ($ccMfoId) {
                    $query->where('m_f_o_s_id', $ccMfoId);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

         $this->non_supplemental_total_programmed = WfpDetail::whereHas('wfp', function ($query) use ($fcwpsId, $ccMfoId) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', $fcwpsId)
                ->where('is_supplemental', 0)->where('is_approved',1)
                ->whereHas('costCenter', function ($query)  use ($ccMfoId) {
                    $query->where('m_f_o_s_id', $ccMfoId);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();

        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) use ($fcwpsId) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', $fcwpsId)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
            ->select(
                \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                'category_item_budgets.name as budget_name', // Include the related field in the select
                'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
            )
            ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
            ->where('cost_centers.m_f_o_s_id',  $ccMfoId)
            ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
            ->get();

        $this->forwarded_ppmp_details = WfpDetail::whereHas('wfp', function ($query) use ($fcwpsId) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', $fcwpsId)
                ->where('is_supplemental', 0)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
            ->select(
                \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                'category_item_budgets.name as budget_name', // Include the related field in the select
                'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
            )
            ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
            ->where('cost_centers.m_f_o_s_id',  $ccMfoId)
            ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
            ->get();
    }
    // ---------------------------------------

    //163
    public function sksuPpmp163()
    {
        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'sksuPpmp163';
        $this->title = 'Sultan Kudarat State University';

        $temp_fund_allocation = FundAllocation::selectRaw('wpf_type_id,is_supplemental, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
            ->where('mfo_fees.fund_cluster_w_f_p_s_id', 2)
            ->where('wpf_type_id', $this->selectedType)
            ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name', 'is_supplemental')
            ->whereHas('costCenter.wfp')
            ->get();

        $this->non_supplemental_fund_allocation = $temp_fund_allocation->where('is_supplemental', 0);
        $supplemental_fund_allocation = $temp_fund_allocation->where('is_supplemental', 1)->pluck('category_group_id')->toArray();

        $this->fund_allocation = $temp_fund_allocation->filter(function ($allocation) use ($supplemental_fund_allocation) {
            return $allocation->is_supplemental || (!in_array($allocation->category_group_id, $supplemental_fund_allocation) && $allocation->is_supplemental == 0);
        });
        $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 2)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        $this->non_supplemental_total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 2)
                ->where('is_supplemental', 0)->where('is_approved',1);
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();

        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 2)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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

        //
        $this->forwarded_ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 2)
                ->where('is_supplemental', 0)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 3);
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 3);
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function export163()
    {
        switch ($this->title) {
            case 'Sultan Kudarat State University':
                $this->sksuPpmp163();
                return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '163-' . $this->title . '.xlsx');
                break;
           case 'Sultan Kudarat State University PRE':
                $this->sksuPre($this->preId);
                return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '163-' . $this->title . '.xlsx');
                break;
            case 'ACCESS Campus':
                $this->accessPpmp163();
                return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '163-' . $this->title . '.xlsx');
                break;
            case 'Tacurong Campus':
                $this->tacurongPpmp163();
                return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '163-' . $this->title . '.xlsx');
                break;
            case 'Isulan Campus':
                $this->isulanPpmp163();
                return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '163-' . $this->title . '.xlsx');
                break;
            case 'Kalamansig Campus':
                $this->kalamansigPpmp163();
                return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '163-' . $this->title . '.xlsx');
                break;
            case 'Lutayan Campus':
                $this->lutayanPpmp163();
                return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '163-' . $this->title . '.xlsx');
                break;
            case 'Bagumbayan Campus':
                $this->bagumbayanPpmp163();
                return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '163-' . $this->title . '.xlsx');
                break;
        }
    }

    // GENERATE 163 PER CAMPUS
    public function generate163PerCampus($campusId, $title)
    {
        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'generate163PerCampus';
        $this->title = $title;


        $temp_fund_allocation = FundAllocation::selectRaw('wpf_type_id,is_supplemental, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('offices', 'cost_centers.office_id', '=', 'offices.id') // Join offices table
            ->join('campuses', 'offices.campus_id', '=', 'campuses.id') // Join campuses table
            ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
            ->where('mfo_fees.fund_cluster_w_f_p_s_id', 2)
            ->where('wpf_type_id', $this->selectedType)
            ->where('campuses.id', $campusId) // Filter by campus_id
            ->whereHas('costCenter.wfp')
            ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name', 'is_supplemental')
            ->get();



        $this->non_supplemental_fund_allocation = $temp_fund_allocation->where('is_supplemental', 0);
        $supplemental_fund_allocation = $temp_fund_allocation->where('is_supplemental', 1)->pluck('mfo_fee_id')->toArray();

        $this->fund_allocation = $temp_fund_allocation->filter(function ($allocation) use ($supplemental_fund_allocation) {
            return $allocation->is_supplemental || (!in_array($allocation->mfo_fee_id, $supplemental_fund_allocation) && $allocation->is_supplemental == 0);
        });

        $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

        $this->total_allocated = $this->fund_allocation->where('is_supplemental', 1)->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) use ($campusId) {
            $query->where('wpf_type_id', $this->selectedType)
                ->where('fund_cluster_w_f_p_s_id', 2)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) use ($campusId) {
                    $query->whereHas('office', function ($query) use ($campusId) {
                        $query->whereHas('campus', function ($query) use ($campusId) {
                            $query->where('id', $campusId); // Filter by campus_id
                        });
                    });
                });
        })
            ->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            ->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        $this->non_supplemental_total_programmed = WfpDetail::whereHas('wfp', function ($query) use ($campusId) {
            $query->where('wpf_type_id', $this->selectedType)
                ->where('fund_cluster_w_f_p_s_id', 2)
                ->where('is_supplemental', 0)->where('is_approved',1)
                ->whereHas('costCenter', function ($query) use ($campusId) {
                    $query->whereHas('office', function ($query) use ($campusId) {
                        $query->whereHas('campus', function ($query) use ($campusId) {
                            $query->where('id', $campusId); // Filter by campus_id
                        });
                    });
                });
        })
            ->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
            ->first();


        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)
                ->where('fund_cluster_w_f_p_s_id', 2)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
            ->join('offices', 'cost_centers.office_id', '=', 'offices.id') // Join with offices
            ->join('campuses', 'offices.campus_id', '=', 'campuses.id') // Join with campuses
            ->select(
                \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                'category_item_budgets.name as budget_name', // Include the related field in the select
                'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
            )
            ->where('campuses.id', $campusId) // Filter by campus_id
            ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
            ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
            ->get();

        $this->forwarded_ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)
                ->where('fund_cluster_w_f_p_s_id', 2)
                ->where('is_supplemental', 0)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
            ->join('offices', 'cost_centers.office_id', '=', 'offices.id') // Join with offices
            ->join('campuses', 'offices.campus_id', '=', 'campuses.id') // Join with campuses
            ->select(
                \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                'category_item_budgets.name as budget_name', // Include the related field in the select
                'cost_centers.mfo_fee_id as mfo_fee_id', // Include the mfo_fee_id in the select
                \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
            )
            ->where('campuses.id', $campusId) // Filter by campus_id
            ->whereIn('cost_centers.mfo_fee_id', $mfo_ids)
            ->groupBy('budget_uacs', 'budget_name', 'mfo_fee_id')
            ->get();
    }
    //-----------------------

    public function accessPpmp163()
    {
        return $this->generate163PerCampus(1, 'ACCESS Campus');

        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'accessPpmp163';
        $this->title = 'ACCESS Campus';


        $temp_fund_allocation = FundAllocation::selectRaw('wpf_type_id,is_supplemental, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('offices', 'cost_centers.office_id', '=', 'offices.id') // Join offices table
            ->join('campuses', 'offices.campus_id', '=', 'campuses.id') // Join campuses table
            ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
            ->where('mfo_fees.fund_cluster_w_f_p_s_id', 2)
            ->where('wpf_type_id', $this->selectedType)
            ->where('campuses.id', 1) // Filter by campus_id
            ->whereHas('costCenter.wfp')
            ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name', 'is_supplemental')
            ->get();



        $this->non_supplemental_fund_allocation = $temp_fund_allocation->where('is_supplemental', 0);
        $supplemental_fund_allocation = $temp_fund_allocation->where('is_supplemental', 1)->pluck('mfo_fee_id')->toArray();

        $this->fund_allocation = $temp_fund_allocation->filter(function ($allocation) use ($supplemental_fund_allocation) {
            return $allocation->is_supplemental || (!in_array($allocation->mfo_fee_id, $supplemental_fund_allocation) && $allocation->is_supplemental == 0);
        });

        $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

        $this->total_allocated = $this->fund_allocation->where('is_supplemental', 1)->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)
                ->where('fund_cluster_w_f_p_s_id', 2)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
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

        $this->non_supplemental_total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)
                ->where('fund_cluster_w_f_p_s_id', 2)
                ->where('is_supplemental', 0)->where('is_approved',1)
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


        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)
                ->where('fund_cluster_w_f_p_s_id', 2)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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

        $this->forwarded_ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)
                ->where('fund_cluster_w_f_p_s_id', 2)
                ->where('is_supplemental', 0)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 3)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
        //         $query->where('campus_id', 1);
        //     });
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 3)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
        //         $query->where('campus_id', 1);
        //     });
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function tacurongPpmp163()
    {
        return $this->generate163PerCampus(2, 'Tacurong Campus');

        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'none';
        $this->title = 'Tacurong Campus';

        $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('offices', 'cost_centers.office_id', '=', 'offices.id') // Join offices table
            ->join('campuses', 'offices.campus_id', '=', 'campuses.id') // Join campuses table
            ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
            ->where('mfo_fees.fund_cluster_w_f_p_s_id', 2)
            ->where('wpf_type_id', $this->selectedType)
            ->where('is_supplemental', 1)
            ->where('campuses.id', 2) // Filter by campus_id
            ->whereHas('costCenter.wfp')
            ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
            ->get();


        $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)
                ->where('fund_cluster_w_f_p_s_id', 2)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
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
                ->where('fund_cluster_w_f_p_s_id', 2)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 3)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
        //         $query->where('campus_id', 2);
        //     });
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 3)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
        //         $query->where('campus_id', 2);
        //     });
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function isulanPpmp163()
    {
        return $this->generate163PerCampus(3, 'Isulan Campus');

        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'none';
        $this->title = 'Isulan Campus';

        $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('offices', 'cost_centers.office_id', '=', 'offices.id') // Join offices table
            ->join('campuses', 'offices.campus_id', '=', 'campuses.id') // Join campuses table
            ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
            ->where('mfo_fees.fund_cluster_w_f_p_s_id', 2)
            ->where('wpf_type_id', $this->selectedType)
            ->where('is_supplemental', 1)
            ->where('campuses.id', 3) // Filter by campus_id
            ->whereHas('costCenter.wfp')
            ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
            ->get();


        $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)
                ->where('fund_cluster_w_f_p_s_id', 2)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
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
                ->where('fund_cluster_w_f_p_s_id', 2)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 3)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
        //         $query->where('campus_id', 3);
        //     });
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 3)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
        //         $query->where('campus_id', 3);
        //     });
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function kalamansigPpmp163()
    {
        return $this->generate163PerCampus(4, 'Kalamansig Campus');

        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'none';
        $this->title = 'Kalamansig Campus';

        $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('offices', 'cost_centers.office_id', '=', 'offices.id') // Join offices table
            ->join('campuses', 'offices.campus_id', '=', 'campuses.id') // Join campuses table
            ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
            ->where('mfo_fees.fund_cluster_w_f_p_s_id', 2)
            ->where('is_supplemental', 1)
            ->where('wpf_type_id', $this->selectedType)
            ->where('campuses.id', 4) // Filter by campus_id
            ->whereHas('costCenter.wfp')
            ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
            ->get();


        $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)
                ->where('fund_cluster_w_f_p_s_id', 2)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
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
                ->where('fund_cluster_w_f_p_s_id', 2)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 3)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
        //         $query->where('campus_id', 4);
        //     });
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 3)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
        //         $query->where('campus_id', 4);
        //     });
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function lutayanPpmp163()
    {
        return $this->generate163PerCampus(5, 'Lutayan Campus');

        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'none';
        $this->title = 'Lutayan Campus';

        $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('offices', 'cost_centers.office_id', '=', 'offices.id') // Join offices table
            ->join('campuses', 'offices.campus_id', '=', 'campuses.id') // Join campuses table
            ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
            ->where('mfo_fees.fund_cluster_w_f_p_s_id', 2)
            ->where('is_supplemental', 1)
            ->where('wpf_type_id', $this->selectedType)
            ->where('campuses.id', 5) // Filter by campus_id
            ->whereHas('costCenter.wfp')
            ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
            ->get();


        $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)
                ->where('fund_cluster_w_f_p_s_id', 2)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
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
                ->where('fund_cluster_w_f_p_s_id', 2)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 3)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
        //         $query->where('campus_id', 5);
        //     });
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 3)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
        //         $query->where('campus_id', 5);
        //     });
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function palimbangPpmp163()
    {
        return $this->generate163PerCampus(6, 'Palimbang Campus');

        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'none';
        $this->title = 'Palimbang Campus';

        $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('offices', 'cost_centers.office_id', '=', 'offices.id') // Join offices table
            ->join('campuses', 'offices.campus_id', '=', 'campuses.id') // Join campuses table
            ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
            ->where('mfo_fees.fund_cluster_w_f_p_s_id', 2)
            ->where('wpf_type_id', $this->selectedType)
            ->where('campuses.id', 6) // Filter by campus_id
            ->where('is_supplemental', 1)
            ->whereHas('costCenter.wfp')
            ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
            ->get();


        $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)
                ->where('fund_cluster_w_f_p_s_id', 2)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
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
                ->where('fund_cluster_w_f_p_s_id', 2)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 3)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
        //         $query->where('campus_id', 6);
        //     });
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 3)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
        //         $query->where('campus_id', 6);
        //     });
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function bagumbayanPpmp163()
    {
        return $this->generate163PerCampus(7, 'Bagumbayan Campus');

        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'none';
        $this->title = 'Bagumbayan Campus';

        $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('offices', 'cost_centers.office_id', '=', 'offices.id') // Join offices table
            ->join('campuses', 'offices.campus_id', '=', 'campuses.id') // Join campuses table
            ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
            ->where('mfo_fees.fund_cluster_w_f_p_s_id', 2)
            ->where('wpf_type_id', $this->selectedType)
            ->where('is_supplemental', 1)
            ->where('campuses.id', 7) // Filter by campus_id
            ->whereHas('costCenter.wfp')
            ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
            ->get();


        $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)
                ->where('fund_cluster_w_f_p_s_id', 2)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
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
                ->where('fund_cluster_w_f_p_s_id', 2)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 3)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
        //         $query->where('campus_id', 7);
        //     });
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 3)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 6)->whereHas('office', function($query) {
        //         $query->where('campus_id', 7);
        //     });
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    //164T
    public function sksuPpmp164T()
    {
        return $this->generateSksuppmp(4, 'Sultan Kudarat State University');
        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'sksuPpmp164T';
        $this->title = 'Sultan Kudarat State University';

        $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
            ->where('mfo_fees.fund_cluster_w_f_p_s_id', 4)
            ->where('wpf_type_id', $this->selectedType)
            ->where('is_supplemental', 1)
            ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
            ->get();

        $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 4)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;


        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 4)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
        //     $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 4)->where('is_approved', 1);
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 4)->where('is_approved', 1);
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function export164()
    {
        switch ($this->title) {
            case 'Sultan Kudarat State University':
                $this->sksuPpmp164T();
                return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '164T-' . $this->title . '.xlsx');
                break;
           case 'Sultan Kudarat State University PRE':
                $this->sksuPre($this->preId);
                return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '164T-' . $this->title . '.xlsx');
                break;
            case 'General Admission and Support Services':
                $this->gasPpmp164T();
                return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '164T-' . $this->title . '.xlsx');
                break;
            case 'Higher Education Services':
                $this->hesPpmp164T();
                return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '164T-' . $this->title . '.xlsx');
                break;
            case 'Advanced Education Services':
                $this->aesPpmp164T();
                return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '164T-' . $this->title . '.xlsx');
                break;
            case 'Research and Development':
                $this->rdPpmp164T();
                return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '164T-' . $this->title . '.xlsx');
                break;
            case 'Extension Services':
                $this->extensionPpmp164T();
                return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '164T-' . $this->title . '.xlsx');
                break;
            case 'Local Fund Projects':
                $this->lfPpmp164T();
                return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '164T-' . $this->title . '.xlsx');
                break;
        }
    }

    public function gasPpmp164T()
    {
        return $this->generateSksuppmpPerCostCenterMfo(4,1,'General Admission and Support Services');

        // CODE BELOW IS NOT USER
        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'none';
        $this->title = 'General Admission and Support Services';

        $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
            ->where('mfo_fees.fund_cluster_w_f_p_s_id', 4)
            ->where('wpf_type_id', $this->selectedType)
            ->where('is_supplemental', 1)
            ->where('cost_centers.m_f_o_s_id', 1)
            ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
            ->get();

        $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 4)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 1);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 4)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 4)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 1);
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 4)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 1);
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();

    }

    public function hesPpmp164T()
    {
        return $this->generateSksuppmpPerCostCenterMfo(4,2,'Higher Education Services');

        // CODE BELOW IS NOT USE
        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'none';
        $this->title = 'Higher Education Services';

        $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
            ->where('mfo_fees.fund_cluster_w_f_p_s_id', 4)
            ->where('is_supplemental', 1)
            ->where('wpf_type_id', $this->selectedType)
            ->where('cost_centers.m_f_o_s_id', 2)
            ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
            ->get();

        $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 4)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 2);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 4)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 4)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 2);
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 4)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 2);
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function aesPpmp164T()
    {
        return $this->generateSksuppmpPerCostCenterMfo(4,3,'Advanced Education Services');

        // CODE BELOW IS NOT USE
        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'none';
        $this->title = 'Advanced Education Services';

        $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
            ->where('mfo_fees.fund_cluster_w_f_p_s_id', 4)
            ->where('wpf_type_id', $this->selectedType)
            ->where('is_supplemental', 1)
            ->where('cost_centers.m_f_o_s_id', 3)
            ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
            ->get();

        $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 4)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 3);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 4)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 4)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 3);
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 4)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 3);
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function rdPpmp164T()
    {
        return $this->generateSksuppmpPerCostCenterMfo(4,4,'Reasearch and Development');

        // CODE BELOW IS NOT USE
        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'none';
        $this->title = 'Research and Development';

        $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
            ->where('mfo_fees.fund_cluster_w_f_p_s_id', 4)
            ->where('wpf_type_id', $this->selectedType)
            ->where('is_supplemental', 1)
            ->where('cost_centers.m_f_o_s_id', 4)
            ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
            ->get();

        $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 4)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 4);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 4)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 4)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 4);
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 4)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 4);
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function extensionPpmp164T()
    {
        return $this->generateSksuppmpPerCostCenterMfo(4,5,'Extension Services');

        // CODE BELOW IS NOT USE
        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'none';
        $this->title = 'Extension Services';

        $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
            ->where('mfo_fees.fund_cluster_w_f_p_s_id', 4)
            ->where('wpf_type_id', $this->selectedType)
            ->where('is_supplemental', 1)
            ->where('cost_centers.m_f_o_s_id', 5)
            ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
            ->get();

        $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 4)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 5);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 4)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 4)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 5);
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 4)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 5);
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function lfPpmp164T()
    {
        return $this->generateSksuppmpPerCostCenterMfo(4,6,'Local Fund Projects');

        // CODE BELOW IS NOT USE
        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'none';
        $this->title = 'Local Fund Projects';

        $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
            ->where('mfo_fees.fund_cluster_w_f_p_s_id', 4)
            ->where('wpf_type_id', $this->selectedType)
            ->where('is_supplemental', 1)
            ->where('cost_centers.m_f_o_s_id', 6)
            ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
            ->get();

        $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 4)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 6);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 4)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 4)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 6);
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 4)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 6);
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    //164T-NonFHE
    public function sksuPpmp164TN()
    {
        return $this->generateSksuppmp(7,'Sultan Kudarat State University');
        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'none';
        $this->title = 'Sultan Kudarat State University';


        $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
            ->where('mfo_fees.fund_cluster_w_f_p_s_id', 7)
            ->where('wpf_type_id', $this->selectedType)
            ->where('is_supplemental', 1)
            ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
            ->get();

        $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 7)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 7)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
        //      $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 7)->where('is_approved', 1);
        //  })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        //  ->groupBy('category_item_id')
        //  ->get();
        //  $this->total = WfpDetail::whereHas('wfp', function($query) {
        //      $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 7)->where('is_approved', 1);
        //  })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function export164NF()
    {
        switch ($this->title) {
            case 'Sultan Kudarat State University':
                $this->sksuPpmp164TN();
                return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '164NFHE-' . $this->title . '.xlsx');
                break;
           case 'Sultan Kudarat State University PRE':
                $this->sksuPre($this->preId);
                return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '164NFHE-' . $this->title . '.xlsx');
                break;
            case 'General Admission and Support Services':
                $this->gasPpmp164TN();
                return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '164NFHE-' . $this->title . '.xlsx');
                break;
            case 'Higher Education Services':
                $this->hesPpmp164TN();
                return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '164NFHE-' . $this->title . '.xlsx');
                break;
            case 'Advanced Education Services':
                $this->aesPpmp164TN();
                return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '164NFHE-' . $this->title . '.xlsx');
                break;
            case 'Research and Development':
                $this->rdPpmp164TN();
                return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '164NFHE-' . $this->title . '.xlsx');
                break;
            case 'Extension Services':
                $this->extensionPpmp164TN();
                return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '164NFHE-' . $this->title . '.xlsx');
                break;
            case 'Local Fund Projects':
                $this->lfPpmp164TN();
                return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '164NFHE-' . $this->title . '.xlsx');
                break;
        }
    }

    public function gasPpmp164TN()
    {
        return $this->generateSksuppmpPerCostCenterMfo(7,1, 'General Admission and Support Services');
        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'none';
        $this->title = 'General Admission and Support Services';

        $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
            ->where('mfo_fees.fund_cluster_w_f_p_s_id', 7)
            ->where('wpf_type_id', $this->selectedType)
            ->where('is_supplemental', 1)
            ->where('cost_centers.m_f_o_s_id', 1)
            ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
            ->get();

        $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 7)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 1);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 7)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
        //      $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 7)->whereHas('costCenter', function($query) {
        //      $query->where('m_f_o_s_id', 1);
        //      });
        //  })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        //  ->groupBy('category_item_id')
        //  ->get();
        //  $this->total = WfpDetail::whereHas('wfp', function($query) {
        //      $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 7)->whereHas('costCenter', function($query) {
        //      $query->where('m_f_o_s_id', 1);
        //      });
        //  })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function hesPpmp164TN()
    {
        return $this->generateSksuppmpPerCostCenterMfo(7,2,'Higher Education Services');
        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'none';
        $this->title = 'Higher Education Services';

        $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
            ->where('mfo_fees.fund_cluster_w_f_p_s_id', 7)
            ->where('wpf_type_id', $this->selectedType)
            ->where('is_supplemental', 1)
            ->where('cost_centers.m_f_o_s_id', 2)
            ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
            ->get();

        $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 7)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 2);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 7)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
        //      $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 7)->whereHas('costCenter', function($query) {
        //      $query->where('m_f_o_s_id', 2);
        //      });
        //  })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        //  ->groupBy('category_item_id')
        //  ->get();
        //  $this->total = WfpDetail::whereHas('wfp', function($query) {
        //      $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 7)->whereHas('costCenter', function($query) {
        //      $query->where('m_f_o_s_id', 2);
        //      });
        //  })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function aesPpmp164TN()
    {
        return $this->generateSksuppmpPerCostCenterMfo(7,3,'Advanced Education Services');

        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'none';
        $this->title = 'Advanced Education Services';

        $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
            ->where('mfo_fees.fund_cluster_w_f_p_s_id', 7)
            ->where('wpf_type_id', $this->selectedType)
            ->where('is_supplemental', 1)
            ->where('cost_centers.m_f_o_s_id', 3)
            ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
            ->get();

        $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 7)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 3);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 7)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
        //      $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 7)->whereHas('costCenter', function($query) {
        //      $query->where('m_f_o_s_id', 3);
        //      });
        //  })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        //  ->groupBy('category_item_id')
        //  ->get();
        //  $this->total = WfpDetail::whereHas('wfp', function($query) {
        //      $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 7)->whereHas('costCenter', function($query) {
        //      $query->where('m_f_o_s_id', 3);
        //      });
        //  })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function rdPpmp164TN()
    {
        return $this->generateSksuppmpPerCostCenterMfo(7,4,'Research and Development');

        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'none';
        $this->title = 'Research and Development';

        $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
            ->where('mfo_fees.fund_cluster_w_f_p_s_id', 7)
            ->where('wpf_type_id', $this->selectedType)
            ->where('is_supplemental', 1)
            ->where('cost_centers.m_f_o_s_id', 4)
            ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
            ->get();

        $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 7)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 4);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 7)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
        //      $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 7)->whereHas('costCenter', function($query) {
        //      $query->where('m_f_o_s_id', 4);
        //      });
        //  })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        //  ->groupBy('category_item_id')
        //  ->get();
        //  $this->total = WfpDetail::whereHas('wfp', function($query) {
        //      $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 7)->whereHas('costCenter', function($query) {
        //      $query->where('m_f_o_s_id', 4);
        //      });
        //  })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function extensionPpmp164TN()
    {
        return $this->generateSksuppmpPerCostCenterMfo(7,5,'Extension Services');

        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'none';
        $this->title = 'Extension Services';

        $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
            ->where('mfo_fees.fund_cluster_w_f_p_s_id', 7)
            ->where('wpf_type_id', $this->selectedType)
            ->where('is_supplemental', 1)
            ->where('cost_centers.m_f_o_s_id', 5)
            ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
            ->get();

        $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 7)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 5);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 7)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
        //      $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 7)->whereHas('costCenter', function($query) {
        //      $query->where('m_f_o_s_id', 5);
        //      });
        //  })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        //  ->groupBy('category_item_id')
        //  ->get();
        //  $this->total = WfpDetail::whereHas('wfp', function($query) {
        //      $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 7)->whereHas('costCenter', function($query) {
        //      $query->where('m_f_o_s_id', 5);
        //      });
        //  })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function lfPpmp164TN()
    {
        return $this->generateSksuppmpPerCostCenterMfo(7,6,'Local Fund Projects');

        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'none';
        $this->title = 'Local Fund Projects';

        $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
            ->where('mfo_fees.fund_cluster_w_f_p_s_id', 7)
            ->where('wpf_type_id', $this->selectedType)
            ->where('is_supplemental', 1)
            ->where('cost_centers.m_f_o_s_id', 6)
            ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
            ->get();

        $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 7)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 6);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 7)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
        //      $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 7)->whereHas('costCenter', function($query) {
        //      $query->where('m_f_o_s_id', 6);
        //      });
        //  })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        //  ->groupBy('category_item_id')
        //  ->get();
        //  $this->total = WfpDetail::whereHas('wfp', function($query) {
        //      $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 7)->whereHas('costCenter', function($query) {
        //      $query->where('m_f_o_s_id', 6);
        //      });
        //  })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }


    //164OSF
    public function sksuPpmp164OSF()
    {
        return $this->generateSksuppmp(5,'Sultan Kudarat State University');
        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'none';
        $this->title = 'Sultan Kudarat State University';

        $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
            ->where('mfo_fees.fund_cluster_w_f_p_s_id', 5)
            ->where('wpf_type_id', $this->selectedType)
            ->where('is_supplemental', 1)
            ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
            ->get();

        $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 5)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 5)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
        //     $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 5)->where('is_approved', 1);
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 5)->where('is_approved', 1);
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function export164OSF()
    {
        switch ($this->title) {
            case 'Sultan Kudarat State University':
                $this->sksuPpmp164OSF();
                return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '164NOSF-' . $this->title . '.xlsx');
                break;
            case 'Sultan Kudarat State University PRE':
                $this->sksuPre($this->preId);
                return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '164NOSF-' . $this->title . '.xlsx');
                break;
            case 'General Admission and Support Services':
                $this->gasPpmp164OSF();
                return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '164NOSF-' . $this->title . '.xlsx');
                break;
            case 'Higher Education Services':
                $this->hesPpmp164OSF();
                return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '164NOSF-' . $this->title . '.xlsx');
                break;
            case 'Advanced Education Services':
                $this->aesPpmp164OSF();
                return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '164NOSF-' . $this->title . '.xlsx');
                break;
            case 'Research and Development':
                $this->rdPpmp164OSF();
                return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '164NOSF-' . $this->title . '.xlsx');
                break;
            case 'Extension Services':
                $this->extensionPpmp164OSF();
                return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '164NOSF-' . $this->title . '.xlsx');
                break;
            case 'Local Fund Projects':
                $this->lfPpmp164OSF();
                return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '164NOSF-' . $this->title . '.xlsx');
                break;
        }
    }

    public function gasPpmp164OSF()
    {
        return $this->generateSksuppmpPerCostCenterMfo(5, 1, 'General Admission and Support Services');
        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'none';
        $this->title = 'General Admission and Support Services';

        $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
            ->where('mfo_fees.fund_cluster_w_f_p_s_id', 5)
            ->where('wpf_type_id', $this->selectedType)
            ->where('is_supplemental', 1)
            ->where('cost_centers.m_f_o_s_id', 1)
            ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
            ->get();

        $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 5)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 1);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 5)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 5)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 1);
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 5)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 1);
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function hesPpmp164OSF()
    {
        return $this->generateSksuppmpPerCostCenterMfo(5, 2, 'Higher Education Services');

        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'none';
        $this->title = 'Higher Education Services';

        $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
            ->where('mfo_fees.fund_cluster_w_f_p_s_id', 5)
            ->where('wpf_type_id', $this->selectedType)
            ->where('is_supplemental', 1)
            ->where('cost_centers.m_f_o_s_id', 2)
            ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
            ->get();

        $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 5)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 2);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 5)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 5)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 2);
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 5)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 2);
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function aesPpmp164OSF()
    {
        return $this->generateSksuppmpPerCostCenterMfo(5, 3, 'Advanced Education Services');

        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'none';
        $this->title = 'Advanced Education Services';

        $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
            ->where('mfo_fees.fund_cluster_w_f_p_s_id', 5)
            ->where('wpf_type_id', $this->selectedType)
            ->where('is_supplemental', 1)
            ->where('cost_centers.m_f_o_s_id', 3)
            ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
            ->get();

        $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 5)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 3);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 5)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 5)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 3);
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 5)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 3);
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function rdPpmp164OSF()
    {
        return $this->generateSksuppmpPerCostCenterMfo(5, 4, 'Research and Development');

        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'none';
        $this->title = 'Research and Development';

        $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
            ->where('mfo_fees.fund_cluster_w_f_p_s_id', 5)
            ->where('wpf_type_id', $this->selectedType)
            ->where('is_supplemental', 1)
            ->where('cost_centers.m_f_o_s_id', 4)
            ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
            ->get();

        $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 5)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 4);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 5)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 5)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 4);
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 5)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 4);
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function extensionPpmp164OSF()
    {
        return $this->generateSksuppmpPerCostCenterMfo(5, 5, 'Extension Services');

        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'none';
        $this->title = 'Extension Services';

        $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
            ->where('mfo_fees.fund_cluster_w_f_p_s_id', 5)
            ->where('wpf_type_id', $this->selectedType)
            ->where('is_supplemental', 1)
            ->where('cost_centers.m_f_o_s_id', 5)
            ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
            ->get();

        $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 5)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 5);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 5)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 5)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 5);
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 5)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 5);
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function lfPpmp164OSF()
    {
        return $this->generateSksuppmpPerCostCenterMfo(5, 6, 'Local Fund Projects');

        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'none';
        $this->title = 'Local Fund Projects';

        $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
            ->where('mfo_fees.fund_cluster_w_f_p_s_id', 5)
            ->where('wpf_type_id', $this->selectedType)
            ->where('is_supplemental', 1)
            ->where('cost_centers.m_f_o_s_id', 6)
            ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
            ->get();

        $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 5)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 6);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 5)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 5)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 6);
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 5)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 6);
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    //164MF
    public function sksuPpmp164MF()
    {
        return $this->generateSksuppmp(6, 'Sultan Kudarat State University');

        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'none';
        $this->title = 'Sultan Kudarat State University';

        $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
            ->where('mfo_fees.fund_cluster_w_f_p_s_id', 6)
            ->where('wpf_type_id', $this->selectedType)
            ->where('is_supplemental', 1)
            ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
            ->get();

        $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 6)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 6)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
        //     $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 6)->where('is_approved', 1);
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 6)->where('is_approved', 1);
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function export164MF()
    {
        switch ($this->title) {
            case 'Sultan Kudarat State University':
                $this->sksuPpmp164MF();
                return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '164MF-' . $this->title . '.xlsx');
                break;
            case 'Sultan Kudarat State University PRE':
                $this->sksuPre($this->preId);
                return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '164MF-' . $this->title . '.xlsx');
                break;
            case 'General Admission and Support Services':
                $this->gasPpmp164MF();
                return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '164MF-' . $this->title . '.xlsx');
                break;
            case 'Higher Education Services':
                $this->hesPpmp164MF();
                return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '164MF-' . $this->title . '.xlsx');
                break;
            case 'Advanced Education Services':
                $this->aesPpmp164MF();
                return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '164MF-' . $this->title . '.xlsx');
                break;
            case 'Research and Development':
                $this->rdPpmp164MF();
                return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '164MF-' . $this->title . '.xlsx');
                break;
            case 'Extension Services':
                $this->extensionPpmp164MF();
                return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '164MF-' . $this->title . '.xlsx');
                break;
            case 'Local Fund Projects':
                $this->lfPpmp164MF();
                return \Excel::download(new PreExport164T($this->selectedType, $this->fund_allocation, $this->ppmp_details, $this->total_allocated, $this->total_programmed, $this->balance,$this->non_supplemental_fund_allocation,$this->forwarded_ppmp_details,$this->non_supplemental_total_programmed,$this->is_q1,$this->activeButton), '164MF-' . $this->title . '.xlsx');
                break;
        }
    }

    public function gasPpmp164MF()
    {
        return $this->generateSksuppmpPerCostCenterMfo(6, 1, 'General Admission and Support Services');

        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'none';
        $this->title = 'General Admission and Support Services';

        $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
            ->where('mfo_fees.fund_cluster_w_f_p_s_id', 6)
            ->where('wpf_type_id', $this->selectedType)
            ->where('is_supplemental', 1)
            ->where('cost_centers.m_f_o_s_id', 1)
            ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
            ->get();

        $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 6)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 1);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 6)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 6)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 1);
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 6)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 1);
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function hesPpmp164MF()
    {
        return $this->generateSksuppmpPerCostCenterMfo(6, 2, 'Higher Education Services');

        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'none';
        $this->title = 'Higher Education Services';

        $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
            ->where('mfo_fees.fund_cluster_w_f_p_s_id', 6)
            ->where('wpf_type_id', $this->selectedType)
            ->where('is_supplemental', 1)
            ->where('cost_centers.m_f_o_s_id', 2)
            ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
            ->get();

        $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 6)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 2);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 6)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 6)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 2);
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 6)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 2);
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function aesPpmp164MF()
    {
        return $this->generateSksuppmpPerCostCenterMfo(6, 3, 'Advanced Education Services');


        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'none';
        $this->title = 'Advanced Education Services';

        $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
            ->where('mfo_fees.fund_cluster_w_f_p_s_id', 6)
            ->where('wpf_type_id', $this->selectedType)
            ->where('is_supplemental', 1)
            ->where('cost_centers.m_f_o_s_id', 3)
            ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
            ->get();

        $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 6)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 3);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 6)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 6)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 3);
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 6)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 3);
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function rdPpmp164MF()
    {

        return $this->generateSksuppmpPerCostCenterMfo(6, 4, 'Research and Development');

        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'none';
        $this->title = 'Research and Development';

        $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
            ->where('mfo_fees.fund_cluster_w_f_p_s_id', 6)
            ->where('wpf_type_id', $this->selectedType)
            ->where('is_supplemental', 1)
            ->where('cost_centers.m_f_o_s_id', 4)
            ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
            ->get();

        $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 6)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 4);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 6)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 6)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 4);
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 6)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 4);
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function extensionPpmp164MF()
    {
        return $this->generateSksuppmpPerCostCenterMfo(6, 5, 'Extension Services');

        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'none';
        $this->title = 'Extension Services';

        $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
            ->where('mfo_fees.fund_cluster_w_f_p_s_id', 6)
            ->where('wpf_type_id', $this->selectedType)
            ->where('is_supplemental', 1)
            ->where('cost_centers.m_f_o_s_id', 5)
            ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
            ->get();

        $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 6)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 5);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 6)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 6)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 5);
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 6)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 5);
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function lfPpmp164MF()
    {
        return $this->generateSksuppmpPerCostCenterMfo(6, 6, 'Local Fund Projects');

        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'none';
        $this->title = 'Local Fund Projects';

        $this->fund_allocation = FundAllocation::selectRaw('wpf_type_id, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
            ->where('mfo_fees.fund_cluster_w_f_p_s_id', 6)
            ->where('wpf_type_id', $this->selectedType)
            ->where('is_supplemental', 1)
            ->where('cost_centers.m_f_o_s_id', 6)
            ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name')
            ->get();

        $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 6)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 6);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 6)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 6)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 6);
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 6)->whereHas('costCenter', function($query) {
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
        return view('livewire.w-f-p.generate-ppmp-q1');
    }

    // 101 - Continuing

    //101
    public function sksuPpmp101Con()
    {
        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'sksuPpmp101Con';
        $this->title = 'Sultan Kudarat State University';


        $temp_fund_allocation = FundAllocation::selectRaw(
            'fund_allocations.wpf_type_id,fund_allocations.is_supplemental, category_groups.id as category_group_id,
                category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
        )
            ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
            ->where('fund_allocations.fund_cluster_w_f_p_s_id', 9)
            ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
            ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
            // ->where('fund_allocations.is_supplemental', 1)
            ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name', 'fund_allocations.is_supplemental')
            ->get();


        $this->forwarded_ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 9)
                ->where('is_supplemental', 0)->where('is_approved',1);
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

        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 9)
                ->where('is_supplemental', 1)->where('is_approved',1);
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


        $this->non_supplemental_fund_allocation = $temp_fund_allocation->where('is_supplemental', 0);
        $supplemental_fund_allocation = $temp_fund_allocation->where('is_supplemental', 1)->pluck('category_group_id')->toArray();

        $this->fund_allocation = $temp_fund_allocation->filter(function ($allocation) use ($supplemental_fund_allocation) {
            return $allocation->is_supplemental || (!in_array($allocation->category_group_id, $supplemental_fund_allocation) && $allocation->is_supplemental == 0);
        });


        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 9)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;


        // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 9)->where('is_approved', 1);
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', 9)->where('is_approved', 1);
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    //   REMOVED EXPORT

    public function gasPpmp101Con()
    {
        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'none';
        $this->title = 'General Admission and Support Services';

        $this->fund_allocation = FundAllocation::selectRaw(
            'fund_allocations.wpf_type_id, category_groups.id as category_group_id,
                category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
        )
            ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('m_f_o_s', 'cost_centers.m_f_o_s_id', '=', 'm_f_o_s.id')
            ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
            ->where('fund_allocations.fund_cluster_w_f_p_s_id', 9)
            ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
            ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
            ->where('fund_allocations.is_supplemental', 1)
            ->where('m_f_o_s.id', 1)
            ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name')
            ->get();


        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 9)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
            $query->where('fund_cluster_w_f_p_s_id', 9)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 1);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 9)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 1);
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 9)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 1);
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();

    }

    public function hesPpmp101Con()
    {
        $this->is_active = false;
        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'none';
        $this->title = 'Higher Education Services';

        $this->fund_allocation = FundAllocation::selectRaw(
            'fund_allocations.wpf_type_id, category_groups.id as category_group_id,
                category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
        )
            ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('m_f_o_s', 'cost_centers.m_f_o_s_id', '=', 'm_f_o_s.id')
            ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
            ->where('fund_allocations.fund_cluster_w_f_p_s_id', 9)
            ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
            ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
            ->where('fund_allocations.is_supplemental', 1)
            ->where('m_f_o_s.id', 2)
            ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name')
            ->get();


        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 9)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
            $query->where('fund_cluster_w_f_p_s_id', 9)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 2);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 9)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 2);
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 9)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 2);
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function aesPpmp101Con()
    {
        $this->is_active = false;
        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'none';
        $this->title = 'Advanced Education Services';

        $this->fund_allocation = FundAllocation::selectRaw(
            'fund_allocations.wpf_type_id, category_groups.id as category_group_id,
                category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
        )
            ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('m_f_o_s', 'cost_centers.m_f_o_s_id', '=', 'm_f_o_s.id')
            ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
            ->where('fund_allocations.fund_cluster_w_f_p_s_id', 9)
            ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
            ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
            ->where('fund_allocations.is_supplemental', 1)
            ->where('m_f_o_s.id', 3)
            ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name')
            ->get();


        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 9)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
            $query->where('fund_cluster_w_f_p_s_id', 9)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 3);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 9)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 3);
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 9)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 3);
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function rdPpmp101Con()
    {
        $this->is_active = false;
        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'none';
        $this->title = 'Research and Development';

        $this->fund_allocation = FundAllocation::selectRaw(
            'fund_allocations.wpf_type_id, category_groups.id as category_group_id,
                category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
        )
            ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('m_f_o_s', 'cost_centers.m_f_o_s_id', '=', 'm_f_o_s.id')
            ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
            ->where('fund_allocations.fund_cluster_w_f_p_s_id', 9)
            ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
            ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
            ->where('fund_allocations.is_supplemental', 1)
            ->where('m_f_o_s.id', 4)
            ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name')
            ->get();


        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 9)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
            $query->where('fund_cluster_w_f_p_s_id', 9)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 4);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 9)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 4);
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 9)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 4);
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function extensionPpmp101Con()
    {
        $this->is_active = false;
        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'none';
        $this->title = 'Extension Services';

        $this->fund_allocation = FundAllocation::selectRaw(
            'fund_allocations.wpf_type_id, category_groups.id as category_group_id,
                category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
        )
            ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('m_f_o_s', 'cost_centers.m_f_o_s_id', '=', 'm_f_o_s.id')
            ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
            ->where('fund_allocations.fund_cluster_w_f_p_s_id', 9)
            ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
            ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
            ->where('fund_allocations.is_supplemental', 1)
            ->where('m_f_o_s.id', 5)
            ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name')
            ->get();


        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 9)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
            $query->where('fund_cluster_w_f_p_s_id', 9)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 5);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 9)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 5);
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 9)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 5);
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function lfPpmp101Con()
    {
        $this->is_active = false;
        $this->is_active = true;
        $this->showPre = false;
        $this->activeButton = 'none';
        $this->title = 'Local Fund Projects';

        $this->fund_allocation = FundAllocation::selectRaw(
            'fund_allocations.wpf_type_id, category_groups.id as category_group_id,
                category_groups.name as name, SUM(fund_allocations.initial_amount) as total_allocated'
        )
            ->join('category_groups', 'fund_allocations.category_group_id', '=', 'category_groups.id')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('m_f_o_s', 'cost_centers.m_f_o_s_id', '=', 'm_f_o_s.id')
            ->join('wfps', 'cost_centers.id', '=', 'wfps.cost_center_id') // Ensure wfp exists
            ->where('fund_allocations.fund_cluster_w_f_p_s_id', 9)
            ->where('fund_allocations.wpf_type_id', $this->selectedType) // Explicit table name
            ->where('fund_allocations.initial_amount', '>', 0) // Explicit table name
            ->where('fund_allocations.is_supplemental', 1)
            ->where('m_f_o_s.id', 6)
            ->groupBy('fund_allocations.wpf_type_id', 'category_groups.id', 'category_groups.name')
            ->get();


        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            $query->where('fund_cluster_w_f_p_s_id', 9)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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
            $query->where('fund_cluster_w_f_p_s_id', 9)
                ->where('is_supplemental', 1)
                ->where('is_approved',1)->whereHas('costCenter', function ($query) {
                    $query->where('m_f_o_s_id', 6);
                });
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 9)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 6);
        //     });
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 9)->whereHas('costCenter', function($query) {
        //     $query->where('m_f_o_s_id', 6);
        //     });
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }

    public function sksuPre($fundCluster)
    {
        $this->is_active = true;
        $this->showPre = true;
        $this->activeButton = 'sksuPre';
        $this->title = 'Sultan Kudarat State University PRE';
        $this->preId = $fundCluster;

        $tem_fund_allocation = FundAllocation::selectRaw('wpf_type_id,is_supplemental, mfo_fees.id as mfo_fee_id, mfo_fees.name as name, SUM(initial_amount) as total_allocated')
            ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
            ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
            ->where('mfo_fees.fund_cluster_w_f_p_s_id', $fundCluster)
            // ->where('is_supplemental', 1)
            ->where('wpf_type_id', $this->selectedType)
            ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name', 'is_supplemental')
            ->whereHas('costCenter.wfp')
            ->get();

        $this->non_supplemental_fund_allocation = $tem_fund_allocation->where('is_supplemental', 0);
        $supplemental_fund_allocation = $tem_fund_allocation->where('is_supplemental', 1)->pluck('category_group_id')->toArray();

        $this->fund_allocation = $tem_fund_allocation->filter(function ($allocation) use ($supplemental_fund_allocation) {
            return $allocation->is_supplemental || (!in_array($allocation->category_group_id, $supplemental_fund_allocation) && $allocation->is_supplemental == 0);
        });



        $mfo_ids = $this->fund_allocation->pluck('mfo_fee_id')->toArray();

        $this->total_allocated = $this->fund_allocation->sum('total_allocated');
        $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) use ($fundCluster) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', $fundCluster)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();

        $this->balance = $this->total_allocated - $this->total_programmed->total_budget;
        $this->non_supplemental_total_programmed = WfpDetail::whereHas('wfp', function ($query) use ($fundCluster) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', $fundCluster)
                ->where('is_supplemental', 0)->where('is_approved',1);
        })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) use ($fundCluster) {
            $query->where('wpf_type_id', $this->selectedType)->where('fund_cluster_w_f_p_s_id', $fundCluster)
                ->where('is_supplemental', 1)->where('is_approved',1);
        })
            ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            ->join('cost_centers', 'wfps.cost_center_id', '=', 'cost_centers.id') // Join with the cost_centers table
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

        // dd($this->non_supplemental_fund_allocation->sum('total_allocated') - $this->forwarded_ppmp_details->sum('total_budget'));
        // dd($this->ppmp_details);

        // $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 3);
        // })->select('category_item_id', \DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))
        // ->groupBy('category_item_id')
        // ->get();
        // $this->total = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('wpf_type_id', $this->selectedType)->where('is_approved', 1)->where('fund_cluster_w_f_p_s_id', 3);
        // })->select(\DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
    }
}
