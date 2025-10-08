<?php

    namespace App\Http\Controllers;

    use App\Exports\CostCenterPreExport;
    use App\Models\CostCenter;
    use App\Models\FundAllocation;
    use App\Models\WfpDetail;
    use DB;
    use Illuminate\Http\Request;
    use Maatwebsite\Excel\Facades\Excel;

    class TestController extends Controller
    {

        public function __invoke(Request $request)
        {
                $cost_centers = $this->getCostCenterWithFundAllocations($request);
                $total_allocated = $cost_centers->sum('total_initial_amount');
                $total_programmed = $this->getTotalProgrammed($request->input('is_supplemental'),
                    $request->input('fund_cluster_id'), $request->input('wfp_type_id'), $request->input('m_f_o_s_id'), $request->input('supplementalQuarterId'));
                $total_balance = $total_allocated - $total_programmed->total_budget;

                $mfo_fee_ids = $cost_centers->pluck('fund_allocations.*.mfo_fee_id')->flatten()->unique()->toArray();
                $wfp_details = $this->getWfpDetails($request, $mfo_fee_ids);
                foreach ($cost_centers as $cost_center) {
                    $cost_center->wfpDetails = $wfp_details->where('cost_center_id', $cost_center->id);
                    $cost_center->totalWfpDetails = $wfp_details->where('cost_center_id',
                        $cost_center->id)->sum('total_budget_per_uacs');
                }

                $fileName = date('Y-m-d').'export';

                if ($request->has('fileName')) {
                    $fileName = $request->input('fileName');
                }

                if (config('app.env') == 'local') {
                    return view('exports.cost-center-164', [
                        'cost_centers' => $cost_centers,
                        'total_allocated' => $total_allocated,
                        'total_programmed' => $total_programmed,
                        'total_balance' => $total_balance,
                    ]);
                }

                return Excel::download(new CostCenterPreExport($cost_centers, $total_allocated,
                    $total_programmed->total_budget, $total_balance), $fileName.'.xlsx');

        }

        public function getCostCenterWithFundAllocations(Request $request)
        {
            $temp_fund_allocations = FundAllocation::selectRaw('wpf_type_id,is_supplemental, mfo_fees.id as mfo_fee_id, mfo_fees.name as name,GROUP_CONCAT(cost_centers.id) as cost_center_ids,
        GROUP_CONCAT(CONCAT(cost_centers.id, ":", initial_amount)) as cost_center_amounts, offices.name as office_name')
                ->join('cost_centers', 'fund_allocations.cost_center_id', '=', 'cost_centers.id')
                ->join('mfo_fees', 'cost_centers.mfo_fee_id', '=', 'mfo_fees.id')
                ->join('offices', 'cost_centers.office_id', '=', 'offices.id') // Join offices table
                ->when($request->has('fund_cluster_id'), function ($query) use ($request) {
                    $query->where('mfo_fees.fund_cluster_id', $request->input('fund_cluster_id'));
                })
                ->when($request->has('wfp_type_id'), function ($query) use ($request) {
                    $query->where('wpf_type_id', $request->input('wfp_type_id'));
                })
                ->when($request->input('m_f_o_s_id'), function ($query) use ($request) {
                    $query->where('cost_centers.m_f_o_s_id', $request->input('m_f_o_s_id'));
                })
                ->when($request->has('supplementalQuarterId'), function ($query) use ($request) {
                    $query->where('fund_allocations.supplemental_quarter_id', $request->input('supplementalQuarterId'));
                })
                ->when(is_null($request->input('supplementalQuarterId')), function ($query) {
                    $query->where('fund_allocations.is_supplemental', 0);
                })
                ->groupBy('wpf_type_id', 'mfo_fees.id', 'mfo_fees.name', 'is_supplemental', 'offices.name')
                ->get()->map(function ($item) {
                    return [
                        'wpf_type_id' => $item->wpf_type_id,
                        'is_supplemental' => $item->is_supplemental,
                        'mfo_fee_id' => $item->mfo_fee_id,
                        'name' => $item->name,
                        'cost_center_amounts' => $item->cost_center_amounts,
                        'cost_center_ids' => $item->cost_center_ids,
                        'office_name' => $item->office_name
                    ];
                });


            $fund_allocations = [];

            foreach ($temp_fund_allocations as $fund_allocation) {
                $cost_center_amounts = explode(',', $fund_allocation['cost_center_amounts']);
                foreach ($cost_center_amounts as $cost_center_amount) {
                    $cost_center_amount = explode(':', $cost_center_amount);

                    $fund_allocations[] = [
                        'wpf_type_id' => $fund_allocation['wpf_type_id'],
                        'is_supplemental' => $fund_allocation['is_supplemental'],
                        'mfo_fee_id' => $fund_allocation['mfo_fee_id'],
                        'name' => $fund_allocation['name'],
                        'cost_center_id' => $cost_center_amount[0],
                        'initial_amount' => $cost_center_amount[1],
                        'office_name' => $fund_allocation['office_name']
                    ];
                }
            }

            $cost_center_ids = collect($fund_allocations)->pluck('cost_center_id')->unique()->toArray();

            $cost_centers = CostCenter::whereIn('id', $cost_center_ids)->get()->map(function ($cost_center) use (
                $fund_allocations
            ) {
                $cost_center->fund_allocations = collect($fund_allocations)->where('cost_center_id',
                    $cost_center->id)->toArray();
                $cost_center->total_initial_amount = collect($cost_center->fund_allocations)->sum('initial_amount');
                return $cost_center;
            });
            return $cost_centers;
        }

        public function getTotalProgrammed($is_supplemental, $fund_cluster_id, $selectedType, $m_f_o_s_id,$supplementalQuarterId)
        {
            return WfpDetail::whereHas('wfp',
                function ($query) use ($selectedType, $is_supplemental, $fund_cluster_id, $m_f_o_s_id, $supplementalQuarterId) {
                    $query->where('wpf_type_id', $selectedType)
                        ->where('fund_cluster_id', $fund_cluster_id)
                        ->when(!is_null($is_supplemental), function ($query) use ($is_supplemental, $supplementalQuarterId) {
                            $query->where('supplemental_quarter_id', $supplementalQuarterId);
                        })
                        ->when(is_null($is_supplemental), function ($query) {
                            $query->where('is_supplemental', 0);
                        })
                        ->where('is_approved', 1)
                        ->when($m_f_o_s_id, function ($query) use ($m_f_o_s_id) {
                            $query->whereHas('costCenter', function ($query) use ($m_f_o_s_id) {
                                $query->where('m_f_o_s_id', $m_f_o_s_id);
                            });
                        });
                })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
        }

        public function getWfpDetails(Request $request, array $mfo_fee_ids)
        {
            $data = WfpDetail::whereHas('wfp', function ($query) use ($request) {
                $query->where('wpf_type_id', $request->input('wfp_type_id'))
                    ->where('fund_cluster_id', $request->input('fund_cluster_id'))
                    ->when(!is_null($request->input('supplementalQuarterId')), function ($query) use ($request) {
                        $query->where('supplemental_quarter_id', $request->input('supplementalQuarterId'));
                    })
                    ->when(is_null($request->input('supplementalQuarterId')), function ($query) {
                        $query->where('is_supplemental', 0);
                    })
                    ->where('is_approved', 1);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->join('cost_centers', 'wfps.cost_center_id', '=',
                    'cost_centers.id') // Join with the cost_centers table
                ->select(
                    DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    'wfps.cost_center_id as cost_center_id',
                    'cost_centers.mfo_fee_id as mfo_fee_id',
                    DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
                )
                ->whereIn('cost_centers.mfo_fee_id', $mfo_fee_ids)
                ->when($request->input('m_f_o_s_id'), function ($query) use ($request) {
                    $query->where('cost_centers.m_f_o_s_id', $request->input('m_f_o_s_id'));
                })
                ->groupBy('budget_uacs', 'budget_name', 'cost_center_id', 'mfo_fee_id')
                ->get();
            return $data;
        }
    }
