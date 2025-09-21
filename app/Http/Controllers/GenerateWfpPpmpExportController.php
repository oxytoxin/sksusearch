<?php

namespace App\Http\Controllers;

use App\Exports\GenerateWfpPpmpExport;
use App\Models\WfpDetail;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
class GenerateWfpPpmpExportController extends Controller
{
    public function index()
    {
         $records = WfpDetail::where('is_ppmp', 1)->whereHas('wfp', function ($query) {
            $query->where('wpf_type_id', request()->input('wfp_type_id'))
             ->where('fund_cluster_w_f_p_s_id', request()->input('fund_cluster_w_f_p_s_id'))
             ->where('is_approved', 1)
            ->when(is_null(request()->input('supplemental_quarter_id')), function ($query) {
                $query->where('is_supplemental', 0);
            })
             ->when(!is_null(request()->input('supplemental_quarter_id')), function ($query) {
                $query->where('supplemental_quarter_id', request()->input('supplemental_quarter_id'));
            })
            ->when(request()->input('m_f_o_s_id') || request()->input('campus_id'), function ($query) {
                 $query->whereHas('costCenter', function($query) {
                    $query->when(request()->input('m_f_o_s_id'), function ($query) {
                        $query->where('m_f_o_s_id', request()->input('m_f_o_s_id'));
                    })
                    ->when(request()->input('campus_id'), function ($query) {
                        $query->whereHas('office', function($query) {
                            $query->where('campus_id', request()->input('campus_id'));
                        });
                    });
                 });
            });
         })
         ->with(['supply', 'categoryItem','wfp'])  // Load both relationships
         ->selectRaw('supply_id, category_item_id, uacs_code, budget_category_id, uom, SUM(total_quantity) as total_quantity, cost_per_unit, SUM(cost_per_unit * total_quantity) as estimated_budget, JSON_ARRAYAGG(quantity_year) as merged_quantities')
         ->groupBy('supply_id', 'category_item_id', 'uacs_code', 'cost_per_unit', 'uom', 'budget_category_id')
         ->get();
        $total = $records->sum('estimated_budget');
        foreach ($records as $record) {
            $mergedQuantities = array_fill(0, 12, 0); // initialize with 12 months
            foreach (json_decode($record->merged_quantities) as $quantities) {
                $quantitiesArray = json_decode(json_encode($quantities), true);
                foreach ($quantitiesArray as $monthIndex => $value) {
                    $mergedQuantities[$monthIndex] += (int) $value;
                }
            }
            $record->merged_quantities = $mergedQuantities;
        }

        if(app()->environment('local')) {
            return view('generate-wfp-ppmp-report',[
            'record' => $records,
            'total' => $total
            ]);
        }


      $data = new GenerateWfpPpmpExport($records,$total);
      $fileName = request('fileName') ?? 'WFP-PPMP';
      return  Excel::download($data, $fileName.'.xlsx');
    }
}
