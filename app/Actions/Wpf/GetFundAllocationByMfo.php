<?php

namespace App\Actions\Wpf;

use App\Models\FundAllocation;

class GetFundAllocationByMfo
{
    public function exec($mfoId,$fundClusterId,$wfpType,$quarterId = null): array
    {
        $forwaredIsWfp = $quarterId == 1;
        $fundAllocations = FundAllocation::query()
                ->with(["categoryGroup","costCenter"=> ['mfo','wfp']])
                ->where('wpf_type_id', $wfpType)
                ->when(!is_null($quarterId) && $forwaredIsWfp, function($query) use ($quarterId){
                    $query->whereNull('supplemental_quarter_id')
                        ->orWhere('supplemental_quarter_id', $quarterId);
                })
                ->when(!is_null($wfpType) && $forwaredIsWfp, function($query) use ($quarterId){
                    $query->whereIn('supplemental_quarter_id', [$quarterId,$quarterId - 1]);
                })
                ->whereHas('costCenter', function($query) use ($mfoId){ $query->where('m_f_o_s_id', $mfoId); })
                ->where('initial_amount', '>', 0)
                ->where('fund_cluster_w_f_p_s_id', $fundClusterId)
                ->get()
                ->groupBy(function($item){
                    return $item->is_supplemental.$item->category_group_id;
                });

        return $fundAllocations->toArray();
    }
}
